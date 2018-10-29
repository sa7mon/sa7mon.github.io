+++
author = "Dan Salmon"
date = 2018-10-28T15:39:00Z
description = "A condensed version of the 2018 Cyber Security Summit in Minneapolis."
draft = false
tags = ["security", "conferences"]
slug = "cyber-security-summit-2018"
title = "Cyber Security Summit 2018 Highlights"
type = "post"
+++

I was fortunate enough to attend the 2018 Cyber Security Summit in Minneapolis, MN. Here I've tried to summarize each talk I attended into a small, digestible format, mostly written in their voices. 


## Student Breakfast
**By:** Ryan Aniol - State of Minnesota

> This was a super informative informal breakfast hosted by Ryan who aimed the talk at the students like myself. It was really nice to hear information directly relevant to my situation.
<br />

**1. Benefits of an Information Security position**
<br />

**Job security**

InfoSec is a huge industry and is growing in both the public and private sector. Historically, the public sector has been trailing in this regard but in the past 5 years we've seen a trend in public sector positions growing. Lots of jobs now. 

* Specializations - One of the benefits of the Information Security world is that you can specify in one of many different areas. Things like Computer Forensics, Red Team, Blue Team, DevOps, and many other specializations have positions available. Additionally, many non-technical roles need filling like Risk & Compliance. There simply aren't enough people to fill all these jobs currently.

* Salary - The Information Security industry, in general, pays extremely well. Though the public sector doesn't pay as well as the private there has been about a 30-40% increase in pay over the past 5 years.

* Always Something New - The adversaries that we're dealing with are no longer script kiddies (though those certainly still exist) 

* Criminal Psychology - With the release and discovery of every new piece of advance malware, we need to remember one thing: each one was created by a human somewhere. Keeping this in mind, we should try to understand the mindset of the enemy. Generally, the enemy's goals these days tend to be much more financially motivated. Though simple DDOS attacks for the kicks still exist, they are not so prevalent. 

**2. Tips to Prepare Students**

* School - "School is an important part of the process. You're all in school currently and that's perfect."

* News - Stay up-to-date with the latest InfoSec news. "I recommend a good RSS reader with a mixture of sources."

* Hands on at home - Spend time at home doing projects. GitHub is full of projects and tools to try out. Go fire up some virtual machines and try them out. 

* Network - The InfoSec community is small-ish. You'll see a lot of the same people at different conferences so go talk to them and start making connections.

* Stress Management - InfoSec jobs are high stress jobs. Recently, BlackHat had 4 different talks at the conference about substance abuse and depression among people with InfoSec jobs. To help alleviate some of your anxiety, remember that there will always be bad guys and you can't stop every single one of them. Remember also to rely on your team because it's not just you out there. 

**3. Q&A**

* "Is there an advantage to starting in the public sector and then moving to the private sector?"
    * "I would say so. In the public sector, you're presented with more different challenges - more than just protecting IP and credit card numbers. Stuff like voter data and critical infrastructure. "

* "Are there more entry level positions in the public sector or private?"
    * "No one more so than the other. There's so many jobs it really doesn't matter."

* "Are there any certifications you'd recommend that students get?"
    * "CISSP or CEH. Any cert in your specialization area, too."



## The Most Impactful IP Theft in History -- What You Need to Know About Protecting Trade Secrets

**By:** Brian Levine - US Department of Justice 

> Brian was a very good storyteller and I was so engaged in listening, I didn't take many notes. The story of this investigation is all available online.

In this talk, Brian Levine from the Department of Justice talked about the investigation and prosecution of a Chinese wind turbine company named Sinovel. 

https://www.justice.gov/opa/pr/sinovel-corporation-and-three-individuals-charged-wisconsin-theft-amsc-trade-secrets

Brian also wanted to raise awareness of the various resources available on [cybercrime.gov](cybercrime.gov). There you can find documents outlining best practices for incident response as well as a framework for vulnerability disclosure programs. 


## National Policy Conversation 

**By:** Matthew Rhoades - Cybersecurity & Technology Program at The Aspen Institute

> Matthew presented a very quick talk about national policy concerning cybersecurity. Here are his predictions for the near future.

In the near future lawmakers will:

* Push even harder for a kind of government-aiding "backdoored" encryption likely as a direct result of the Apple v FBI case. 
* Require voter and political platform protections in the wake of the discovery that Russian influence may have altered the course of the latest US presidential election.


## How you prioritize threats and intelligence 
**By:** Eric Dull - Deloitte & Touche LLP

> Eric is a self-described stats nerd. He gave a great talk about gaining visibility into your network and how crucial it is.

Downtime is guaranteed to be very costly for your organization. To prepare yourself for this, you should always assume you're going to be breached and that you'll need to react to it. 


