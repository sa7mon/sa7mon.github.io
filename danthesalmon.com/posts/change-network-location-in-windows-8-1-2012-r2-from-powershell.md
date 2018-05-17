+++
author = "Dan Salmon"
date = 2017-08-26T20:46:25Z
description = ""
draft = false
tags = ["windows"]
slug = "change-network-location-in-windows-8-1-2012-r2-from-powershell"
title = "Change Network Location in Windows 8.1/2012 R2 from Powershell"

+++

# Step 1
From an elevated PowerShell window issue the following command to gather some information about our current network profile:

<pre class="command-line language-powershell" data-output="2-8" data-prompt="C:\>">
<code class="language-powershell">Get-NetConnectionProfile

Name             : Unidentified Network
InterfaceAlias   : Ethernet
InterfaceIndex   : 10
NetworkCategory  : Public
IPv4Connectivity : LocalNetwork
IPv6Connectivity : LocalNetwork</code>
</pre>

The only information we really need from this output is the “InterfaceIndex”

# Step 2
After getting the InterfaceIndex, we run the following command:

<pre class="command-line language-powershell" data-output="2-8" data-prompt="C:\>">
<code class="language-powershell">Set-NetConnectionProfile -InterfaceIndex 10 -NetworkCategory Private</code>
</pre>
The last argument is what category you want to switch it to. The only allowed values here are “Public” or “Private” as per the [MSDN documentation](https://technet.microsoft.com/en-us/library/jj899565.aspx). The category can also be “DomainAuthenticated” but Windows will automatically assign this when the machine is added to a domain.



