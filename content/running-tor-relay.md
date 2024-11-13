# Running a Tor relay (fairly) painlessly

My first experience with running a Tor relay was around 2015. I had an HP SFF PC in my bedroom running Ubuntu and decided to spin up a Tor relay to contribute to the network. I did it, but within a week Hulu mysteriously stopped working from my network. A conversation with my confused roommate later, he showed me the message he was getting on the website. I cannot recall what the exact wording was, but it had something to do with accessing the site from a "known malicious" or "poor reputation" IP address. I feigned ignorance then promptly shutdown the relay and unplugged our modem for 30 minutes to cycle the public IP address.

Looking back now, I realize my mistake was running a relay from my home network. For all I know, I was inadvertantly running an exit node instead of a middle relay, but either way the IP address of the node gets published publicly unless you are running a bridge (I think).

At the time, I had no experience with or knowledge of cloud services. I also had no money to use them as a poor college student.

This is no longer the case and cloud compute instances have only gotten cheaper since then.

----

After deciding I wanted to run a Tor relay again, I began by digging into the [Relay Operations](https://community.torproject.org/relay/) documentation. There is a wealth of information there to help guide the perspective relay operator. The main decision to make is where - geographically and in which cloud provider's datacenters - one should stand up a relay. 

Ideally the network of relays would have a high diversity level of:

- AS/location
- operating system

Oh and you should try to provide as much monthly bandwidth as possible.

**Choosing a Hosting Provider**

Regarding provider diversity, the docs say the following:

> When selecting your hosting provider, consider network diversity on an autonomous system (AS) and country level. A more diverse network is more resilient to attacks and outages. Sometimes it is not clear which AS you are buying from in case of resellers. To be sure, ask the host about the AS number before ordering a server.
>
> It is best to avoid hosts where many Tor relays are already hosted, but it is still better to add one there than to run no relay at all.
>
> Try to avoid the following hosters:
>    OVH SAS (AS16276)
>    Online S.a.s. (AS12876)
>    Hetzner Online GmbH (AS24940)
>    DigitalOcean, LLC (AS14061)

The list of hosters to avoid makes sense - those are some of the cheapest, easiest to use cloud providers around. Many volunteers have already spun up relays there and it would be dangerous to have a majority of relays in a small number of datacenters: an attacker who could gain access to one of these data centers would be in control of a large portion of the Tor network.

Using the metrics site, a perspective relay operator can see a table of the most popular [AS](https://metrics.torproject.org/rs.html#aggregate/as) and [Countries](	) for existing relays. Ideally, you would choose an AS and country very far down the list, but in reality it may be tricky to find a cloud hosting provider with a datacenter in, say, [Mauritius](https://en.wikipedia.org/wiki/Mauritius).

Another consideration to make is that not all cloud providers look kindly at people using their networks for Tor activity. Running an exit node is a great way to get tangled up in legal issues and many network operators don't want the hassle. Again, the Tor docs have you covered - the [Good Bad ISPs](https://community.torproject.org/relay/community-resources/good-bad-isps/) page lists IPSs by country that either have explicit policies or a track record of being friendly to Tor.

Finding a good combination of diverse provider and cheap bandwidth can take some time. In the end, I landed on [DataWagon](https://datawagon.com/). Currently, there are only 13 other relays in their AS [(AS27176)](https://metrics.torproject.org/rs.html#search/as:AS27176) along with mine. The virtual server I pay $4/month for has more than the minimum specs for running a relay:

- x2 vCPUs
- 1 GB DDR4 RAM
- 30GB NVMe Storage

The only downside to this server is it is limited ot 5TB of bandwidth each month. I wasn't sure what would happen if I exceeded this - maybe extra charges so I opened a support ticket. Their response was

> After 5TB bandwidth usage, your speed will be limited to 1Mb/s. 

which I guess seems reasonable. At least I won't get hit with a surprise bill at the end of the month!

**Choosing an OS**

Now that I had my hosting provider picked out, it was time to decide what OS I would run. The Tor docs mention a diversity of OS among the relays would be nice:

> We recommend using the operating system you are most familiar with, but if you're able, the network would most benefit from BSD and other non-Linux based relays. Most relays currently run on Debian.

As of writing, the distribution is skewed quite heavily toward Linux systems

<<<<<<<< screenshot here >>>>>>>>>>>>

I decided that NixOS would be an okay compromise here. Sure, it's Linux but at least it's not Debian right? The other more selfish reason I chose NixOS was simply because I want to get more experience with it - it really does feel like the future even if there are a lot of sharp edges in need of smoothing. I will likely spin up another relay on FreeBSD in the future.

DataWagon has a pretty slick management panel and I was able to easily upload a NixOS ISO, boot it, and install it to disk with a basic config I copied from my previous [experimentations](https://github.com/sa7mon/nixos).

## Setting Up

Similar to my previous dealings with NixOS configuration, this part took some time. I started by creating a `torrc` file according to the Debian [setup steps](https://community.torproject.org/relay/setup/guard/debian-ubuntu/) and then searched in the [NixOS Options](https://search.nixos.org/options?channel=23.11&from=0&size=50&sort=relevance&type=packages&query=services.tor.settings) site for the relevant options names to translate to.

This initially seemed silly: create a `torrc` file, then port it to a `.nix` file so that NixOS could generate the `torrc` file in an immuatable way. I guess this is just something you have to get over when joining the world of Declarative Systems.

This was pretty easy going until I tried to change the `ORPort` setting - from what I understand, this is the port users will connect to your relay on. I don't think changing this is *as important* as it would be if I were running an exit node, but I still figured it was a good idea to not use the default port. The default ports of Tor nodes are well-known by security vendors so it's probably best to switch to a port number less likely to be blocked in restrictive networks - like 80 or 443.


Once the server was running, I could monitor the Tor daemon with Nyx - a very handy utility to show network usage and tor logss in the terminal.

## Running

The server ran with no problem and the network activity followed pretty closely the phases documented in [The lifecycle of a new relay](https://blog.torproject.org/lifecycle-of-a-new-relay/). The first few weeks I checked in almost every day to see if my little relay had earned any new flags. 

<<<<<<<<<<<screenshot here of current flags or all possible with descriptions >>>>>>>>>>>>>>>>>>>>>>>>>


After a few weeks it became clear that I was going to hit my 5TB limit way before the end of the month. I set the AccountingMax and AccountingStart options appropriately, but didn't actually know what would happen once the limit was hit.

The Tor docs list the [tor-relays](https://lists.torproject.org/cgi-bin/mailman/listinfo/tor-relays/) mailing list as a place to get help running a relay, so I subscribed to the list and asked there what would happen. (Side note: mailing lists are a very confusing communication platform for someone that grew up chatting in online forums)

I was surprised and thankful to receive a reply from none other than [Roger Dingledine](https://en.m.wikipedia.org/wiki/Roger_Dingledine) answering my question - the relay will simply "sleep" the rest of the month after it hits the bandwidth limit.

<<< check emails for more details to add >>>

After it had been running like this for a few months, I started wondering if I should tweak the bandwidth options. Is is better for the overall network to have a slow relay which is online 24/7 or to have a relay which is very fast, but is only available for half of the month?

I put this question to the mailing list and got a few ideas back. I could limit the speed (`BandwidthBurst` and `BandwidthRate`) to ensure my 5TB wouldn't run out until the end of the month. The other suggestion was much simpler and I felt silly for not considering it: ask my VPS provider for more monthly data. The response I got was that it would cost an additional $3/month for 10TB of data transferred - this seemed reasonable as the total monthly charge for this box would be $7.

On Jan 3rd I paid the invoice for extra bandwidth and turned off the limiting options in the Tor config.

http://172.81.131.87/
https://metrics.torproject.org/rs.html#details/1776B82878E571E3969A6A7F7EA140A8853F11DB
https://blog.torproject.org/lifecycle-of-a-new-relay/
https://www.reddit.com/r/NixOS/comments/16wtsbp/how_do_i_supply_submodule_options_in/

https://cloud.co.za/virtual-servers/
https://truehost.cloud/cloud-servers/
https://www.gphosting.co.za/linux-cloud-hosting

www.wedos.com/vps-ssd/
\