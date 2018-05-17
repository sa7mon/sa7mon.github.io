+++
author = "Dan Salmon"
date = 2017-08-18T03:56:32Z
description = ""
draft = false
tags = ["freenas"]
slug = "view-reset-sickrage-web-password"
title = "View/Reset SickRage Web Password"

+++

# Background

I recently forgot my password for SickRage and was surprised by the little documentation online.  I have it installed in a jail in FreeNAS, so that’s what I’ll be focused on here but the instructions should be exactly the same if you have it installed inside an OS.

# Steps

It’s actually really easy, assuming you have command-line access. In FreeNAS, open a shell on your SickRage jail and edit the following file:

```language-bash
/var/db/sickrage/config.ini
```
There should be 2 lines in that file, “web_username” and “web_password”. Just edit these with what you want them to be and save the file. I installed Nano in my environment, but vi should work just fine.

![editFile](../images/editFile.png)