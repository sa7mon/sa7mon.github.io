+++
author = "Dan Salmon"
date = 2022-11-04T00:00:00Z
description = ""
draft = true
categories = ["3D Printing"]
slug = "ender-3-pro-btouch"
tags = ["Ender 3"]
title = "Installing a Bl Touch on an Ender 3 Pro (or how I lost my mind)"
type = "post"
+++

<!-- # Installing a Bl Touch on an Ender 3 Pro (or how I lost my mind) -->

This is just a little project log documenting my progress installing a BLTouch on and Ender 3 (and almost losing my mind in the process).

Overall:
- Everywhere you turn, there's a broken link

I have an almost-stock Creality Ender 3 Pro. The only changes I've made to it over the past few years are:
- swapping the magnetic bed for a glass build surface
- relocating the main board to the back of the printer
- upgrading the main board to the Creality "Silent Motor" board

A friend of mine gave me a BLTouch a few years ago and it had sat in a box until last week when I was having issues printing a particularly tricky thing I had designed. "Surely the issue is with the printer and not in my design" I thought - so it was time to finally install the BLTouch. 

After unboxing all the parts, I followed the included paper instructions to install the probe and route cable through the existing wire loom back to the board. So far so good!

Next, the instructions said I needed to update the firmware on the board. Makes sense, so I went to the link included and landed on a 404 page. The root of this domain just shows a default installation page

(pic)

(pic)

I assume they just changed domains, but after Googling "Creality 3D" I was even more baffled as there are a wealth of similar-sounding domains that rank near the top of the results:

- creality.com
- creality3dofficial.com
- creality3d.shop

