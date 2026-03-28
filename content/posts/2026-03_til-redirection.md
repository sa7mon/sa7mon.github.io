+++
date = '2026-03-27T00:00:00-06:00'
draft = true
title = ''
type = 'post'
description = ''
tags = []
slug = ''
+++

This week, my team was cooperatively working on a HTB machine and got to the point of exploiting a Command Injection vulnerability. Naturally, my first suggestion was to pop over to revshells.com to quickly get a reverse shell payload we could copy/paste.

Follow-up to the head-scratcher we had in the HTB this morning, I finally have a satisfying answer.

The feature that allows you to use /dev/tcp/10.10.10.10/9001 in shells is called "[network redirection](https://www.gnu.org/software/bash/manual/html_node/Redirections.html)" and seems to be unique to Bash.

Notably (according to stackoverflow), it is not present in zsh, fish, or the Busybox/Android variants. Crucially, is it also not supported by dash which is what /bin/sh is symlinked to in modern distros including Ubuntu. If you want to test whether your shell supports the feature:
start a webserver on some porttry to : </dev/tcp/127.0.0.1/PORT_HERE
In Bash, you will get no output but no error. In any other shell, you will get cannot open...No such file. Lesson learned - when revshells says "Bash" it means "Bash".
