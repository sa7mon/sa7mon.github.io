## 2025 Projects

I'm not one to create New Years resolutions, but I do like the idea of settings some goals for myself for the year. In general, I'm going to try to write more about all the projects I take on whether or not they pan out.

So to help with that and to give myself some pressure to follow-through, here is a short list of projects I've either planned, started work on, or am collecting my thoughts on.

### S3Scanner documentation

[S3Scanner](https://github.com/sa7mon/S3Scanner) now has almost all the features I have planned to add to it. There are a handful of bugs to squash and new providers to add, but the big change I want to make is to [overhaul](https://github.com/sa7mon/S3Scanner/issues/264) the documentation. The README is too long and there are a lot of implementation differences between the S3 providers that I have noticed that should be stored somewhere. The format will likely be Markdown, we'll see if something like Docusaurus or Mkdocs will be needed but either way the documentation should live "next to" the source code.

### Radio K Bot

[Radio K](https://radiok.org/) is a college radio station broadcasting from the University of Minnesota. I have discovered a ton of great music from the station, but tuning into the FM frequency presents a few issues:
- the radio tower does not use broadcast very far. I cannot receive it in my home office with a cheap FM antenna
- as with all terrestrial radio, there are ads that I would like to skip

To remedy this, I envision a simple Go app to parse the [playlist](https://radiok.org/playlist) once per day and update an Apple Music playlist. 

### Pager Sniffing with HackRF

The HackRF (and cheaper RTL-SDR dongles) can tune to certain frequencies used by pagers. Amazingly, this tech is still used today and most messages are broadcast very far without any encryption. This is something I've gotten working in the past with a different combination of hardware and location, but I'd like to document the process for myself and see if I can setup a longer-term collection system to analyze messages.

### 38C3 Favorite Talks

The Chaos Communication Congress is one of my favorite information security conferences every year. I have a list of talks that sound really interesting and I will create a list of the best ones, in my opinion.

### Running Tor Relays (and bridges)

I've been running a handful of Tor relays and bridges for the past few months. I'd like to document my experience setting these up and maintaining them as well as my impressions of the Tor community. 

### Off-site TrueNAS Backup

As documented in my homelab post, I have a TrueNAS system that houses a lot of data. Most of it is not important, but some of it I would like to backup. I plan to document how I built a small form factor DIY NAS to use for off-site backups and the setup process for getting the remote machines to communicate without opening any ports in my firewall.

### Desktop Linux Migration

The only machine in my house running Windows is my desktop and I would like to change that for a myriad of reasons. I'll document my requirements, Linux distro selection, and results.

