---
author: "Dan Salmon"
date: 2023-05-19T00:00:00Z
description: "Here's what I'm running in my homelab right now"
draft: false
tags: ["homelab", "docker"]
slug: "state-of-homelab-2023"
title: "State of the Homelab: 2023"
type: "post"
---

I started my homelab almost 10 years ago. At that time my goal was to learn more about Linux, FreeBSD, and virtualization tools as well as having an "always-on" machine to do long-running tasks. This post is a snapshot of what the network looks like today as well as a little history behind the different parts.

## Obligatory Diagram

Here is a mostly complete picture of the homelab as it stands today

![diagram](./diagram_border20.svg)

and here's what it looks like in meatspace

{{< figure src="rack.jpg" title="" width="75%" link="rack.jpg" attr="sorry, I know it's a bad picture">}}

It may be useful to refer back to the diagram while reading the rest of this post.

## Network

Recently, my life priorities have changed and I find myself less willing to tinker with things when they're not acting right. When possible, I'd much rather switch/upgrade/downsize to alternatives that Just Work even if said alternative is more expensive. This is especially true when it comes to my home network.

To that end, the main network router was, up until recently, an [apu2](https://pcengines.ch/apu2.htm) running pfSense with a few services such as WireGuard and Suricata[^suricata]. For the most part things ran great until they didn't and I was not able to fix them in a timely fashion. 

For switching, I use a [USW-Pro-24](https://store.ui.com/collections/unifi-network-switching/products/usw-pro-24) and I learned that mixing Ubiquiti and non-Ubiquiti gear do not really play well . This led to some very strange network bootstrapping issues relating to the Unifi Controller which was running in a Raspberry Pi connected to the pfSense router.

After replacing the pfSense with a [UDM Pro](https://store.ui.com/collections/unifi-network-unifi-os-consoles/products/udm-pro) everything started working in perfect harmony and network administration became simple, even when doing more complicated configurations. 

The single [UAP-AC-Pro](https://store.ui.com/products/uap-ac-pro) provides wireless coverage to 90% of my house. In the future I plan to add another one if I can find a good way to route ethernet through my ceiling.

The source is lost to me so I can't attribute it but someone once referred to Ubiquiti as the "Apple of networking" and I have certainly found this to certainly be the case:

- looks nice
- very user-friendly
- not as customizable as more "power user"-friendly alternatives
- In general, it Just Works

Overall I am very pleased with my little Unifi stack.

[^suricata]: I do not recommend running Suricata on a home network. Apps, websites, and streaming services will break in strange and unpredictable ways that do not immediately point to Suricata being the culprit. Maybe you can tune the rules properly, but I gave up.

## TrueNAS

This was the first "homelab" machine I built years ago. I happen to know the exact day I built it because I recently decommissioned my first zpool:

```
root@freenas:~# zpool history -l vol1 | head -n 2
History for 'vol1':
2015-05-28.09:23:18 zpool create -o cachefile=/data/zfs/zpool.cache -o failmode=continue -o autoexpand=on -O compression=lz4 -O aclmode=passthrough -O aclinherit=passthrough -f -m /vol1 -o altroot=/mnt vol1 mirror /dev/gptid/156c1e1b-0545-11e5-bca1-74d435ed2d77 /dev/gptid/15c53447-0545-11e5-bca1-74d435ed2d77 [user 0 (root) on freenas.local]
```

I have added and retired other pools since then, but that was the original one. All 4 drives had around 60,000(!) power-on hours at time of decommission according to SMART.

When I first built the machine, I ran a handful of services in jails, managed by `warden`. This was FreeNAS 9.3+ (later renamed TrueNAS Core) which did not have `iocage` yet for jail management. The jails worked okay for a while until I ran into CPU limitations - this is a dual-core Pentium after all. Docker was starting to really take off at the time and I didn't want to keep porting Dockerfiles to bash scripts so I decided it was finally time to learn Docker and split the "storage" and "compute" duties.

## Proxmox

For the compute node, I'm using HP Z620 I bought used from eBay that I then installed a Mellanox Connect-X3 10GbE card and 4x cheap 1TB SSDs in. I knew I was going to use Docker to run my services, so after getting Proxmox installed and creating a ZFS pool, I ran a bunch of benchmarks to determine the best way to run Docker workloads on Proxmox. I've detailed that process [here](https://danthesalmon.com/running-docker-on-proxmox/), but the outcome is that I decided to run a Debian VM with Docker running inside it. This gives a good mix of performance and maintainability via Proxmox's VM snapshot and backup features.

The FreeNAS box also sports a Mellanox 10G card through which Proxmox can access a handful of SMB shares. I store both LXC container and VM backups on the SMB shares as well as a collection of (actual) Linux ISOs so I don't use up precious SSD space on the Proxmox machine.

### Jellyfin (LXC)

I was a long-time Plex user but stopped using it a few years back after I felt that I just couldn't figure out how/when/why it was transcoding (the underpowered hardware I was using certainly didn't help things either). I recently came across Jellyfin and decided to spin it up. While it does lack some of the cooler features Plex offers, it was simple to install and point at my media share. There's a good 'Plex vs Jellyfin' comparison video [here](https://www.youtube.com/watch?v=jKF5GtBIxpM) that goes more in-depth on this, but Jellyfin fulfills my needs (including a new native Apple TV [app](https://apps.apple.com/ca/app/swiftfin/id1604098728)).


Jellyfin has a great page in their [documentation](https://jellyfin.org/docs/general/clients/codec-support/) that lays out audio, video, and subtitle codec support for each client. This makes it very easy to determine when transcoding will happen and can help guide you on which codecs you should target when acquiring and transcoding your media.

### Pihole (LXC)

This container is running Pihole - a service I've had on my network for a long time. Previously, I was running this in the Docker VM, but I wanted to more easily point TailScale DNS at it so I moved Pihole to an LXC container and installed the TailScale client. Tailscale is configured as a "subnet router" so I can access any of my homelab services from any other device I have Tailscale on. This is super handy and means I can get Pihole ad-blocking on my iPhone regardless of what network I'm connected to.

### haosova (VM)

This is a Home Assistant server. I don't have much for home automation right now - just a 'smart' thermostat and a garage door sensor. Both use Zigbee for communications and talk to this VM via a [Conbee II](https://www.phoscon.de/en/conbee2) passed through to the VM. This setup has been rock solid for me for the past year and it's been very nice having the power to create complicated thermostat schedules. I also turn the heat/AC down if I leave for the weekend and then adjust it back up from my phone a few hours before getting home.

**Future improvements**

I attempted but was unsuccessful in creating an automation which would look at the weather forecast and suggest opening the windows in the house, if appropriate, instead of relying on the heat or AC.

### docker-vm (VM)

This VM runs the majority of my "homelab" services. The `docker-compose` scripts for each service can be found [here](https://github.com/sa7mon/homelab). Here is a quick rundown on the core services.

## Services

### [CUPS](https://openprinting.github.io/cups/)
	
{{< figure src="cups.png" title="" width="75%" link="cups.png" attr="CUPS printer list">}}

CUPS is a print server that allows me to share my HP Laserjet 4200 with all the devices on my network. Crucially, this also includes my iOS devices via AirPrint. It is super convenient to print things directly from my phone or iPad and without CUPS there would be no way to accomplish this with my ancient printer.

### [miniflux](https://miniflux.app/)

{{< figure src="miniflux.png" title="" width="75%" link="miniflux.png" attr="miniflux feed list">}}

I use Miniflux for simple RSS feed consumption. Truthfully, I haven't compared it to any other feed readers, but so far it has all the features I need. My favorite feature is the "Save" button which sends the current article to Wallabag and is dead-simple to setup.
	
Most of the feeds I consume are personal blogs, but the bulk of the articles come from the Hacker News frontpage and Ars Technica feeds.
	
### [wallabag](https://www.wallabag.it/en)

{{< figure src="wallabag.png" title="" width="80%" link="wallabag.png" attr="wallabag home page">}}
	
Wallabag is somewhere between a bookmark manager and a feed reader. You can think of it like a self-hosted, simpler [Pocket](https://getpocket.com/en/). The links I save here are RSS articles I found interesting and tabs I've had open in Firefox for too long but don't want to lose.
	
The killer feature for me is each item's "Origin URL". This is where you found the resource - for me that's usually a Hacker News comment or Twitter thread. It's great to have a link to the discussion about a given article so I can remember the context around why I found something interesting. 

### [firefly iii](https://github.com/firefly-iii/firefly-iii)

{{< figure src="firefly-iii.png" title="" width="90%" link="firefly-iii.png" attr="image credit: https://demo.firefly-iii.org">}}

I use Firefly III to "balance" the checking account my partner and I share. The app is a little cumbersome to use with redundant and sometimes unnecessary features (the creator has said as much), but it's the best self-hosted finance manager I've found so far. Plus, if I really felt that strongly about my complaints, Firefly has an API so I could write a simpler frontend that ignores those features I don't care about.
	
My biggest gripe is that importing transactions is somewhat manual - having to download a CSV from my bank, then click through a 5-6 step wizard each time. In the past, I [hacked together](https://github.com/sa7mon/firefly-gone-plaid) a tool to query my bank using Plaid's API and send those transactions to Firefly. These days, there's a separate "Firefly Importer" app that I run in another container. If I find myself with some free time, I plan to make a PR to Plaid as a provider even though it means contributing to a PHP project.

### [netbox](https://github.com/netbox-community/netbox)

{{< figure src="netbox.png" title="" link="netbox.png" attr="netbox prefix list">}}

`netbox` is an asset inventory app that I use to keep track of network devices and what their IP addresses are. I make extensive use of DHCP reservations in the router so that all end devices can be simply set to DHCP but always receive the same internal IP. netbox has way more features than I could ever need and sometimes that can make adding new devices a bit tedious, but the benefit I get in keeping things straight makes it worth it.
	
The search box at the top is the killer feature. For a device I'm trying to track down the IP of, I can plug in (partial or full):

- manufacturer
- model
- "friendly" name
- interface name

### [Nginx Proxy Manager](https://nginxproxymanager.com/)
	
{{< figure src="nginx-proxy-manager.png" title="" link="nginx-proxy-manager.png" attr="DNS name -> container mappings">}}

With this handful of services all running from the same VM, I don't want to have to remember what port each service is running on: that's what DNS is for. Pihole contains the DNS entries and this container, `nginx-proxy-manager`, is a reverse proxy listening on a single port. Depending on the internal domain name requested, it will proxy the traffic between the user and that container.
	
This setup has become invaluable as I can now, for example, access miniflux in the browser by just going to `miniflux.home.lan`. In the `nginx-proxy-manager` config, I simply reference the containers by name and specify which port to connect to. I run each service via `docker-compose` and the beauty of using `nginx-proxy-manager` is that I don't even need to map any ports in the compose file.

## Future

- Rack Proxmox somehow
	- HP rails kit
	- Re-home guts into 3-4U case
	- Finally move to a 2U pre-built server and transfer storage
- Rack FreeNAS box
	- Hard to find small-ish disk shelves. Noise/power are a concern
- Move printer to quarantine VLAN that only CUPS can access
- Rack-mounted KVM for direct access