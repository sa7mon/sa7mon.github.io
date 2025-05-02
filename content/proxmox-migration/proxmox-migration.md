---
author: "Dan Salmon"
date: "2025-05-02T00:00:00Z"
description: ""
draft: true
tags: ["proxmox", "zfs"]
slug: ""
title: ""
type: "post"
---

As I've talked about [previously](https://danthesalmon.com/state-of-homelab-2023/), all compute workloads in my homelab are run on an older HP Z620 workstation. This machine runs everything I need it to without complaint and has done so for years. Recently I started thinking more about replacing it, or at least transplanting its components into something I can rackmount. I don't like that it's the only piece of homelab gear that doesn't reside neatly in the 18U rack.

I would like to move to something rackmount-able because:
    - I've never liked some of the the proprietary HP internals - non-standard PSU, drive trays that I never used (instead, I just let drives hang freely)
    - rackmounted gear is cooler than free-standing desktops
    - all the gear is neatly organized in the rack
    - did I mention it's cooler

Initially I looked for methods by which I could rackmount the Z620. HP does make a rails kit (HP PN: B8S55AA) that mounts on the top and bottom of the chassis, allowing you to rack the desktop sideways, but it takes up 4U of space and kits on the secondary market were ~$150. 

As luck would have it, a kind nerd at work posted that he was giving away a Ryzen 5 1600 and cooler. I acquired the CPU and thus made up my mind that I would build a new machine to replace the Z620.

## Part Picking

My goals for the build were:
    - cheap
    - re-use as many components as possible
    - TDP <= current levels
    - relatively quiet
    - most important: rack-mounted

The parts I knew I wanted to re-use were:

- 5x SSDs - 4 for the ZFS pool, 1 for the OS
- Mellanox ConnectX-4 10Gbe network card to connect the machine directly to the TrueNAS storage server
- Intel Arc A380 GPU for Jellyfin transcoding
- LSI 9200-8I HBA - if needed, to connect all the SATA drives to the motherboard

+ okay let's build around this CPU since it's lower wattage but more power (TODO: verify)

### Chassis

The first limiting factor to decide on was the size (in rack units: RU) of the chassis. 4U would certainly fit everything, but I think I can get away with something much smaller. I am starting to run out of space in this 18U rack after all. 2U looked to be tall enough to accommodate all the components except the GPU which is a full-height PCIe card.

I looked around online, heavily relying on /r/homelab recommendations for chassis options. The 2 that looked the most promising were:

