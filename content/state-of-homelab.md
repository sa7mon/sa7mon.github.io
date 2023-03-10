+++
author = "Dan Salmon"
date = 2023-03-09T00:00:00Z
description = ""
draft = true
tags = ["homelab", "docker"]
slug = "state-of-homelab-2023"
title = "State of the Homelab: 2023"
type = "post"
+++

# State of the Homelab: 2023

I've run a homelab for a number of years. In the beginning, it was to learn more about Linux and running servers 

Recently, my life priorities have changed and I find myself less willing to tinker with things when they're not acting right. I'd much rather switch/upgrade/downsize to alternatives, if possible, that Just Work. 

To that end, the main network router was up until recently a PC Engines APU2 running pfSense with a few services such as WireGuard and Suricata. For the most part things ran great until they didn't and I was not able to fix them in a timely fashion. 

Also, it turns out Ubiquiti gear does not work well when mixed with non-Ubiquiti gear. This led to some very strange network bootstrapping issues relating to the Unifi Controller running in a Raspberry Pi and the pfSense box.

## Another Section

Here is a mostly complete picture of the homelab as it stands today

(Diagram)

and here's what it looks like in real life

(rack)

## FreeNAS

My homelab started with this machine. I happen to know exactly when it started because I recently decommissioned (finally) my first pool:

```
root@freenas:~ # zpool history -l vol1 | head -n 2
History for 'vol1':
2015-05-28.09:23:18 zpool create -o cachefile=/data/zfs/zpool.cache -o failmode=continue -o autoexpand=on -O compression=lz4 -O aclmode=passthrough -O aclinherit=passthrough -f -m /vol1 -o altroot=/mnt vol1 mirror /dev/gptid/156c1e1b-0545-11e5-bca1-74d435ed2d77 /dev/gptid/15c53447-0545-11e5-bca1-74d435ed2d77 [user 0 (root) on freenas.local]
```

I have added and retired other pools since then, but that was the original. All 4 drives had around 60,000 power-on hours according to SMART (!).

For a while I ran a handful of services in the jails. This was FreeNAS 9.3+ so we only had warden (not iocage) back then. This worked okay for a while until I ran into CPU limitations, I mean this is a dual-core Pentium after all.

I decided it was time to break apart the "storage" and "compute" duties which leads me to my next topic.

## Proxmox

For compute duties, I purchased a used HP Z620 from eBay along with a pair of Mellanox Connect-X3 10GbE cards and 4x cheap 1TB SSDs from MicroCenter. I knew I was going to use Docker to run my services, so after getting Proxmox installed and creating a ZFS pool, I ran a bunch of benchmarks to determine the best way to run Docker workloads on Proxmox. I've detailed that process [here](https://danthesalmon.com/running-docker-on-proxmox/), but the outcome is that I decided to run a vanilla Debian VM with Docker installed in it which gives a good mix of performance and ease of maintainance.

Once I had the Mellanox cards installed in both machines, I could setup SMB shared in FreeNAS that Proxmox will backup to. I store both LXC container and VM backups this way as well as storing a collection of ISOs so we don't use up precious SSD space on the Proxmox machine.

### Containers

**Jellyfin**

