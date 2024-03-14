# Windows kills SMB Speeds when using Tailscale

Yesterday when trying to transfer an ISO file from a TrueNAS SMB share, I was getting horrible transfer speeds.

(image here)

The NAS can definitely saturate a gigabit link which is what my desktop connects to the switch over. I had seen this behavior before and had a feeling it was related to me having Tailscale running. The last time it happened I had quit Tailscale and my fast transfers returned.

In my home network, I have a Proxmox VM that, among other things, runs Tailscale as a [subnet router](https://tailscale.com/kb/1019/subnets). I set it up this way so that my homelab services, especially Pi-hole, are available to my laptop and phone from outside my house.

Consequently, my desktop now has 2 interfaces that both can reach my trusted 10.10.1.1/24 network - the gigabit NIC connected to the switch and the Tailscale virtual adapter. 

A quick online search led me to a thread [on the Tailscale forums](https://forum.tailscale.com/t/windows-routes-all-smb-traffic-through-tailscale-even-when-lan-ip-is-specified-is-much-slower/1995) describing the exact behavior I was seeing.

I learned that Windows has a system for determining which interface will handle a request when multiple interfaces can be used. Just like dynamic routing protocols calculate "costs" of each route to a remote network, Windows has a system to calculate which interface to use. Windows refers to this cost as the interface's "metric" and by default it will be calculated automatically based on the physical interface medium and its link speed (as documented [here](https://learn.microsoft.com/en-us/troubleshoot/windows-server/networking/automatic-metric-for-ipv4-routes)).

Since both interfaces are interpreted by Windows as standard gigabit adapters (even though one is actually virtual), they both have the 


```powershell
PS C:\Windows\system32> Get-NetIPInterface |Where-Object { ($_.InterfaceAlias -EQ "Tailscale" -or $_.InterfaceAlias -EQ "Ethernet") -and $_.AddressFamily -eq "IPv4"}

ifIndex InterfaceAlias                  AddressFamily NlMtu(Bytes) InterfaceMetric Dhcp     ConnectionState PolicyStore
------- --------------                  ------------- ------------ --------------- ----     --------------- -----------
10      Ethernet                        IPv4                  1500              25 Enabled  Connected       ActiveStore
11      Tailscale                       IPv4                  1280               5 Disabled Connected       ActiveStore
```

- can't manually set interface metric without an IPv4 configuration

Get-NetAdapter | Where-Object -FilterScript {$_.InterfaceAlias -Eq "Tailscale"} | Set-NetIPInterface -InterfaceMetric 500
