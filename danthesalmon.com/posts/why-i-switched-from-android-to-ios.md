+++
author = "Dan Salmon"
date = 2017-09-01T01:12:00Z
description = ""
draft = false
tags = ["security", "android"]
slug = "why-i-switched-from-android-to-ios"
title = "Why I switched from Android to iOS"

+++

*Note: This article is not just a stringent comparison of Apple vs Android, but a look at the whole picture that I took while deciding which phone to switch to.*

A few months ago, my beloved Verizon HTC One M8 began to lose it’s charge at an alarming rate. It would be at 40% battery by lunch time after a day of very moderate use. Luckily, I was due for an upgrade from Verizon. This was great, but presented an issue: I don’t keep up on the new cell phone releases. To be clear, I am aware of big releases that have new features: I knew Motorola had a new phone that had removable addons, Samsungs are all(?) waterproof, and iPhones hadn’t really added much since the fingerprint reader.

# The Search

So being the comparison shopper that I am, I started looking more closely at the offerings from the major phone manufacturers. The first constraint put on the search was that it had to be offered through the Verizon upgrade program. Being a broke college student, I wasn’t about to plop down $600-1000 for full retail. This narrowed the search down considerably, and from the upgrade-available phones I picked out 3 that seemed at the top of their class: the Google Pixel/Pixel XL, iPhone 6S, and the LG V20.

I spent a few minutes listing the features I needed and the ones that would be nice to have. Here were my (partially completed) notes:

![phones](../images/phones.png)

*Tangent: I never thought I’d have to include “Headphone jack” as a requirement, but that’s where we find ourselves.*

These were just a few of the many factors I was considering. Ultimately, it all came down to that all-important S word: Security. On a mobile device, security comes mainly in 2 forms: secure communications with others, and phone data security. I decided that security was paramount for me when looking at the requirements.

# Data at rest

On my HTC, I had Android’s full-disk encryption turned on for the phone and the SD card. Now widely-known due to Apple v FBI , iPhones [enable full-disk encryption by default](https://www.apple.com/business/docs/iOS_Security_Guide.pdf) once the user configures a passcode. Both Android and Apple feature full-disk encryption, but they go about it differently. Recently, Android’s method received a big black mark due to the fact that it stores the keys in software as opposed to hardware. [Researchers found 2 vulnerabilities](https://bits-please.blogspot.com/2016/06/extracting-qualcomms-keymaster-keys.html) in the way Qualcomm-powered Android devices handled the keys and were able to extract crypto keys. Apple handles this process differently and stores keys in a specialized piece of hardware called the Secure Enclave.

In addition to disk encryption methods, we also need to think about authentication processes. The new line of iPhones and a few Android devices now allow for fingerprint authentication. This is great for unlock speed and convenience, but there are big issues with this. Namely, that the government [can compel you to turn over your fingerprint](https://www.theatlantic.com/technology/archive/2016/05/iphone-fingerprint-search-warrant/480861/) to [unlock your phone](http://documents.latimes.com/search-warrant-glendale-iphone/) and that fingerprints cannot be rotated like passwords and keys can. I’ll be using a 6-digit passcode and 10-attempt device wipe policy thank you very much.

# Data en route

Almost since it came out, I’ve been using Signal to communicate securely with friends. The problem with Signal is that both parties need to have the app in order to talk privately. As anyone else who uses 3rd party secure messaging apps knows, it’s not fun to be that guy that keeps nagging his friends to download some other app just to talk to them. Not to mention the iPhone version of Signal is quirky to say the least and feature-lacking enough for me to not recommend it to others and not really want to use it myself. After looking into iMessage security, Apple’s system seems very secure out of the box;  the only downside being that Apple effectively acts as the keyserver in the transaction. This means that it’s technically possible (though unlikely given their track record) that Apple could advertise someone else’s public key (think 3-letter organization) as though it belonged to me. With Signal, you can verify keys with other users by physically scanning a QR code.

Although Signal is technically very secure and fundamentally I would trust an open-source project that has received high praise from the security community over a proprietary program that doesn’t allow me to verify keys, the problem of adoption still persists. What good is a the most secure chat protocol if I can only use it to talk to 4-6 of my contacts? Ultimately, I chose the iMessage path as it allowed the majority of my chats to be relatively secure as opposed to a few of them secured VERY well, but most of them not secure at all.

# Operating System Security

Thanks to the splintered ecosystem that is carrier-branded Android, my HTC One M8 has around 4 CVEs affecting it right now and there’s not a thing I can do about it. If Verizon doesn’t deem my phone current enough to push out an update, I simply will never get an update. I know I could flash a third-party OS on it, but this is my daily driver and I don’t want to worry about bricking it.

In stark contrast, the iOS model ensures that I will get Apple-direct updates the minute they’re available. Not to mention, Apple seems pretty good about continuing software support for older devices. As of this writing, the[ oldest supported device that can run the current iOS 10 is the iPhone 5](http://iossupportmatrix.com/) released in September 2012. Most likely, I will not have this phone for longer than 5 years, so that’s more than enough support time for me.

# Epilogue

In the end, I chose the iPhone 6S. Hopefully this article will give you some food for thought if you’re thinking about switching. It got a bit longer than I was planning, but now I finally have something to point people to when they say “What, you switched to an iPhone??”

Also, if you haven’t yet read it [go read Apple’s iOS security whitepaper](https://www.apple.com/business/docs/iOS_Security_Guide.pdf). I link to it several times here, but it’s worth noting outright.