I was a long-time Plex user but stopped using it a number of years back after I felt that I just couldn't figure out how/when/why it was transcoding. Not to mention the hardware I was running it on was severly underpowered. Recently I came across Jellyfin and decided to spin it up. It was simple to install and point at my media share. Jellyfin lacks some of the cool features that Plex has, but it certainly has enough for me. There's a recent 'Plex vs Jellyfin' comparison video [here](https://www.youtube.com/watch?v=jKF5GtBIxpM) that goes more in-depth on this.

All I know is that the server runs great and the native Apple TV app [SwiftFin](https://apps.apple.com/ca/app/swiftfin/id1604098728), which finally just got pushed from TestFlight to the App Store, fulfills all my needs. It is fairly unpolished at this point, as acknowledged by the dev team, but the community is large and improvements are being made all the time.

Since the Z620 came with an Nvidia Quadro (model?) that I wasn't using for anything, I spent some time fiddling with PCI device passthrough in Proxmox so Jellyfin would have direct access to the GPU for transcoding.

Jellyfin has a great page in their documentation [here](https://jellyfin.org/docs/general/clients/codec-support/) that lays out audio, video, and subtitle codec support for each client. This makes it very easy to determine when transcoding will happen and can help guide you on which codecs you should target when acquiring your media.

**DNS**

This container is running Pihole - a service I've had on my network for a long time. Previously, I was running this in the Docker VM, but I wanted to more easily point Tailscale DNS at it so I moved pihole to an LXC container and installed Tailscale in it.


### VMs

**haosova**

This is a Home Assistant server. I don't have much for home automation at this point - just a 'smart' thermostat and a garage door sensor. Both use Zigbee for communications and talk to this VM via a [Conbee II](https://www.phoscon.de/en/conbee2) passed-thru to the VM. This setup has been rock-solid for me for the past year and it's been very nice having the power to create complicated thermostat schedules. I also turn the heat/AC down if I leave for the weekend and then adjust it back up from my phone a few hours before getting home.

*Future improvement*: I attempted but was unsuccessful in creating an automation which would look at the weather forecast and suggest opening the windows in the house, if appropriate, instead of relying on the heat or AC.

**docker-vm**

This VM runs the majority of my "homelab" services:

- [CUPS](https://openprinting.github.io/cups/)
	- A print server that allows me to share my HP Laserjet 4200 with all the devices on my network. Crucially, this also includes my iOS devices via AirPrint. It is incredicly convenient to print things directly from my phone or iPad and without CUPS there would be no way to accomplish this with my ancient printer.
- [miniflux](https://miniflux.app/)
	- I use Miniflux for simple RSS feed consumption. Truthfully, I haven't compared it to any other options out there, but so far it has all the features I need. My favorite feature is the "Save" button which sends the current article to Wallabag. The integration was dead-simple to setup.
	- Most of the feeds I consume are personal blogs, but the bulk of the articles come from the Hacker News Frontpage and Ars Technica feeds. 
- [wallabag](https://www.wallabag.it/en)
	- Wallabag is somewhere between a bookmark manager and a feed reader. You can think of it like a self-hosted, simpler [Pocket](https://getpocket.com/en/). The links I save here are RSS articles I found interesting and tabs I've had open in Firefox for too long but don't want to lose.
	- The killer feature for me is each item's "Origin URL". This is where you found the resource - for me that's usually a Hacker News comment or Twitter thread. It's great to have a link to the discussion about a given article so I can remember the context around why I found something interesting. 
- [firefly iii](https://github.com/firefly-iii/firefly-iii)
	- I use Firefly III to "balance" the checking account my partner and I share. The app is a little cumbersome to use with redundant and sometimes unnecessary features (the creator has said as much), but it's the best self-hosted finance manager I've found so far. Plus, if I really felt that strongly about my complaints, Firefly has an API so I could write a simpler frontend that ignores those features I don't care about.
	- My biggest gripe is that importing transactions is somewhat manual - having to download a CSV from my bank, then click through a 5-6 step wizard each time. In the past, I [hacked together](https://github.com/sa7mon/firefly-gone-plaid) a tool to query my bank using Plaid's API and send those transactions to Firefly. These days, there's a separate "Firefly Importer" app that I run in another container. If I find myself with some free time, I plan to add Plaid as a provider (even though it means contributing to a PHP project).
- [netbox](https://github.com/netbox-community/netbox)
	- `netbox` is an asset inventory app that I use to keep track of what network devices and their IP addresses. I made extensive use of DHCP reservations in the router so that all end devices can be simply set to DHCP but always receive the same internal IP address. netbox has way more features than I could ever need and sometimes that can make adding new devices a bit tedious, but the benefit I get in keeping things straight makes it worth it.
	- The search box at the top is the killer feature. For a device I'm trying to track down the IP of, I can plug in (partial or full):
		- manufacturer
		- model
		- "friendly" name
		- interface name
	- It also helps me track VLANs and subnets so I keep logical segmentation.
	(TODO: screenshot of http://10.10.1.175:8000/ipam/prefixes/)
- nginx-proxy-manager
	- With this handful of services all running from the same VM which exposes their ports, I didn't want to access them via `docker.vm.ip.address:port`. That's what DNS is for. Pihole contains the DNS entries and this container, `nginx-proxy-manager`, is a reverse proxy listening on a single port. Depending on the internal domain name requested, it will proxy the traffic between the user and that service.
	- This setup has become invaluable as I can now, for example, access miniflux in the browser by just going to `miniflux.home.lan`. In the `nginx-proxy-manager` config, I simply reference the containers by name and specify which port to connect to.

## Future

- Rack Proxmox somehow
	- HP rails kit
	- Re-home guts into 3-4U case
	- Finally move to a 2U pre-built server and transfer storage
- Rack FreeNAS box
	- Hard to find small-ish disk shelves. Noise/power are a concern
- Move printer to quarantine VLAN that only CUPS can access
- Rack-mounted KVM for direct access