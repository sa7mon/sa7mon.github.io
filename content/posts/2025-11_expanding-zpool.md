+++
date = '2025-11-16T18:35:51-06:00'
draft = false
title = 'Expanding a Zpool One Disk At a Time'
type = 'post'
description = ''
tags = ['zfs', 'proxmox']
slug = 'expanding-zpool'
+++

While discussing some benchmarks I ran on the zpool in my Proxmox server, it was suggested that the SSDs backing the pool were severely bottlenecking performance. The pool consisted of 4x WD Blue 1TB SATA SSDs which I chose years ago for budget reasons. After doing some research on the benefits of upgrading from consumer to enterprise SSDs - lower latency and higher endurance to name two - I started hunting on eBay for suitable drives.

I ended up choosing the 1.92TB Micron 5300 Pro. With an eBay alert and some patience I procured 4 used drives for an average price of about $124 shipped.

With the new drives in hand and no SATA ports to spare in the Proxmox server, I needed to replace the drives in the pool one at a time. It was easier than I thought it would be!

Here's how the pool looked at the start.

```
root@proxmox:~# zfs list ssdtank
NAME      USED  AVAIL  REFER  MOUNTPOINT
ssdtank  1.11T   663G  16.0G  /ssdtank

root@proxmox:~# zpool status ssdtank
  pool: ssdtank
 state: ONLINE
  scan: scrub repaired 0B in 00:27:05 with 0 errors on Sun Oct 12 00:51:07 2025
config:

        NAME                                    STATE     READ WRITE CKSUM
        ssdtank                                 ONLINE       0     0     0
          mirror-0                              ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_19416F800207  ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200559800082  ONLINE       0     0     0
          mirror-1                              ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608800160  ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608802113  ONLINE       0     0     0

errors: No known data errors
```

The first step was to enable `autoexpand` on the pool so it would automatically grow once all the drives had been replaced: `zpool set autoexpand=on ssdtank`. Another option that I did not use would have been to run `zpool online -e <pool> <device>` on each new drive after replacement.

Starting with a random drive, I offline'd it which immediately put the pool into a degraded state. In this state, the pool is still usable and will continue serving the filesystem but the performace will suffer until the offline drive is replaced. The pool is also in danger of data loss if you happen to lose the other drive(s) in the vdev before the drive replacement is finished.

```
root@proxmox:~# zpool offline ssdtank ata-WDC_WDBNCE0010PNC_19416F800207
root@proxmox:~# zpool status ssdtank
  pool: ssdtank
 state: DEGRADED
status: One or more devices has been taken offline by the administrator.
        Sufficient replicas exist for the pool to continue functioning in a
        degraded state.
action: Online the device using 'zpool online' or replace the device with
        'zpool replace'.
  scan: scrub repaired 0B in 00:27:05 with 0 errors on Sun Oct 12 00:51:07 2025
config:

        NAME                                    STATE     READ WRITE CKSUM
        ssdtank                                 DEGRADED     0     0     0
          mirror-0                              DEGRADED     0     0     0
            ata-WDC_WDBNCE0010PNC_19416F800207  OFFLINE      0     0     0
            ata-WDC_WDBNCE0010PNC_200559800082  ONLINE       0     0     0
          mirror-1                              ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608800160  ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608802113  ONLINE       0     0     0

errors: No known data errors
```

Since my motherboard (and I assume most other modern boards) supports hot-swapping SATA drives, I did not need to power the server down before removing the offline drive and replacing it with one of the new drives. It's very important at this point that the *correct* drive is removed. Lucky for me, the serial numbers of the installed drives were visible in the custom drive cage [I made](/posts/new-proxmox-server) and the disk ID used by ZFS seemed to follow the pattern `ata-brand_model_serial`.

After removing the old drive and connecting the new one in its place, I instructed ZFS to replace the offlined drive with the new one.

```
root@proxmox:~# zpool replace ssdtank ata-WDC_WDBNCE0010PNC_19416F800207 ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F
invalid vdev specification
use '-f' to override the following errors:
/dev/disk/by-id/ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F contains a filesystem of type 'ddf_raid_member'
```

Oops, looks like the used drive I bought still had some filesystem remnants. `wipefs` made short work of that.

```
root@proxmox:~# wipefs -a /dev/disk/by-id/ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F
/dev/disk/by-id/ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F: 4 bytes were erased at offset 0x1bf1fc55e00 (ddf_raid_member): de 11 de 11
root@proxmox:~# zpool replace ssdtank ata-WDC_WDBNCE0010PNC_19416F800207 ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F
```

At this point, the pool began resilvering the drive as if I had just replaced a failed drive.

```
root@proxmox:~# zpool status ssdtank
  pool: ssdtank
 state: DEGRADED
status: One or more devices is currently being resilvered.  The pool will
        continue to function, possibly in a degraded state.
action: Wait for the resilver to complete.
  scan: resilver in progress since Fri Nov  7 19:19:09 2025
        1.06T / 1.06T scanned, 111G / 545G issued at 378M/s
        111G resilvered, 20.43% done, 00:19:36 to go
config:
        NAME                                              STATE     READ WRITE CKSUM
        ssdtank                                           DEGRADED     0     0     0
          mirror-0                                        DEGRADED     0     0     0
            replacing-0                                   DEGRADED     0     0     0
              ata-WDC_WDBNCE0010PNC_19416F800207          OFFLINE      0     0     0
              ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F  ONLINE       0     0     0  (resilvering)
            ata-WDC_WDBNCE0010PNC_200559800082            ONLINE       0     0     0
          mirror-1                                        ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608800160            ONLINE       0     0     0
            ata-WDC_WDBNCE0010PNC_200608802113            ONLINE       0     0     0

errors: No known data errors
```

After about 45 minutes, the resilvering completed and the pool was once again healthy.

I repeated this "offline, swap, replace" process for each of the remaining drives until finally I was left with a healthy 3.48TB pool.

```
root@proxmox:~# zpool status ssdtank
  pool: ssdtank
 state: ONLINE
  scan: resilvered 546G in 00:58:41 with 0 errors on Fri Nov  7 23:14:02 2025
config:

        NAME                                            STATE     READ WRITE CKSUM
        ssdtank                                         ONLINE       0     0     0
          mirror-0                                      ONLINE       0     0     0
            ata-Micron_5300_MTFDDAK1T9TDS_2018287ED55F  ONLINE       0     0     0
            ata-Micron_5300_MTFDDAK1T9TDS_220735232EF9  ONLINE       0     0     0
          mirror-1                                      ONLINE       0     0     0
            ata-Micron_5300_MTFDDAK1T9TDS_2018287ED7C6  ONLINE       0     0     0
            ata-Micron_5300_MTFDDAK1T9TDS_193924CAA1C6  ONLINE       0     0     0

errors: No known data errors
```

With the new disks in place, read/write speeds as well as IOPS increased by about 2.25x according to my novice level `fio` tests.
  