Rosewill [RSV-Z2600U](https://www.rosewill.com/rosewill-rsv-z2600u-black/p/9SIA072GJ92852?seoLink=server-components&seoName=Server%20Chassis) has space for 4 3.5" HDDs which I knew would be more than enough room to fit 5 SSDs if I used at least 1 dual SSD mounting bracket (like [this](https://www.amazon.com/Corsair-Dual-Mounting-Bracket-CSSD-BRKT2/dp/B016498CK0?th=1)).


Plinkusa [IPC-2026-BK](https://www.plinkusa.net/web2026s.htm) which advertises space for 6x 3.5" drives and, crucially, an apparently patented bracket for mounting full-height PCIe cards horizontally (parallel to the motherboard). 

Both were around the $100 range, but I ultimately decided on the Plinkusa chassis both because of the PCIe adapter and because it supports a full ATX motherboard while the Rosewill only supports Micro-ATX.

- As luck would have it, someone was selling the exact model I was looking for on Craigslist for $70 and it came with an Inland (TODO: wattage?) PSU. This was incredibly fortunate since it saved me the $55(!) in shipping a brand new unit would have cost.

After getting the chassis home, I realized that whatever adapter thing this case normally comes with for full-height PCIe mounting, mine didn't come with it. I emailed the manufacturer asking how I could buy just this adapter for my specific chassis. It took a few days of back and forth, mostly just identifying which chassis I had - it seemed like this company has sold many iterations. Finally, I was quoted a price of $30 (including shipping) for a "3 slot riser card window with full set accessory box". The price seemed reasonable, but:

- They asked me to "send $30 to our paypal account sales@plinkusa.net" which felt a bit scammy
- In the time it had taken to arrive at this price, I had already designed and 3D-printed a prototype that was 85% complete and cost pennies to print.

[TODO picture of prototype]

Since the PCI Express standard is well defined and documented, the process was a breeze once I understood how I needed to orient things. Maybe in the future if this print fails or I decide to upgrade I'll order the official part, but for now this should be fine.

### Motherboard

- That week I shopped around for a cheap AM4 motherboard that had 5+ SATA 6Gbs ports. Once again I got lucky and found a deal on Craigslist: a used Gigabyte B450 AORUS Pro for $75.

I started doing some test fits, laying which components would go where when I realized that I might not have enough PCI lanes available with this motherboard if I use the LSI HBA. I summarized the situation in [a post](https://www.reddit.com/r/homelab/comments/1ilrcbo/does_my_proxmox_build_have_enough_pcie_lanes/) on /r/homelab.

After some very helpful replies and thinking better of some harebrained ideas, I arrived at a solution that would ensure enough PCI lanes were available:

1. Delete the HBA - connect the drives directly to the onboard SATA ports using some [low-profile 180-degree adapters](https://www.ebay.com/itm/126307406194)
2. Move the SSDs to the very front of the chassis, designing and printing some kind of bracket to hold them all
3. Plug the Mellanox 10Gbe card into the second PCIe slot
4. Run a riser cable over the Mellanox card to connect the GPU to the top [TODO: xWhat] PCIe port

### SSDs

For my next part created from scratch, I designed and 3D printed an SSD caddy that would:

- slot into the existing 3.5" drive cage
- securely hold at least 5x 2.5" SATA SSDs on edge
- have adequate space between the drives for airflow
- allow for removal of the drives individually without removing the whole caddy. Once everything is installed, I don't want to have to unplug all the cables and remove the whole caddy just to replace a single drive.

Again, because the 2.5" drive follows a well-defined spec (_SFF-8201: 2.5 Form Factor Drive Dimensions_), the design process went very smoothly once I came up with a general design that would allow for single drive removal. 2 re-prints and a few tweaks in OnShape later, I had a part that fit all my criteria. 

[TODO pic(s) of part and installed]
[TODO: post to Printables]

I can't recommend enough a proper set of calipers for designing parts from scratch.

I ordered a set of thin SATA cables that would allow for a pretty tight bend radius to connect the drives at the front of the chassis.

### Booting

It was time to actually try booting the machine! I installed the RAM and CPU sans cooler, plugged it in, and hit the power button. 

Nothing.

No POST, not even any fanspin. I dug out my trusty RadioShack multimeter and tested the PSU cables. All of the 5V and 12V rails seemed fine and I tried plugging in a known-good PSU to no avail. The problem was likely either a dead CPU or dead motherboard. 

I ordered a "certified refurbished" MSI B450 Gaming Plus Max from eBay; with coupon it came to $88 which seemed fine considering it came with a 2-year warranty. After installing the new board, I crossed my fingers and hit the button. It booted up just fine!

### Cooling

Now it was time to move on to cooling. The AMD Wraith cooler that had come with the Ryzen 5 was too tall for this 2U chassis so I needed to find something lower profile.

I compared a few Dynatron coolers including the A47 and A43 but ultimately decided against them because of noise levels.

[TODO picture of A47: "a side-mounted fan cooler that blows air back instead of up makes more sense in a server chassis, but 59.8 dBA at 100% duty cycle is just too loud. "]

Instead, I picked up a Noctua NH-L9A-AM4 from Micro Center (which, if I'm honest, was also a major selling point). After mounting it with the included thermal paste, I booted the machine up and explored the BIOS settings. It was constantly complaining about the CPU temp, but something about the reading seemed fishy.

[TODO: picture of warning and explanation of how hot that would be]

I checked for a BIOS update and sure enough the version after the one installed contained a relevant release note:

> -  Fixed incorrect display of CPU temperature and fan speed

After updating it via USB drive, the temp and fan speeds in the BIOS were sane.

The 4 80mm YATE LOON D80SM-12 fans were pretty scratchy sounding, even with the chassis lid closed. To remedy this, I ordered 4 used Sunon fans from eBay, but didn't realize they would come with Dell's proprietary 5-pin fan connectors. I tried to re-pin them to work with the 4-pin PWM fan headers on the motherboard, but ended up returning them and getting 4 Noctua NF-A8 fans which worked perfectly.

## Transplanting

At this point I was ready to do the hardware transplant. I had setup another machine with Proxmox and migrated the critical services (mostly Pihole) and could now afford downtime. So on a Saturday night, I shut down the HP workstation and got the SSDs, 10G card, and GPU moved over to their new home.

After plugging in the riser cable for the GPU, I realized how comically long it was for this application. Thankfully it was flexible enough that I could still get the chassis lid closed without squishing the cable too hard. At this point, I realized that I couldn't plug any video cables into the rear of the GPU: there was not enough clearance at the top of the bracket.

Back to OnShape I went to quickly delete the 3rd PCIe slot (I only needed the top 2 anyway) and move the others down. I re-printed the bracket and got it re-installed. Much better.

Next, I installed the rails. The included instructions were really lacking, but thankfully [this serverbuilds.net post](https://web.archive.org/web/20250117083018/https://forums.serverbuilds.net/t/rack-mounting-the-rosewill-rsv-l4500/2804) did a great job showing which holes to use on the rack bracket. After unscrewing and re-mounting the rails a total of 3 times, everything fit correctly and I no longer had gaps above the server!

## Turning the Key

With the server racked, I booted the machine with all the new components for the first time. I was unceremoniously greeted with an error message from GRUB:

```
    error: symbol `grub_is_lockdown` not found. Entering rescue mode
```

I followed the recovery steps listed in the Proxmox wiki [here](https://pve.proxmox.com/wiki/Recover_From_Grub_Failure) and tried some suggestions from various Linux forums but I could not get the system to boot. I tried changing some of the boot options in the motherboard, toggling UEFI / CSM / Secure Boot support, to no avail.

I decided the quickest way to get past this was a fresh Proxmox re-installation. I knew all my VMs and containers were safe on the ZFS pool and backed up to the TrueNAS server, so I would only need to re-setup a few things. I wiped the boot drive and installed the latest Proxmox from USB drive. One benefit to doing this was that I opted for setting up the boot drive as a single-drive ZFS pool - the previous installation had used the default ext4. This was I can easily snapshot and backup the Proxmox host in addition to the VMs and containers. Nice!

## Rebuilding

Now that the system booted, I started the work of setting everything back up. I created a bridge with the 10Gbe card, set a static IP, and enabled Jumbo Frames by increasing the MTU. The NIC on the other side of the Direct Attach Cable (DAC) in the TrueNAS box had previously been configured for Jumbo Frames. I verified I could ping in both directions successfully and fired off an `iperf3` test to verify the throughput.

```shell
root@proxmox:~# iperf3 -c 10.50.1.2 -p 3345
Connecting to host 10.50.1.2, port 3345
[  5] local 10.50.1.4 port 57324 connected to 10.50.1.2 port 3345
[ ID] Interval           Transfer     Bitrate         Retr  Cwnd
[  5]   0.00-1.00   sec  1.15 GBytes  9.92 Gbits/sec    0   1.19 MBytes
[  5]   1.00-2.00   sec  1.15 GBytes  9.90 Gbits/sec    0   1.37 MBytes
[  5]   2.00-3.00   sec  1.15 GBytes  9.89 Gbits/sec    0   1.37 MBytes
[  5]   3.00-4.00   sec  1.15 GBytes  9.90 Gbits/sec    0   1.37 MBytes
[  5]   4.00-5.00   sec  1.15 GBytes  9.90 Gbits/sec    0   1.37 MBytes
[  5]   5.00-6.00   sec  1.15 GBytes  9.89 Gbits/sec    0   1.37 MBytes
[  5]   6.00-7.00   sec  1.15 GBytes  9.90 Gbits/sec    0   1.37 MBytes
[  5]   7.00-8.00   sec  1.15 GBytes  9.89 Gbits/sec    0   1.85 MBytes
[  5]   8.00-9.00   sec  1.15 GBytes  9.90 Gbits/sec    0   1.85 MBytes
[  5]   9.00-10.00  sec  1.15 GBytes  9.90 Gbits/sec    0   1.85 MBytes
- - - - - - - - - - - - - - - - - - - - - - - - -
[ ID] Interval           Transfer     Bitrate         Retr
[  5]   0.00-10.00  sec  11.5 GBytes  9.90 Gbits/sec    0             sender
[  5]   0.00-10.00  sec  11.5 GBytes  9.89 Gbits/sec                  receiver

iperf Done.
```

Next, I added the TrueNAS SMB share as a storage backend. This is where all the backups from the older server live - from here I can restore each VM and container one by one.

With my 4-disk ZFS pool added as a storage backend, I restored my main Docker VM. After it had finished, I was a bit confused by what happened. The restore process had created a Zvol when I had used qcow disk images before. After doing some searching around the proxmox forums, I learned that if your storage type is ZFS, VM disks are stored as ZVOLs. If you want to use qcow or raw files instead, you have to configure the storage type as `directory`. The proxmox [manual](https://pve.proxmox.com/pve-docs/pve-admin-guide.html#_storage_types) shows a feature comparison of the two and I opted to keep the ZFS storage type set after watching a few [Tom Lawrence](https://www.youtube.com/channel/UCHkYOD-3fZbuGhwsADBd9ZQ) videos on what ZVOLs are and how they work.

Next I restored the Jellyfin VM and easily setup the PCI passthrough. Proxmox makes this so simple: just add the raw PCI device in the hardware tab.
        
While I was working through the VM restoration, the system had a few instances where it would fall off the network. Nothing would fix it except a hard reboot. I plugged in a monitor and didn't see anything obvious in the system logs.

## Investigating

I needed more data to pinpoint *when* the system failed and to capture more logs to explain *why*. I setup a quick monitoring script on the TrueNAS server which did a curl on the Proxmox web interface at a regular interval and printed the status code. I also started logging `dmesg` output to file on the new server.

The system "crashed" a few more times. Frustratingly, there were never obvious "this process has failed" logs in the output around the time that it happened. I put my [A+](https://www.comptia.org/certifications/a) hat on and replaced the GPU with a simple Nvidia Quadro thinking the issue may be with a video driver or even the riser cable. This didn't help, nor did updating the BIOS or blacklisting all video-related kernel modules via modprobe. 

I ran an overnight memory [test](https://memtest.org/) to rule out a bad RAM module or slot but there were no errors reported. My next idea, that I didn't have much faith in, was that the CPU needed a microcode update given its age. I went down this path for a while before learning that the BIOS actually loads the latest microcode before passing off control to the OS, so I was already up-to-date.

I was at my wits' end scrolling through the start of the dmesg logs when I noticed some lines I hadn't picked up on before

```
[Tue Apr 22 14:41:43 2025] [Firmware Bug]: ACPI MWAIT C-state 0x0 not supported by HW (0x0)
[Tue Apr 22 14:41:43 2025] ACPI: \_PR_.C000: Found 2 idle states
[Tue Apr 22 14:41:43 2025] [Firmware Bug]: ACPI MWAIT C-state 0x0 not supported by HW (0x0)
[Tue Apr 22 14:41:43 2025] ACPI: \_PR_.C002: Found 2 idle states
[Tue Apr 22 14:41:43 2025] [Firmware Bug]: ACPI MWAIT C-state 0x0 not supported by HW (0x0)
[Tue Apr 22 14:41:43 2025] ACPI: \_PR_.C004: Found 2 idle states
```

Some quick searching told me these logs were related to something called ["C-states"](https://en.wikipedia.org/wiki/ACPI#Processor_states). If your CPU does not understand or does not support the state that the ACPI system is requesting, it can cause system instability which results in weird, unstable behavior.

After fumbling though some GRUB config files and getting very confused, I eventually found there's a setting in the MSI BIOS named "Global C-state Control". After disabling this setting, the server finally survived a full 24 hours!

## Wrapping Up

This was a much larger project than I thought it would be. Ultimately, I was able to combine many of my hobbies which was very fun.

Here is the final parts list and cost breakdown

| Part | Model | Cost | Notes |
|------|-------|------|-------|
| Chassis | PlinkUSA IPC-2026-BK | $70 | |
| Motherboard | MSI B450 GAMING PLUS MAX | $88 | $75 (dead) + $88 (working) |
| CPU |AMD Ryzen 5 1600 | $0 | |
| PSU | Inland 500W | $0 | Included with chassis |
| RAM | Corsair Vengeance LPX 16GB (2x8GB) | $48 | |    
| Storage | 4x 1TB WD Blue SATA SSD, 1x 240GB Inland SATA SSD| $0 | Re-used from original system |
| CPU Cooler | Noctua NH-L9a-AM4 | $57 | |
| 2x SATA adapters | Cablecc Dual SATA 7Pin Female to 360 Degree Angled 7Pin Male Adapter | $16 | Generic brand |
| SATA Cables | 6x SATA 6Gbps 0.5m sleeved cable | $11 | Generic brand |
| PCI Riser Cable | Thermaltake AC-058-CO1OTN-C1 | $43 | Hard to find|
| Rails | iStarUSA [TC-RAIL-26](http://www.istarusa.com/en/istarusa/products.php?model=TC-RAIL-26) | $40 | |
| Case fans | 4x Noctua NF-A8 | $57 | |