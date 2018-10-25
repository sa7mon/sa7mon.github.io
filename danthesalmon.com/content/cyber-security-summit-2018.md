+++
author = "Dan Salmon"
date = 2018-10-23T15:39:00Z
description = "A condensed version of the 2018 Cyber Security Summit in Minneapolis."
draft = true
tags = ["security"]
slug = "cyber-security-summit-2018"
title = "Cyber Security Summit 2018 Wrap-Up"
type = "post"
+++


I was fortunate enough to attend the 2018 Cyber Scurity Summit in Minneapolis, MN. Here I've tried to summarize each talk I attended into a small, digestable format written in their voice. 

*Note:* Each section is written in the voice of the speaker except for any notes from me which will be denoted as such.


## Student Breakfast
By: Ryan Aniol, State of Minnesota

*Dan:* This was a super informative informal breakfast hosted by Ryan who aimed the talk at the students, like myself. It was really nice to hear information directly relevant to my situation.

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

* Stress Management - InfoSec jobs are high stress jobs. Recently, BlackHat had 4 different talks at the conference about substance abuse and depression among people with InfoSec jobs. To help aleviate some of your anxiety, remember that there will always be bad guys and you can't stop every single one of them. Remember also to rely on your team because it's not just you out there. 

**3. Q&A**

* "Is there an advantage to starting in the public sector and then moving to the private sector?"
    * "I would say so. In the public sector, you're presented with more different challenges - more than just protecting IP and credit card numbers. Stuff like voter data and critical infrastructure. "

* "Are there more entry level positions in the public sector or private?"
    * "No one moreso than the other. There's so many jobs it really doesn't matter."

* "Are there any certifications you'd recommend that students get?"
    * "CISSP or CEH. Any cert in your specialization area, too."

## The Most Impactful IP Theft in History -- What You Need to Know About Protecting Trade Secrets

In this talk, Brian Levine from the Department of Justice talked about the investigation and prosecution of a Chinese wind turbine company named Sinovel. 

https://www.justice.gov/opa/pr/sinovel-corporation-and-three-individuals-charged-wisconsin-theft-amsc-trade-secrets

Brian also wanted to raise awareness of the various resources available on [cybercrime.gov](cybercrime.gov). There you can find documents outling best practices for incident response as well as a framework for vulnerability disclosure programs. 


## National Policy Conversation 

By: Matthew Rhoades, Cybersecurity & Technology Program at The Aspen Institute

Summary:

In this talk, Matthew talked about his work at The Aspen Institute and laid out his predictions for topics that lawmakers will likely start pushing legistlation for. These things included:

* Pushing even harder for a kind of government-aiding "backdoored" encryption. This will likely be a response to the latest legal battles the government has waged against tech companies as seen in the Apple v FBI case. 
* Voter and political platform protections in the wake of the discovery that Russian influence may have altered the course of the latest US presidential election.


## How you prioritize threats and intelligence 
By: Eric Dull, Deloitte & Touche LLP

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
By: Mike Gillespie - Amazon Web Services

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

By: Tim Crothers - Target


















