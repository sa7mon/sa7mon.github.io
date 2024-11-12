---
author: "Dan Salmon"
date: "2024-11-11T00:00:00Z"
description: "SMB speeds can be severely impacted when multiple interfaces can reach a network."
draft: false
tags: ["windows", "tailscale"]
slug: "windows-smb-tailscale"
title: "Windows Kills SMB Speeds When Using Tailscale"
type: "post"
---

### The Issue

Yesterday when trying to transfer an ISO file from a TrueNAS SMB share, I was getting horrible transfer speeds.

{{< figure src="slow1.png" title="" alt="Copy progress dialog window showing a speed of 355KB/s">}}

The NAS can definitely saturate a gigabit link which is what my desktop connects to the switch over. I had seen this behavior before and had a feeling it was related to having Tailscale running - the last time it happened I quit Tailscale and my fast transfers returned.

Why would this matter?

Well, in my home network I have a Proxmox VM that, among other things, runs Tailscale as a [subnet router](https://tailscale.com/kb/1019/subnets). I set it up this way so that my homelab services (especially Pi-hole) are available to my laptop and phone from outside my house.

Consequently, my desktop now has 2 interfaces that can both reach my trusted `10.10.1.1/24` network - the gigabit NIC connected to the switch and the Tailscale virtual adapter. 

A quick online search led me to a thread [on the Tailscale forums](https://forum.tailscale.com/t/windows-routes-all-smb-traffic-through-tailscale-even-when-lan-ip-is-specified-is-much-slower/1995) describing the exact behavior I was seeing.

I learned that Windows has a system for determining which interface will handle a request when multiple interfaces could be used. Just like dynamic routing protocols calculate the "cost" of each route to a remote network, Windows has a system to calculate which interface to use. Windows refers to this cost as the "Interface Metric" which is calculated based on the physical interface medium and its link speed (as documented [here](https://learn.microsoft.com/en-us/troubleshoot/windows-server/networking/automatic-metric-for-ipv4-routes) in the last table).

Since the Tailscale interface advertises a link speed of 100Gbps, Windows assigns it a much lower interface metric than the integrated gigabit NIC on my motherboard. Lower metric = lower "cost" associated.

```powershell
PS C:\> Get-NetAdapter | Where-Object {$_.Name -EQ "Tailscale" -or $_.Name -EQ "Ethernet"}

Name        InterfaceDescription     ifIndex Status   LinkSpeed
----        --------------------     ------- ------   ---------
Ethernet    Intel(R) I211 Gigabit...      10 Up          1 Gbps
Tailscale   Tailscale Tunnel               9 Up        100 Gbps

PS C:\> Get-NetIPInterface | Where-Object { ($_.InterfaceAlias -EQ "Tailscale" -or $_.InterfaceAlias -EQ "Ethernet") -and $_.AddressFamily -eq "IPv4"}

ifIndex InterfaceAlias  AddressFamily NlMtu(Bytes) InterfaceMetric Dhcp     ConnectionState PolicyStore
------- --------------  ------------- ------------ --------------- ----     --------------- -----------
10      Ethernet        IPv4                  1500              25 Enabled  Connected       ActiveStore
11      Tailscale       IPv4                  1280               5 Disabled Connected       ActiveStore
```

I only want traffic destined for my [Tailnet](https://tailscale.com/kb/1136/tailnet/) to use the Tailscale interface. To ensure Windows chooses the Ethernet interface in all other cases, we just need to increase the Interface Metric on the Tailscale interface to be higher than 25.

### The Fix

The usual way to change this is in the Control Panel, but if you try to set the Interface Metric manually, Windows won't let you because the Tailscale interface somehow has an "empty" static IP address set.

{{< figure src="manual-metric.png" title="" alt="Windows error message window showing text 'The adapter requires at least one IP address. Please enter one.'">}}

Powershell to the rescue:

```powershell
PS C:\Windows\system32> Get-NetAdapter | Where-Object -FilterScript {$_.InterfaceAlias -Eq "Tailscale"} | Set-NetIPInterface -InterfaceMetric 500
PS C:\Windows\system32> Get-NetIPInterface |Where-Object { ($_.InterfaceAlias -EQ "Tailscale" -or $_.InterfaceAlias -EQ "Ethernet") -and $_.AddressFamily -eq "IPv4"}

ifIndex InterfaceAlias AddressFamily NlMtu(Bytes) InterfaceMetric Dhcp     ConnectionState PolicyStore
------- -------------- ------------- ------------ --------------- ----     --------------- -----------
10      Ethernet       IPv4                  1500              25 Enabled  Connected       ActiveStore
9       Tailscale      IPv4                  1280             500 Disabled Connected       ActiveStore
```

After I changed this, I was back to getting near-gigabit speeds over the Ethernet interface. 

{{< figure src="fast.png" title="" alt="Copy progress dialog window showing a speed of 113MB/s">}}

### Future Steps

Switch this machine to a Linux distro and upgrade the NIC to 10G!