Since you won't be able to protect all data all the time, you'll need to prioritize data security based on risk value. Be sure to protect sensitive data, especially that which may come under other protections such as HIPAA or FERPA over other less important things that management may have tried to prioritize such as the CEO's emails. 

In order to identify and prioritize your data, you need to have a good grasp of your network.

**1. Map Your Network**

Here's the best way to map your network:

* Determine the activities that are key to your business
* Map these functions to specific servers and networks
* Use data whenever possible
* Automate and repeat

Questions you should be able to answer when you're done:

1. How many devices are on your network?
2. How many DHCP addresses are in use?
3. How many servers are on the network and which protocols are they using?
4. What operating systems are present?
5. What's externally visible?

You need to have visibility to see what's going on.

**2. Threat Intel**

There are hundreds of public sources, but most threat intel just points out the known worst parts of the internet. Groups like IARPA are doing work trying to predict attacks and generate intel. 

Not all threats matter to you because not all threats will affect your business. 

**3. Plan To Act**

What happens after the initial attack? You could unplug the whole network like Maersk did during the NotPetya infection. 

Have something ready in addition to simple remediation. Network should be heavily segmented to reduce pivoting. You could also initiate service degradation after hours. Turning off non-production services after hours can also help reduce your attack vector. 


## Securing Microservices 
**By:** Mike Gillespie - Amazon Web Services

> Mike gave a really cool technical talk for those who use AWS products. AWS has a whole slew of tools built-in to it's cloud offerings to help customers safeguard their data and protect against vulnerabilities

**Monoliths** - To start splitting these apart 10 years ago we started creating XML + SOAP API's, 5 years ago we moved everything to REST, and now we have "Microservices". Anything we can do split the application into smaller, bite-sized pieces. 

In the development lifecycle, the people working on it usually fall into the following roles:

* Dev Team - "Make it faster"
* Ops Team - "Make it stable"
* Sec Team - "Make it secure"


**AWS**

With AWS, security is a shared responsibility. Where this responsibility is split depends on the product used, but generally:

* The customer is responsible for security *in* the cloud. (i.e. customer data, instance firewall configs)
* AWS is responsible for security *of* the cloud. (i.e. Edge networking, storage, database, availability)

**Securing VPC's**

* Use an Instance-Level Firewall (called "Security Groups" in AWS)
* Use subnet network rules (called NACLs in AWS)

Make sure you use tools with automation. You don't want to be the bottleneck in your department so just script everything.


**Use host-based agents**

* Amazon Inspector will scan for known vulnerabilities
* AWS Simple Server Manager
* Or use 3rd party agents that provide:
    * Antivirus
    * Data Loss Prevention

Again, automate all of this!

**API Gateway**

This acts as a "front door" to your microservices. It usually sits in front of:

* Authentication
* Rate Throttling
* Monitoring
* Versioning

Make sure to select and API Gateway that supports automation.

**WAF - Web Application Firewall**

This provides Layer 7 protection and protects against the OWASP Top 10. On AWS there are 3rd party solutions.


**Best Practices for Protecting Containers**

* Use read-only file systems
* Sign your container images
* Run vulnerability analysis on the containers in your Continuous Integration pipeline
* Run containers as a non-root user


## Detection Techniques

**By:** Tim Crothers - VP of Cyber Security at Target

