+++
author = "Dan Salmon"
date = 2018-01-16T00:40:32Z
description = "A novice's take on the new mobile-friendly currency"
draft = false
tags = ["crypto"]
slug = "first-impressions-of-electroneum"
title = "Electroneum - First Impressions"

+++

A coworker recently sent me a link to Yet Another Cryptocurrency. Like most people, I'm pretty burnt out with all these new coins and ICO's popping up each week. In fact, I get about 5-10 ICO spam emails in my Junk mail folder. 

This one looked to be just a little different, though. He was excited because this one allows you to use your phone to mine. I talked to him about how it's a neat idea, but ultimately probably won't be profitable, especially compare to a multi-GPU setup.

Still, the idea of mobile mining was intriguing. Especially if you could negate the cost of power, since it would be 100% profit at that point. 

So I started digging into this coin and here are my initial findings.


## Site
The first thing I did was download the app on an old Nexus 6P(?) I had in my parts box. Upon launch it prompts you to login to your Electroneum account. What? I can't just give it a wallet address? 



After looking at the website the answer is no: you have to create an online wallet on the my.electroneum.com website and login to the app with that. This sketched me out a bit since web wallets are inherintly less secure than an offline wallet.

I proceeded to the sign up page where I was greeted with some highly suspicous behavior....

![electroneum-hi](../images/electroneum-hi.png)

When you click the "Sign Up" button, a Javascript alert box pops up. The only explanations I can come with are:
1. Someone found a XSS vuln and left this here as a proof-of-concept
2. A dev pushed straight to production

Either way this doesn't exactly inspire confidence. 

![electroneum-alert](../images/electroneum-alert.png)
[Archive.org snapshot of the page](https://web.archive.org/web/20180106002628/https://my.electroneum.com/)

I [tweeted at them](https://twitter.com/bltjetpack/status/949436050539204609) asking if it was intentional. As of writing, I haven't received a response but a day after I tweeted them the code was gone. 

Other than that, the signup process is pretty standard save for the fact that they have a Captcha on **every. single. page.**


## Wallet
Like any coin I assumed I needed to create a wallet to hold my coins so I downloaded the "Offline Wallet Generator" which is curiously labeled as being compatible with only Chrome.

<!--(Pic of download area)-->

After unzipping the download, I found an HTML file. Wtf? Turns out the "Offline Wallet Generator" is just Javascript.

![electroneum-generate-wallet](../images/electroneum-generate-wallet.png)

The rest of the process is to simply generate some entropy to create the wallet. At the end of the process you get a PDF file as a paper wallet. 

![electroneum-wallet-pdf](../images/electroneum-wallet-pdf.png)

Truly the strangest wallet-generation process I've gone through.


## Mining

After finally completing the signup process, I logged into the app only to be greeted with this message:

![electroneum-mining-offline](../images/electroneum-mining-offline.png)

There was a Google Form to signup for the mining beta that was open until January 12th.

https://twitter.com/electroneum/status/948663752290914304

I signed up and am waiting to hear back on it. 

I wasn't able to mine on a mobile device which was a bummer, so I grabbed an old netbook I had. After about 2 hours of searching and trying in vain, it seems there does not exist a CPU miner for Electroneum (or Monero for that matter) that works with the Intel Atom x86 platform running Ubuntu. 


I plan to post a follow-up if and when I'm finally able to get mining working on my Android phones. 


**P.S.**
There's also an easter egg on the site. If you look in the *robots.txt* file you'll find there's an [/adminarea/](https://electroneum.com/adminarea/) directory. If you go there and view the source, you'll find a form that submits to a *dontdoit.php*. If you then visit this page, it just outputs your public IP address. Not sure what this is for, but may be worth poking around at.





