---
author: "Dan Salmon"
date: "2025-07-02T00:00:00Z"
summary: "A great time was had"
draft: false
tags: ["conferences"]
slug: "secretcon-2025-highlights"
title: "SecretCon 2025 Highlights"
type: "post"
---

As I alluded to in my [previous post](i-am-speaking-at-secretcon-2025/), I presented at SecretCon 2025 this past week. This was my first time attending after having heard about it for the first time a few months ago - I gather this is the 3rd year it has been held. Here are some highlights, in no particular order, from my day and a half there!

**Badge**

The badge did not disappoint - after turning it on, you can use the two buttons on the left and right of the screen used to cycle through the 20ish built-in animations. A few of them reveal the fact that there is a gyroscope on the board as they move when you tilt the badge.

{{< figure src="badge.png" height="600px" caption="" alt="Picture of an owl-shaped blue PCB with a screen near the mouth area.">}}

I later learned that one of the challenges of the CTF (that I didn't know was happening) was to open a serial console via the USB-C port, discover a text-based adventure game, and ascend to a certain level.

**Hardware Hacking Village**

At the hardware hacking village, a representative from GhostScale was running a soldering workshop. You would receive an ESP32, breakout board, and SD card reader which you could solder together. Then, you could flash the board with [ESP32Marauder](https://github.com/justcallmekoko/ESP32Marauder) which can do Wifi and Bluetooth attacks. I definitely need to play with this board more as it also has space for additional modules to be added.

{{< figure src="soldering-station.png" height="600px" caption="" alt="4 people are seated at a long table, all concentrating on soldering.">}}
{{< figure src="esp32.png" height="600px" caption="" alt="Close-up of PCB with ESP32 soldered to it.">}}

**Aerospace Village**

I didn't grab a picture of it, but at this village there was a model airplane connected to a laptop which was running some sort of simulator. I believe the goal of the station was to issue commands to the onboard computer to see if you could override the safety controls.
I ended up talking to the representative Lillian for a good while about everything GNSS, GPS, ADB, and airplane electronics systems design. It was truly fascinating.

**Tin Foil Hat Competition**

I spent a solid hour here. They had a mannequin head set up on the table with a HackRF antenna embedded inside. The goal of the contest was to design a hat made from aluminum foil that would block out the most signals. More signals blocked = more points. After struggling with this for a while and using maybe 10 feet of foil, I managed to hit #2 on the scoreboard. After that, no matter what I added to the hat my score never improved. I walked away after a team walked up with a hat shaped not unlike a dunce cap and grabbed first place. I learned that I do not understand how radio waves work and the fact that my cell phone works at all is plain magic.

{{< figure src="tinfoil1.png" height="600px" caption="" alt="A mannequin head with a tin foil hat covering the top of the head and ears down to the neck">}}
{{< figure src="tinfoil2.png" height="600px" caption="Classic hats only used tin foil, 'hybrid' could use a mix of materials." alt="A tv screen lists the rankings for classic hats or hybrid hats and each contestants attenuation (dB)">}}


**Lockpicking Village**

This was a fun table to hang out at for a while. I enjoy picking locks, but I am not good at it - this became evident when I was only able to pick my way through 2 of the 6 challenge locks. I would have loved to have learned more about tubular locks, but none were available.

{{< figure src="lockpick.png" height="600px" caption="" alt="Close up on a table covered in lock picks, tension wrenches, and in the foreground a yellow plastic rectangle with 6 circular lock faces embedded.">}}

**Talks**

The talks I attended were great - especially the Friday keynote with Gabe. I have been subscribed to his channel [saveitforparts](https://www.youtube.com/@saveitforparts) for quite a while so it was cool seeing his talk *Intercepting Satellite Data with Trash* in person. It really got me excited to explore the world of SDR more which coincidentally is how I found his channel in the first place.

The *Securing the Skies: Safety and Security in
Aviation and Why It Matters* talk by Lillian from the Aerospace Village was also fascinating. I learned a ton about the safety certifications of equipment that goes into planes and how *security* testing plays a major role in *safety* testing.

The closing keynote was a super fun story that there just wasn't enough time to explore deeply: *Closing Keynote: We Infiltrated a North Korean Laptop Farm, Here's How We Did It*. DPRK remote worker schemes are endlessly fascinating to me and just this week, the DOJ [announced](https://www.bleepingcomputer.com/news/security/us-disrupts-north-korean-it-worker-laptop-farm-scheme-in-16-states/) the takedown of a major network of laptop farms in the US. I look forward to seeing if he gives this talk at another conference so I can hear the full story.

<br />
<br />
Overall I had a great time and I hope I can scrape together a talk to give next year!