At this point I decided to abandon the paper instructions and looked for a good online guide.
The (seemingly) official Creality YouTube install video (https://www.youtube.com/watch?v=2B4qdKdqJj4) points to another dead link (https://www.creality.com/download/bl-touch-firmware_c0006).


- The BLTouch came with an SPI flasher which the video shows, so I disconnected the LCD and plugged the flasher into the board, then into a USB-C dongle on my Mac.
- Made the adjustments to Marlin as directed. Built it but got some errors like `region `text' overflowed by 2066 bytes`
    - During build, chose "Melzi optimized" even though I'm not sure what that means.
    - Ah, with all the new options enabled, the built firmware is too big to fit in the limited amount of memory on this board.
    - Disabled ARC, SD card support, and other features people recommended I don't need. (refer to config files for exact changes)
    - Used [this Reddit thread](https://www.reddit.com/r/3Dprinting/comments/9wk80l/what_can_be_removed_from_marlin_119_to_fit_on/e9las3x/)
- Successfully built it, but I still can't Upload it to the board through the Mini-USB port. Just keep getting the "avrdude not in sync" error
- Didn't really seem to be showing up in the Win10 VM in UTM. Maybe I didn't share the right USB controller?

- This is dumb, I'll just plug it into my Ryzen desktop.
- Okay, something shows up in Device Manager when I plug it in
- I re-install the VS Code extensions on this desktop, copying over my edited Marlin config
- I go to Upload the firmware to the board, but I still get that same "avrdude not in sync" error
- Wtf??
- I do some more searching around online for "Ender 3 Pro avrdude not in sync" and similar terms
- Ah, the thing I had missed from the Creality video is that my board needs to have a bootloader burned to it before I can update the firmware!
    - The Teaching Tech guide [here](https://teachingtechyt.github.io/upgrades.html#bltouch) also mentions that.
    - Turns out, the Creality v1.1.5 "Silent stepper motor" board I have is 8-bit and needs a bootloader before I can just flash my own firmware.

## Flashing the Bootloader

- Okay, head back to the mystery Google Drive folder and grab a random `progisp*.rar` which might as well be called "totally_not_malware", but the video referred to this application.
- It gives crazy errors as soon as I launch it
    - After ignoring them, I try to "read values" (or whatever) and it doesn't work.
    - I read some things online about needing to use the COM port the app is expecting, which I think is COM1.
- Something in my motherboard is constantly taking COM1. I tell Windows to assign the printer COM1 and move the motherboard thing to another COM port, but as soon as the hardware changes are refreshed the motherboard thing is taking COM1 again???
- I go into the config window and the COM port I think the flasher is using isn't even listed as an option
- This isn't working. I go to close it but it won't even let me do that.
- Fiddled with the compatibility settings for a bit, but that didn't change anything.
- Tried plugging the main board mini-usb into the desktop directly
- When that didn't work, I tried using Zadig as I have in the past when playing with the HackRF. Still no go.

- I start trying to think outside the box. I'm not able to communicate with the board with my Mac or with my Windows desktop. 
- During some of my searches to get the COM port stuff figured out, I had seen references to people using an Arduino Uno to flash the bootloader.
- I don't have one, but I do have a Raspberry Pi in the printer already for running Octoprint.
    - Found https://johnwyles.github.io/posts/flashing-the-creality-ender-3-with-a-raspberry-pi/
    - Followed guide in getting Pi physically connected
    - (Pic here of the board connected to the Pi)
    - After SSHing into the Pi, I got stuck on the firmware building step as the TH3D repo had changed significantly (something about moving from V1 to V2 of something) and I couldn't figure out how to reconcile the differences.
    - The next part of that guide walks you through setting up `avrdude` settings to flash the board, so I skipped to that part.
    - Got the config files made and:
        - `sudo avrdude -p atmega1284p -C ~/avrdude.conf -c pi_1 -v`
            ```
            avrdude: AVR device initialized and ready to accept instructions

            Reading | ################################################## | 100% 0.00s

            avrdude: Device signature = 0x1e9705 (probably m1284p)
            avrdude: safemode: lfuse reads as D6
            avrdude: safemode: hfuse reads as DC
            avrdude: safemode: efuse reads as FD

            avrdude: safemode: lfuse reads as D6
            avrdude: safemode: hfuse reads as DC
            avrdude: safemode: efuse reads as FD
            avrdude: safemode: Fuses OK (E:FD, H:DC, L:D6)

            avrdude done.  Thank you.
            ```
    - Holy crap holy crap we can communicate with the board finally!!!!!
    - I still didn't have an actual bootloader to flash the board with, so followed this video to build bootloader with Ardiuno from Creality source code: https://www.youtube.com/watch?v=aquuSNEekvY
        - This seemed more likely to work than the previous guide since we're using Creality source code instead of whatever TH3D is
        - Maybe the only place I saw reference an actual Creality web page that lists Ender source code [here](https://www.creality3dofficial.com/pages/firmware-download) which points [here](https://drive.google.com/drive/folders/1A9vSzhdJqm4qdt4wmbYEP8VPbX15ScUU).
        - `Ender 3 Pro BLTouch_8bit_1.1.6.rar`
        - Like the last guide, this one directed me to use the Ardiuno IDE which is very dated, to build `Marlin.ino.with_bootloader.sanguino.hex` and `Marlin.ino.sanguino.hex`
            - Don't ask me what Sanguino means, but it pops up all over
    - scp'd the files up
    ```
    ╭─dan@dans-MacBook-Air ~/Downloads/Ender-3 Pro1.1.6.1 Source Code/Marlin
    ╰─$ scp Marlin.ino*.hex dan@10.10.1.128:~/
    Marlin.ino.sanguino.hex                             100%  345KB   1.7MB/s   00:00
    Marlin.ino.with_bootloader.sanguino.hex             100%  338KB   2.1MB/s   00:00
    ```
    - Then:
        - Backup existing firmware: `sudo avrdude -p atmega1284p -C ~/avrdude.conf -c pi_1 -v -U flash:r:ender-3_before_flash_backup.hex:i`
        - Write new firmware: `sudo avrdude -p atmega1284p -C ~/avrdude.conf -c pi_1 -v -U flash:w:Marlin.ino.with_bootloader.sanguino.hex:i`
    - Seemed to work! I think we have burned a bootloader!
- The printer now (I think) has my custom firmware. The BL Touch light and LCD light turn on, but doesn't show anything. I think I accidentally completely disabled the screen when disabling features to slim down the build.
- Let's see if we can enable abl and screen
- Enabled all the screen options in Marlin again and built a new hex file

- I also started thinking, "Hmm since avrdude worked from the Pi, maybe I should try directly from my Mac now"
    - ```bash
    $ ls /dev/cu.*
    /dev/cu.Bluetooth-Incoming-Port  /dev/cu.usbserial-130  /dev/cu.wlan-debug
    ```

    - ```shell
    $ avrdude -p atmega1284p -C ./avrdude.conf -c arduino -P /dev/cu.usbserial-130  –v
    avrdude: AVR device initialized and ready to accept instructions

    Reading | ################################################## | 100% 0.00s

    avrdude: Device signature = 0x1e9705 (probably m1284p)

    avrdude done.  Thank you.
    ```

    - ```shell
    $ avrdude -p atmega1284p -C ./avrdude.conf -c arduino -P /dev/cu.usbserial-130 -U flash:r:ender-3_after_bootloader_flash:i

    avrdude: AVR device initialized and ready to accept instructions

    Reading | ################################################## | 100% 0.00s

    avrdude: Device signature = 0x1e9705 (probably m1284p)
    avrdude: reading flash memory:

    Reading | ################################################## | 100% 14.09s

    avrdude: writing output file "ender-3_after_bootloader_flash"

    avrdude done.  Thank you.
    ```

    - ```
    $ avrdude -p atmega1284p -C ./avrdude.conf -c arduino -P /dev/cu.usbserial-130 -U flash:w:firmware.hex:i

    avrdude: AVR device initialized and ready to accept instructions

    Reading | ################################################## | 100% 0.00s

    avrdude: Device signature = 0x1e9705 (probably m1284p)
    avrdude: NOTE: "flash" memory has been specified, an erase cycle will be performed
            To disable this feature, specify the -D option.
    avrdude: erasing chip
    avrdude: reading input file "firmware.hex"
    avrdude: writing flash (119884 bytes):

    Writing | ################################################## | 100% 16.36s

    avrdude: 119884 bytes of flash written
    avrdude: verifying flash memory against firmware.hex:

    Reading | ################################################## | 100% 12.89s

    avrdude: 119884 bytes of flash verified

    avrdude done.  Thank you.
    ```
- Sweet! Now I can disconnect these jumper wires from the Pi and just flash directly from my Mac.
- Auto Build Marlin / Platformio will build the firmware, then I just have to drop to CLI to flash manually with `avrdude`

- With my newly-built hex file which should re-enable the screen, I flash the board
- Screen and BL Touch are still off when the printer powers up

- Wtf is going on? Maybe I made some changes I shouldn't have somewhere.
- I start fresh from the Marlin example for the Ender 3 without making any config changes and compile it. This shouldn't enable the BLTouch, but should get the screen working. Compiled, flashed, and screen is still dead.
- Decided to just forge ahead - enabled the BL Touch options, and disabled enough other features to make it fit in memory

- Compiled, flashed, and still nothing. 
- On a whim, I decided to re-seat the LCD screen connector board thing. Flipped power back on and the BL Touch and screen came on! Huge success! BL Touch menu option is there now too.

## Post Sequence

- Everything from here on was pretty smooth sailing. I was able to Follow the TeachingTech guide to set the offsets including the Z-probe.
- Can now create and save mesh data by selecting the (whatever it's called) menu option and having the printer probe the bed.
- Went into OctoPrint and installed the mesh visualizer plugin to see how we were looking (screenshot)
- Did a few cycles of level-adjust knobs-level until we were looking good
- Added a "before print job starts" GCODE script in OctoPrint:
```ini
M420 S1 Z4 ; Set bed leveling on with a 4 layer fade out
```
- First layer now looks perfect!
- Pushed my Marlin config [here](https://github.com/sa7mon/marlin-configs/tree/import-2.1.x/config/examples/Creality/Ender-3%20Pro/CrealityV1)

- Now can use the Firmware Updater plugin in Octoprint to flash firmware over the network
- Only conclusion as to why Auto Build Marlin/Platformio don't work to upload is that the avrdude settings weren't right. Even tried messing with the Upload Port setting.

## Other references

- https://jango.si/post/3dprint/flashing-ender3/
- https://support.th3dstudio.com/helpcenter/creality-ender-3-pro-8-bit-firmware/