Slides: [GitHub](https://github.com/Soinull/Strong_Detection)

> Tim had a very interesting and engaging presentation with a really cool honeypot-style strategy that's easy enough to employ in any AD environment.

Most people would say that a "breach" occurs as soon as a phishing link is clicked. I say even if a phishing link is clicked and a RAT is installed, this is known as a "prevention failure" with the *potential* of a breach. A breach only occurs when the attackers accomplish their goals.

The "dwell time", which is the time an attacker stays on a victim network, is less than a few days and with ransomware is even shorter.

Most antivirus solutions are still signature-based which is a problem. Dave Kennedy's tool shows that it's trivial to bypass this. The tool generates malware, submits it to an antivirus, then keeps tweaking it and checking the detection rate until it determines exactly what in the malware is triggering the antivirus. Then the author can simply change this part and become completely undetected. 


**Honeypot Technique**

I will assert that there's no legitimate reason for a user to dump credentials from the cache of a system. Using this rule, we can use a technique to catch attackers when they touch a system. 

Using a management tool such as SCCM or even PSExec on a smaller network, we are going to cache fake credentials in the registry and memory of random desktops across the network. After caching these creds, we have make those accounts actually exist in Active Directory, but make sure to make the password >50 characters long and generated randomly. This way, AD will generate a failed login attempt. 

Then, we will create alerting with Windows Event Viewer to alert on any instance of Event ID: 4771 which is Kerberos Pre-Auth Failure. Another Event ID will give you the host name of the event where the attacker tried to get in.

## Cybersecurity: How Much Is Enough?

**By:** Tony Sager - The Center for Internet Security

> Tony is a self-described old graybeard who has been around for a while. The theme for this talk was "security has become mainstream and that's a good thing."

The answer to "How Much Cybersecurity is enough?" is always "more". 

Risk = (vulnerability + threat + consequences) / countermeasures

Lifetime of lessons learned

* We aren't special and the bad guys don't do magic
* Knowing about vulnerabilities doesn't get them fixed
* Have to prioritize defensive choices, 80/20, most defense comes from the first few choices you make
* People don't make security decisions, they make business decisions
* Cyber Security = Information Management !== threat sharing. When you hear "share" you should interpret that as "translate + execute"
* Cybersecurity is more like Groundhog Day than Independence Day. It's not nearly as exciting as fighting aliens and most of the time you're repeating the same tasks. 


## Mimecast Email Talk

**By:** Brandon Reid - Mimecast 

> I liked this talk a lot because it was fairly technical. The conference advertised that there would be a live phishing demo which didn't happen. That was a little dissapointing, but the talk was still very good.

Cybersecurity is a defense arms race.

Email attacks are effective. 90% of attacks start with a phish - it's the most common attack vector currently used. 

Originally, phishing emails were just Nigerian 419 scams. Surprisingly, these still exist.

You're at risk of phishing if:

* Your domain name contains certain easily-mistaken letters
* Your management team is highlighted on your website
* You accept resumes on your website

Even for savvy users, phishing is still possible due to:

* Unicode / Punycode
* URL elongation on mobile devices

What are some ways to fight this?

* Signature-based only pick up low-hanging fruit that has already been seen in the wild
* Static File Analysis caught Petya before it was known. Takes ~1-2 seconds to process
* If attachments have scripts, strip them and convert to PDF

Attacks don't need malware

* Email Impersonation - Starts with a financial ask and has a sense of urgency. Looks like it's coming from CEO or other board member
* Supply Chain Impersonation - Attacker will find someone the target does business with and impersonate them


## Bruce Schneier: "Click Here to Kill Everybody"

**By:** Bruce Schneier - Legendary Cryptographer, [Schneier on Security](https://www.schneier.com/)

> This was essentially the only reason I came to the conference on the second day. Bruce is an amazing speaker and I was so happy to get to hear him speak. He was promoting his newest book "Click Here to Kill Everybody" which IBM sponsored by giving away free copies of and which Bruce signed.  

Everything is a computer now. 

1. Most software is poorly written. It's the old adage of good, fast, or cheap - pick any 2
2. The Internet wasn't designed with security in mind
3. Extensibility of computers mean they can't be constrained
4. Complexity of systems mean defense is harder than offense
5. New vulnerabilities arise as we add more inter-connections
6. Attacks will always get better, faster, and easier

With automation comes new dangers. New ideas of smart cities could have much more serious real-world failures than just simple data breaches. 

Consumer electronics are not easily updateable. That's how we get Mirai.

We don't yet have thing->thing authentication figured out. Just person->thing.

Supply chain risks - What software can you trust? Hardware implants?

This is a policy issue. Law and tech can subvert teach other, but defense needs to prevail.


What kind of regulatory structure do we need? The market can't solve this. They're going to get involved anyway. Some are already: California passed an IoT bill, New York is regulating crypto, Massachusetts is working on consumer protections.

Regulation doesn't stifle innovation, it forces manufacturers to make it cheaper while staying within the new laws. Rising tide effect benefits people in other parts of the world when regulated somewhere else (i.e. GDPR).

Technologists need to get involved in politics. Lawmakers look really bad when they're not technical (i.e. Facebook Senate hearing). 

**Q & A**

* Do you think standards will help drive innovation?
    * Yes, companies won't improve their security without being forced to.
* Do policymakers need technical knowledge?
    * Would be great but probably never going to happen. Staff and advisors seem to work elsewhere in the world. That would be the most likely. 
* How can we get more people in cyber?
    * Through the normal ways: better education, better skills training. AI will be the real wildcard since we have no clue what jobs it will be replacing. 
* How is PKI going to change with increased computer speed?
    * I don't think it'll change, but I hope we have new protocols and tweaks to make it better.
* What will happen with the advent of quantum computing?
    * Don't worry about it. Grover's algorithm says that quantum will double the length of breakable keys. So just double the length of keys. Seriously, we also haven't built a working quantum computer, but we probably will in the next decade. It also took 30 years to understand von Neumann computing. It's also possible that quantum kills PKI crypto. 
* What if the government body you're proposing fails?
    * So what? Unrestrained companies are worse than a failed government entity. 









