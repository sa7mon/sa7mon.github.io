+++
author = "Dan Salmon"
date = 2017-08-26T04:57:15Z
description = ""
draft = false
tags = ["android"]
slug = "fix-haxm-emulation-error-in-android-studio"
title = "Fix HAXM Emulation Error in Android Studio"

+++

**Requirement Note:** Android Studio requires an Intel processor with VT-X support for it’s built-in emulators to work. If you want to do Android development with emulators on an AMD processor, I would highly suggest using [Genymotion](https://www.genymotion.com/#!/). If you’re not sure if your Intel processor supports VT-X, Intel has [an article here](http://www.intel.com/support/processors/sb/cs-030729.htm).

---

This error is occurring because Android Studio requires Intel Hardware Accelerated Execution Manager (Intel HAXM) to run it’s emulators. All we need to do is install this tool.

![error](../images/error.png)

# Step 1

After starting Android Studio, open the SDK manager and scroll to the Extras folder near the bottom. Check the Intel x86 Emulator Accelerator (HAXM installer) option and hit the Install packages button.

![haxm-win-img01](../images/haxm-win-img01.png)

# Step 2

After it’s done installing the package, you need to run the installer. It’s located in *\<sdk>/extras/intel/HardwareAcceleratedExecutionManager/IntelHAXM.exe*. The location of \<sdk> is near the top of the SDK Manager window and is usually in *C:\Users\(user)\AppData\Local\Android\sdk*.

![installer](../images/installer.png)

Just accept all the defaults for the installer until it finishes. When it’s done, restart Android Studio and try to start your VM up again. This time it should start right up.