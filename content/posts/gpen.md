---
author: "Dan Salmon"
type: "post"
date: "2026-10-23T12:30:16-05:00"
title: "Passing the GPEN"
description: "I passed the GPEN and you can too"
tags: ["certifications"]
---

This week I took and passed the GPEN exam. Here are some thoughts about how the class and exam went.

I took the [SEC560](https://www.sans.org/cyber-security-courses/enterprise-penetration-testing) class the last week in June. I opted for the Live Online format as I have found when taking previous courses that this is a good balance of cost (my employer wouldn't fly me somewhere to take a class in-person) and having a live instructor available for questions in real time. I don't think I have the discipline and self-motivation needed to consume a completely self-paced class, so this was a good option. The class itself was great - this was my first time taking a class delivered by Jeff and I thought he did an awesome job. I enjoyed the tangents about real-world examples or side projects he's working on.

The key difference in how I took this class compared to previous SANS courses is that this time I tried to make my index during class as we covered topics. In [my GWAPT post](/posts/gwapt-certification-review/), I explained the SANS index study method; I planned to do the same with this cert. The only difference this time was I would try to complete as much of the index as possible during class instead of waiting until the class ended to start the index from scratch.

This worked out pretty well and I found that I was able to pay much closer attention during the lectures since I was actively engaged in summarizing and writing things down in my spreadsheet. I will definitely be employing this "build the index during the class" method in future SANS courses to help keep me engaged. The class (like most SANS classes) is six 8-hour days of instruction which is a huge amount of time to be receiving information.

At the end of the class, I had a pretty large index created, but it was rough. I had done my best to keep up with the pace of slides as Jeff went through them, but there were lots of items missing definitions and page numbers from when I had to switch my focus back to the slides or we had moved on.

After the class ended, I started the process of going back through all the material and honing my index. I re-reviewed every page of all 8 books and made sure I had all the key terms with concise definitions.

I had set a rule for myself that I would only study during work hours as work was paying for me to get the certification and I did not want this to interfere with my personal time in any way. Work not interfering with personal life is very important to me. This method worked, but also enabled me to procrastinate a bit as most work weeks are very busy and I could easily put off the 1-2 hour study blocks with a "too much to do today" excuse. This is why I'm writing a post about passing a certification in October after taking the class in June. In hindsight, I should have set a deadline to take the test.

Since the class focuses a lot of the labs on Active Directory attacks, they require connection to the SANS VPN to reach the cyber range. Students only get access to this for the duration of the class which means I wasn't able to go back and redo those labs. Jeff had recommended [Game of Active Directory](https://github.com/Orange-Cyberdefense/GOAD) as a study resource so I worked on getting that running in my homelab. Unfortunately, GOAD requires a lot of resources (mainly RAM) to run several Windows Server VMs and not wanting to rob a bank to afford another 64GB, I decided to wait until after I had taken the first practice test to see if I needed the additional practice.

I also briefly began building an attack workflow diagram similar to the [OCD Mindmaps](https://orange-cyberdefense.github.io/ocd-mindmaps/) as a way to keep track of which hashes can be used for what type of attacks, but this was very time-consuming and I decided it was probably not strictly needed to pass the exam.

I took my first practice test on October 6 using v1 of my index. I take the practice tests at home following the same rules that are applied during the real exam: no phone, browser full-screened, nothing else on my desk but the class books, my index, and a blank piece of paper to write down terms that my index was still missing. I got a 91% on the practice test, which was quite a confidence boost. After updating my index, I took my next practice test the following week and received a 96% score. I scheduled my test for the following week at a local testing center.

During the real exam, there weren't really any surprises. I wish there had been more room on the desk for the books - I had a side table to use too, but I ended up spending a lot of time shuffling books around. At the end of 2.5 hours, the test abruptly ended and informed me I had passed with a score of 97%.

This class was my first foray into network pentesting in an Active Directory environment, understanding Kerberos, and using Sliver for a C2. I really enjoyed the AD exploitation portion and it has motivated me to get GOAD set up in my lab to keep learning the Windows side of things and probably go for the OSCP in the future.
