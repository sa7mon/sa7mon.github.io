+++
author = "Dan Salmon"
date = 2017-12-22T01:12:14Z
description = ""
draft = true
slug = "hardening-nginx"
title = "Hardening Nginx"

+++

* Check out: https://github.com/orangejulius/https-on-nginx/blob/master/ssl.conf

 In this article I'm going to show I greatly increased my Nginx security and how easy it can be. Throughout this I'll link to Scott Helme's blog and if you want to learn more you should check it out. He goes into great detail.

# Security Headers (.io)

Beginning scan yielded a grade of *F*. That's not great. Let's go through the list "Missing Headers" and start adding lines to our `nginx.conf`. After each major change, restart nginx and re-test.

* **X-Xss-Protection**
*tldr;* Tell the browser to use it's built-in XSS protection. 
[Better explanation](https://scotthelme.co.uk/hardening-your-http-response-headers/#x-xss-protection)

   <code class="language-nginx">add_header X-Xss-Protection "1; mode=block" always;</code>
* **X-Content-Type-Options**
*tldr;* Tell the browser to trust the server-declared MIME type of files
 [Better explanation](https://scotthelme.co.uk/hardening-your-http-response-headers/#x-content-type-options) 

   <code class="language-nginx">add_header X-Content-Type-Options "nosniff" always;</code>
* **X-Frame-Options** 
*tldr;* Tell the browser which sites can set the source of an iframe to your site 
[Better explanation](https://scotthelme.co.uk/hardening-your-http-response-headers/#x-frame-options)

   <code class="language-nginx">add_header X-Frame-Options "SAMEORIGIN" always;</code>

Testing again at this point gives us a grade of C. We're getting there!

* **HTTP Strict Transport Security (HSTS)**
*tldr;* Tell the browser to always load the site over HTTPS.
[Better explanation](https://scotthelme.co.uk/hardening-your-http-response-headers/#strict-transport-security)

   <code class="language-nginx">add_header Strict-Transport-Security "max-age=31536000; includeSubdomains" always;</code>

Testing now gives us a B. We're almost there!

* **Content-Security-Policy (CSP)** - 
*tldr;* Tell the browser which sources are valid to run content from
[Better explanation](https://scotthelme.co.uk/content-security-policy-an-introduction/)  

  This one is going to take a bit more work than just copy-pasting. Again, [Scott Helme's site](https://scotthelme.co.uk/csp-cheat-sheet/#building-a-policy) is a great resource for building a secure policy that works for your site.
  
  I started out with the following: 
<code class="language-nginx">add_header Content-Security-Policy: default-src 'self' 'unsafe-inline' https://disqus.com https://*.disqus.com https://*.disquscdn.com; upgrade-insecure-requests; block-all-mixed-content; reflected-xss block; referrer origin-when-cross-origin;</code>

  but found that certain parts of my site were broken. I had fogotten to whitelist a few CDN's hosting some Javascript and CSS assets. Luckily, this is easy to debug by just opening the console in Chrome's Dev Tools and checking what requests are being blocked. 
  
  This is the process I'd suggest following when building your own CSP. Begin by being too strict and breaking functionality and then ease it up until everything works again. If don't like whitelisting too many sources, you may want to consider hosting your assets from your site instead of linking to external sources.

Got to A but capped there because CSP contained 'unsafe-inline'. Got rid of it, but it broke Disqus. Will have to investigate further.

----
# SSLlabs

Got a B straight out of the gate just using a LE cert and the following lines:
   ` ssl_protocols TLSv1 TLSv1.1 TLSv1.2; # Dropping SSLv3, ref: POODLE`
   `ssl_prefer_server_ciphers on;`
* Edited the protocols line a while ago when POODLE came out to mitigate that attack.
* Edited the protocols down to TLVv1.2 and pasted in the ciphers from: https://wiki.mozilla.org/Security/Server_Side_TLS#Modern_compatibility

* DNS CAA - Doesn't exist
    * Dreamhost doesn't support CAA records. Pointed domain's nameservers at DO's instead 
    * Record generator: https://sslmate.com/caa/
    * Got this record back, just needed to manually paste into DO panel:
    `danthesalmon.com.	CAA	0 issue "letsencrypt.org"`


**Certificate Authority Authorization (CAA)**
[info](https://blog.qualys.com/ssllabs/2017/03/13/caa-mandated-by-cabrowser-forum)
* Dreamhost doesn't seem to allow CAA records. Tweeted them about it. Topic on their forums about it: [here](https://discussion.dreamhost.com/t/allow-caa-records-in-dhs-dns/64170/9?u=sandwich)
* To generate a policy using a pre-existing cert: [here](https://sslmate.com/caa/) and hit "Auto-Generate Policy"
* Monitor Certificate transparency Logs and get alerts if a cert is generated for your domain: [here](https://sslmate.com/account/certspotter)

**Ciphers/Stapling/Diffie-Hellman-Cache**
After generating the 2048 bit DH params with openssl, pasted in the lines from [here](https://github.com/orangejulius/https-on-nginx/blob/master/ssl.conf). Got A+ from SSLlabs!


* Disable Nginx sending version information:
    `server_tokens off;`
    * Verified via securityheaders.io

-----

# IPv6 Support 
* Just followed [their guide](https://www.digitalocean.com/community/tutorials/how-to-enable-ipv6-for-digitalocean-droplets) 
* Used [this site](http://ready.chair6.net/?url=danthesalmon.com) to check

12/19/17
* Powered off the droplet since it needed a reboot anyway from something. Clicked "Enable IPv6" since it's free with DO. 
* Turned it back on, pasted 6 lines into `/etc/network/interfaces` and put my public ip and gateway in. 
* Rebooted and verified I could ping Google's IPv6 address. Will still need to add an AAAA record 
* Yesterday configured the interface to use new IPv6 address. Ran a check and it's failing because we have no AAAA record.
* Added an AAAA record in DO super easily and waited 15-20 minutes for DNS to propogate 
* Overall was super easy. Whole process probably took 10 minutes to get site IPv6-compliant. 