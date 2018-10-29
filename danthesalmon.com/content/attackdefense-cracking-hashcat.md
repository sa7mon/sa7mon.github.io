+++
author = "Dan Salmon"
date = 2018-10-29T20:46:25Z
description = ""
draft = false
tags = ["ctf"]
slug = "attackdefense-cracking-hashcat"
title = "AttackDefense - Cracking Hashcat Guide"
type = "post"
+++

![attack-defense-grey](../images/attack-defense-grey.png)

AttackDefense is a new site with all sorts of awesome labs an CTFs for training. Best of all: the site is free. 

In this guide, I'll detail how to complete the ["Cracking MD5 Hashes"](attackdefense.com/challengedetails?cid=51) lab, though this strategy should work for most labs in the "Hashcat All" section. If you are unfamiliar with Hashcat, I'd strongly recommend reading [the wiki](https://hashcat.net/wiki/) or following Youtube tutorials elsewhere before trying this challenge.

<br>

*Note: I would highly suggest you try to complete the lab first without help from this guide.*

<br />

### First attempt: Use the provided word list

Checking hashcat help or [this page on the wiki](https://hashcat.net/wiki/doku.php?id=example_hashes), we can find that mode "0" is the mode for MD5, so let's first try just a straight wordlist attack with the supplied file. 

<pre>
<code class="markdown">student@attackdefense:~$ hashcat -m 0 digest.txt 1000000-password-seclists.txt
hashcat (v4.2.1) starting...

OpenCL Platform #1: Intel(R) Corporation
========================================
* Device #1: Intel(R) Xeon(R) Gold 6148 CPU @ 2.40GHz, 24169/96677 MB allocatable, 20MCU

Hashes: 1 digests; 1 unique digests, 1 unique salts
Bitmaps: 16 bits, 65536 entries, 0x0000ffff mask, 262144 bytes, 5/13 rotates
Rules: 1

Minimum password length supported by kernel: 0
Maximum password length supported by kernel: 256

Dictionary cache built:
* Filename..: 1000000-password-seclists.txt
* Passwords.: 1000003
* Bytes.....: 8529147
* Keyspace..: 1000003
* Runtime...: 0 secs

Approaching final keyspace - workload adjusted.

Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:09:26 2018 (1 sec)
Time.Estimated...: Thu Oct 18 18:09:27 2018 (0 secs)
Guess.Base.......: File (1000000-password-seclists.txt)
Guess.Queue......: 1/1 (100.00%)
Speed.Dev.#1.....:  1007.2 kH/s (14.39ms) @ Accel:1024 Loops:1 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 1000003/1000003 (100.00%)
Rejected.........: 0/1000003 (0.00%)
Restore.Point....: 1000003/1000003 (100.00%)
Candidates.#1....: vrs555 -> vjht008
HWMon.Dev.#1.....: N/A

Started: Thu Oct 18 18:09:07 2018
Stopped: Thu Oct 18 18:09:28 2018
</code>
</pre>

*Verbose Hashcat warnings omitted in this article for brevity*

Of course that would be too easy.

From the challenge description we can see that the password must comply with the following rules:

1. Length must be between 0 and 6, inclusive. 
2. Password can only contain characters from the following sets: a-z, 0-9

These rules really limit the total permutations of potential passwords, so we can move onto trying a Bruteforce Attack using masks. Normally this isn't a great idea because Bruteforce Attacks can be very time-consuming, but the total combinations of characters allowed is around 4.5 million and this is an MD5 hash which is very easy for the CPU to compute. 


To perform our Bruteforce Attack, we must supply a mask. `hashcat --help` will list all available charsets near the bottom of the output.

<pre>
<code class="markdown">- [ Built-in Charsets ] -

  ? | Charset
 ===+=========
  l | abcdefghijklmnopqrstuvwxyz
  u | ABCDEFGHIJKLMNOPQRSTUVWXYZ
  d | 0123456789
  h | 0123456789abcdef
  H | 0123456789ABCDEF
  s |  !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~
  a | ?l?u?d?s
  b | 0x00 - 0xff
</code>
</pre>

Charset "h" fits our needs exactly, so let's try it. 

<pre>
    <code class="markdown">
student@attackdefense:~$ hashcat -m 0 -a 3 ./digest.txt ?h?h?h?h?h?h

hashcat (v4.2.1) starting...

OpenCL Platform #1: Intel(R) Corporation
========================================
* Device #1: Intel(R) Xeon(R) Gold 6148 CPU @ 2.40GHz, 24169/96677 MB allocatable, 20MCU

Hashes: 1 digests; 1 unique digests, 1 unique salts
Bitmaps: 16 bits, 65536 entries, 0x0000ffff mask, 262144 bytes, 5/13 rotates

Approaching final keyspace - workload adjusted.

Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:34:59 2018 (1 sec)
Time.Estimated...: Thu Oct 18 18:35:00 2018 (0 secs)
Guess.Mask.......: ?h?h?h?h?h?h [6]
Guess.Queue......: 1/1 (100.00%)
Speed.Dev.#1.....: 12905.7 kH/s (56.17ms) @ Accel:1024 Loops:64 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 16777216/16777216 (100.00%)
Rejected.........: 0/16777216 (0.00%)
Restore.Point....: 65536/65536 (100.00%)
Candidates.#1....: 1aace5 -> 6ef7f6
HWMon.Dev.#1.....: N/A

Started: Thu Oct 18 18:34:57 2018
Stopped: Thu Oct 18 18:35:01 2018
    </code>
</pre>


Hmm. Well, that didn't work. Most likely, this is because we supplied a single 6-character mask when the password length requirement specified it could be **up to** 6 charcters. Specifically, hashcat tried all permutations between `000000` and `zzzzzz`. This means the password must be less than 6 characters long. Thankfully, Hashcat has a simple parameter to fix this: `--increment`. This parameter forces Hashcat to run through all increments of our supplied mask, starting at length 1 and up to the length of the supplied mask:

<pre>
<code class="markdown">?h
?h?h
?h?h?h
?h?h?h?h
?h?h?h?h?h
?h?h?h?h?h?h
</code>
</pre>

This will greatly expand our keyspace.


<pre>
<code class="markdown">student@attackdefense:~$ hashcat -m 0 -a 3 --increment ./digest.txt ?h?h?h?h?h?h
hashcat (v4.2.1) starting...

OpenCL Platform #1: Intel(R) Corporation
========================================
* Device #1: Intel(R) Xeon(R) Gold 6148 CPU @ 2.40GHz, 24169/96677 MB allocatable, 20MCU

Hashes: 1 digests; 1 unique digests, 1 unique salts
Bitmaps: 16 bits, 65536 entries, 0x0000ffff mask, 262144 bytes, 5/13 rotates


Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:41:47 2018 (0 secs)
Time.Estimated...: Thu Oct 18 18:41:47 2018 (0 secs)
Guess.Mask.......: ?h [1]
Guess.Queue......: 1/6 (16.67%)
Speed.Dev.#1.....:    25324 H/s (0.01ms) @ Accel:1024 Loops:16 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 16/16 (100.00%)
Rejected.........: 0/16 (0.00%)
Restore.Point....: 1/1 (100.00%)
Candidates.#1....: 1 -> 6
HWMon.Dev.#1.....: N/A

Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:41:47 2018 (0 secs)
Time.Estimated...: Thu Oct 18 18:41:47 2018 (0 secs)
Guess.Mask.......: ?h?h [2]
Guess.Queue......: 2/6 (33.33%)
Speed.Dev.#1.....:   380.8 kH/s (0.02ms) @ Accel:1024 Loops:16 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 256/256 (100.00%)
Rejected.........: 0/256 (0.00%)
Restore.Point....: 16/16 (100.00%)
Candidates.#1....: 1a -> 6f
HWMon.Dev.#1.....: N/A

Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:41:48 2018 (0 secs)
Time.Estimated...: Thu Oct 18 18:41:48 2018 (0 secs)
Guess.Mask.......: ?h?h?h [3]
Guess.Queue......: 3/6 (50.00%)
Speed.Dev.#1.....:  5214.3 kH/s (0.19ms) @ Accel:1024 Loops:16 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 4096/4096 (100.00%)
Rejected.........: 0/4096 (0.00%)
Restore.Point....: 256/256 (100.00%)
Candidates.#1....: 1ab -> 6f6
HWMon.Dev.#1.....: N/A

Session..........: hashcat
Status...........: Exhausted
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:41:48 2018 (0 secs)
Time.Estimated...: Thu Oct 18 18:41:48 2018 (0 secs)
Guess.Mask.......: ?h?h?h?h [4]
Guess.Queue......: 4/6 (66.67%)
Speed.Dev.#1.....: 47023.8 kH/s (0.45ms) @ Accel:640 Loops:16 Thr:1 Vec:8
Recovered........: 0/1 (0.00%) Digests, 0/1 (0.00%) Salts
Progress.........: 65536/65536 (100.00%)
Rejected.........: 0/65536 (0.00%)
Restore.Point....: 4096/4096 (100.00%)
Candidates.#1....: 1abe -> 6f6f
HWMon.Dev.#1.....: N/A

813446902666d346ab42151a3beaf5a9:1a23b

Session..........: hashcat
Status...........: Cracked
Hash.Type........: MD5
Hash.Target......: 813446902666d346ab42151a3beaf5a9
Time.Started.....: Thu Oct 18 18:41:48 2018 (0 secs)
Time.Estimated...: Thu Oct 18 18:41:48 2018 (0 secs)
Guess.Mask.......: ?h?h?h?h?h [5]
Guess.Queue......: 5/6 (83.33%)
Speed.Dev.#1.....: 10025.2 kH/s (3.10ms) @ Accel:1024 Loops:16 Thr:1 Vec:8
Recovered........: 1/1 (100.00%) Digests, 1/1 (100.00%) Salts
Progress.........: 983040/1048576 (93.75%)
Rejected.........: 0/983040 (0.00%)
Restore.Point....: 40960/65536 (62.50%)
Candidates.#1....: 1abe3 -> 6f6f7
HWMon.Dev.#1.....: N/A

Started: Thu Oct 18 18:41:45 2018
Stopped: Thu Oct 18 18:41:49 2018
</code>
</pre>

We got it! Hashcat shows a new session for each mask increment and it managed to crack the hash with mask length 5:

<pre>
<code class="markdown">813446902666d346ab42151a3beaf5a9:1a23b</code>
</pre>

