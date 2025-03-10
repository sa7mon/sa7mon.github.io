---
author: "Dan Salmon"
date: "2025-01-11T00:00:00Z"
summary: "Time to put up or shut up"
draft: false
tags: []
slug: "2025-projects"
title: "2025 Projects"
type: "post"
---

I'm not one to create New Years resolutions, but I do like the idea of settings goals for myself for the year. In general, I'm going to try to write more about all the projects I take on whether or not they pan out.

To help with this and to give myself some pressure to follow through here is a short list of projects I've either planned, started work on, or am still formulating the idea for.

### S3Scanner Documentation

[S3Scanner](https://github.com/sa7mon/S3Scanner) now has almost all the features I have planned to add to it. There are a handful of bugs to squash and new providers to add, but the big change I want to make is to [overhaul](https://github.com/sa7mon/S3Scanner/issues/264) the documentation. 

The README is too long and there are a lot of implementation differences between the S3 providers that I have noticed that should be documented somewhere. The format will likely be markdown, maybe with a generate like [Docusaurus](https://docusaurus.io/) or [MkDocs](https://www.mkdocs.org/). The documentation should live "next to" the source code.

### Radio K Bot

[Radio K](https://radiok.org/) is a college radio station broadcasting from the University of Minnesota. I have discovered a ton of great music from the station, but tuning an FM radio to it presents a few issues:
- the radio tower does not use broadcast very far - I cannot receive it in my home office with a cheap FM antenna
- as with all terrestrial radio, there are ads that I would like to skip

To remedy this, I envision a simple Go app to parse the [playlist](https://radiok.org/playlist) once per day and update an Apple Music playlist. 

### Pager Sniffing with HackRF

The HackRF (and cheaper RTL-SDR dongles) can tune to certain frequencies used by pagers. Amazingly, this tech is still used today and most messages are broadcast large distances without any encryption. I've managed to receive and decode messages in the past with a different combination of hardware and location, but I'd like to document the process for myself and see if I can setup a longer-term collection system to analyze messages.

### 38C3 Favorite Talks

The Chaos Communication Congress is one of my favorite information security conferences every year. I have a list of talks that sound really interesting and I will create a list of my favorites after having watched them.

### Running Tor Relays (and bridges)

I've been running a handful of Tor relays and bridges for the past few months. I'd like to document my experience setting these up and maintaining them, as well as my impressions of the Tor community. 

### Off-site TrueNAS Backup

As documented in my homelab [post](/state-of-homelab-2023/), I have a TrueNAS system that houses a lot of data. Most of it is not important, but some of it I would like to backup. I plan to document how I built a small form factor DIY NAS to use for off-site backups and the setup process for getting the remote machines to "see" each other without opening any firewall ports.

### Desktop Linux Migration

The only machine in my house running Windows is my desktop and I would like to change that for a myriad of reasons. I'll document my requirements, Linux distro selection, and results.


### Open Source Burp Alternative

This is likely a longer-term project than what I could do in a few months, but I'm beginning to play around with some Go libraries that should make it easy to build desktop apps. I have little to no experience with desktop apps as most of my development is either with web APIs or command-line tools. To help with my learning experience, I will build an app for pentesters to use when testing web apps. It will combine features of Burp, Caido, and include some useful features I have not seen other tools implement.

<br />
<br />
<br />
Time to go do