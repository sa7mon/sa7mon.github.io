---
author: "Dan Salmon"
date: 2020-06-19T00:00:00Z
description: "There are a few different ways to run Docker on Proxmox"
draft: false
tags: ["proxmox", "homelab", "docker"]
slug: "running-docker-on-proxmox"
title: "Running Docker on Proxmox"
summary: "There are a few different ways to run Docker on Proxmox. Here are some instructions and unscientific benchmarks comparing them."
type: "post"
toc: true
---

<style>
.chart .legend {
    fill: black;
    text-anchor: start;
}
.chart text {
    fill: black;
    font: 15px sans-serif;
}
.chart .label {
    fill: black;
    font: 14px sans-serif;
    text-anchor: end;
}
.bar:hover {
    fill: brown;
}
.axis path,
.axis line {
    fill: none;
    stroke: #000;
    shape-rendering: crispEdges;
}
</style>

During the process of evaluating Proxmox as a Docker host, I found that there are at least 3 methods for running Docker containers in Proxmox. Here are instructions for doing those 3 methods as well as some simple disk speed benchmarks to compare.

The 3 methods I will outline are:

* Docker running in an LXC container
* Docker running in a VM
* Docker running on Proxmox itself

### Run Docker in an LXC container

**Security warning:** This configuration offers very little, if any security to segment the contents of the container from the Proxmox host. This method should not be used in production.

1. On the Proxmox host, edit `/etc/modules-load.d/modules.conf` to add the aufs and overlay kernel modules

    ```bash
    # /etc/modules: kernel modules to load at boot time.
    #
    # This file contains the names of kernel modules that should be loaded
    # at boot time, one per line. Lines beginning with "#" are ignored.
    aufs
    overlay
    ```

2. Restart Proxmox host
3. Create an LXC container with your desired settings and OS, making sure to uncheck "unprivileged container", but don't start it yet. I'm using Debian 10.
4. In Proxmox, edit the `/etc/pve/lxc/<id>.conf` file where `<id>` is the ID given to your container:
    ```bash
    lxc.apparmor.profile: unconfined
    lxc.cgroup.devices.allow: a
    lxc.cap.drop:
    ```
4. Start the container
5. In the container, create `/etc/docker/daemon.json` and make the contents:
    ```json
    {
        "storage-driver": "overlay2"
    }
    ```
6. Install Docker. The official instructions for Debian can be found [here](https://docs.docker.com/engine/install/debian/). They boil down to this:

    ```bash
    apt update
    apt-get install -y apt-transport-https ca-certificates curl gnupg-agent software-properties-common
    curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add -
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
    apt-get update
    apt-get install -y docker-ce docker-ce-cli containerd.io
    ```

### Run Docker in a VM

1. Create a VM with any operating system supported by Docker Engine. A list of officially supported distributions can be found [here](https://docs.docker.com/engine/install/#supported-platforms)
1. Follow the official instructions for your distribution linked from the previous docs page
1. That's it!

### Run Docker in Proxmox

Note: **This method should not be used in a production environment.** Like the LXC method, there is very little segmentation between the containers and the Proxmox host. Additionally, the docker daemon runs as the Proxmox `root` user which is a universally bad idea. This method is the least secure of the 3 listed here.

1. Follow the official documentation for installing Docker Engine on Debian [found here](https://docs.docker.com/engine/install/debian/)
1. You may need to restart Proxmox after installing Docker, but after that it should be good to go
1. *Optional*: By default, the Docker `data-root` will be on your local storage where Proxmox itself is installed. If you want Docker to store its data in another location, edit `/lib/systemd/system/docker.service` and change the `ExecStart=` line to include the `--data-root` option. For example, I made a ZFS dataset and pointed Docker to it like this:

    `ExecStart=/usr/bin/dockerd --data-root /tank/docker-root -H fd:// --containerd=/run/containerd/containerd.sock`

### Testing Methodology

I first have to preface these results by saying that this test was very unscientific. This is my first time trying out Proxmox and first time getting "under the hood" of Docker.

* My Docker use case is focused on disk performance. Thus, I did not test CPU performance to see what kind of a virtualization penalty would be introduced with either the LXC or VM method.

* While I tried to keep the test as fair as possible, there were certain variables I couldn't keep identical across the board, namely: the storage drivers and whether AppArmor was enabled. Here is a breakdown of the variables:

    <style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;margin-bottom:8px;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
    overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
    font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
    .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
    .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
    .tg .tg-7btt{border-color:inherit;font-weight:bold;text-align:center;vertical-align:top}
    .tg .tg-fymr{border-color:inherit;font-weight:bold;text-align:left;vertical-align:top}
    </style>
    <table class="tg">
    <thead>
    <tr>
        <th class="tg-0pky"></th>
        <th class="tg-7btt">CPU Cores</th>
        <th class="tg-7btt">RAM</th>
        <th class="tg-7btt">Storage Driver</th>
        <th class="tg-7btt">Backing Filesystem</th>
        <th class="tg-7btt">AppArmor</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="tg-fymr">LXC</td>
        <td class="tg-c3ow">2</td>
        <td class="tg-c3ow">4GB</td>
        <td class="tg-c3ow">overlay2</td>
        <td class="tg-c3ow">extfs</td>
        <td class="tg-c3ow">disabled</td>
    </tr>
    <tr>
        <td class="tg-fymr">VM</td>
        <td class="tg-c3ow">2</td>
        <td class="tg-c3ow">4GB</td>
        <td class="tg-c3ow">overlay2</td>
        <td class="tg-c3ow">extfs</td>
        <td class="tg-c3ow">enabled</td>
    </tr>
    <tr>
        <td class="tg-fymr">Proxmox</td>
        <td class="tg-c3ow">2</td>
        <td class="tg-c3ow">4GB</td>
        <td class="tg-c3ow">zfs</td>
        <td class="tg-c3ow">n/a</td>
        <td class="tg-c3ow">enabled</td>
    </tr>
    </tbody>
    </table>

* When testing on Proxmox I limited the max CPU cores and memory of the benchmark container using `--memory="2g"` and `--cpuset-cpus="0,1"`.
* My host consists of an HP Z620 workstation with the following hardware:
    * 1x Xeon E5-2620 CPU
    * 4x 4GB DDR3-1333 RAM modules, 16GB total
    * 4x 1TB WD Blue SATA SSDs configured as a 2x2 ZFS mirror
* Since the VM and LXC container ran on the ZFS pool, I edited the Docker config when testing on Proxmox to move the `data-root` to a dataset on the ZFS pool. This is why the ZFS storage driver is used.


### Disk Speed Benchmarks

<figure class="bg-light">
<svg xmlns="http://www.w3.org/2000/svg" class="chart" width="100%" height="630">
        <g transform="translate(160,5)">
            <rect fill="#1f77b4" class="bar" width="10.606689351813428" height="19"/>
            <text x="12.606689351813428" y="10" fill="black" dy=".35em">21.3</text>
            <text class="label" x="-15" y="30" dy=".35em">4k rand read</text>
        </g>
        <g transform="translate(160,25)">
            <rect fill="#aec7e8" class="bar" width="7.0213295709187475" height="19"/>
            <text x="9.021329570918748" y="10" fill="black" dy=".35em">14.1</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,45)">
            <rect fill="#ff7f0e" class="bar" width="12.299775915013692" height="19"/>
            <text x="14.299775915013692" y="10" fill="black" dy=".35em">24.7</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,75)">
            <rect fill="#1f77b4" class="bar" width="0.9461366088472072" height="19"/>
            <text x="2.9461366088472074" y="10" fill="black" dy=".35em">1.9</text>
            <text class="label" x="-15" y="30" dy=".35em">4k rand write</text>
        </g>
        <g transform="translate(160,95)">
            <rect fill="#aec7e8" class="bar" width="0.7469499543530583" height="19"/>
            <text x="2.746949954353058" y="10" fill="black" dy=".35em">1.5</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,115)">
            <rect fill="#ff7f0e" class="bar" width="2.5894265084239354" height="19"/>
            <text x="4.589426508423935" y="10" fill="black" dy=".35em">5.2</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,145)">
            <rect fill="#1f77b4" class="bar" width="237.72927213876667" height="19"/>
            <text x="239.72927213876667" y="10" fill="black" dy=".35em">477.4</text>
            <text class="label" x="-15" y="30" dy=".35em">4k seq read</text>
        </g>
        <g transform="translate(160,165)">
            <rect fill="#aec7e8" class="bar" width="53.23263341356129" height="19"/>
            <text x="55.23263341356129" y="10" fill="black" dy=".35em">106.9</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,185)">
            <rect fill="#ff7f0e" class="bar" width="224.98132625114118" height="19"/>
            <text x="226.98132625114118" y="10" fill="black" dy=".35em">451.8</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,215)">
            <rect fill="#1f77b4" class="bar" width="1.2947132542119677" height="19"/>
            <text x="3.2947132542119677" y="10" fill="black" dy=".35em">2.6</text>
            <text class="label" x="-15" y="30" dy=".35em">4k seq write</text>
        </g>
        <g transform="translate(160,235)">
            <rect fill="#aec7e8" class="bar" width="0.9959332724707444" height="19"/>
            <text x="2.9959332724707446" y="10" fill="black" dy=".35em">2</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,255)">
            <rect fill="#ff7f0e" class="bar" width="2.938003153788696" height="19"/>
            <text x="4.938003153788696" y="10" fill="black" dy=".35em">5.9</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,285)">
            <rect fill="#1f77b4" class="bar" width="148.44385426176447" height="19"/>
            <text x="150.44385426176447" y="10" fill="black" dy=".35em">298.1</text>
            <text class="label" x="-15" y="30" dy=".35em">1M rand read</text>
        </g>
        <g transform="translate(160,305)">
            <rect fill="#aec7e8" class="bar" width="354.7016349904556" height="19"/>
            <text x="356.7016349904556" y="10" fill="black" dy=".35em">712.3</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,325)">
            <rect fill="#ff7f0e" class="bar" width="403.55216200514565" height="19"/>
            <text x="405.55216200514565" y="10" fill="black" dy=".35em">810.4</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,355)">
            <rect fill="#1f77b4" class="bar" width="36.30176778155864" height="19"/>
            <text x="38.30176778155864" y="10" fill="black" dy=".35em">72.9</text>
            <text class="label" x="-15" y="30" dy=".35em">1M rand write</text>
        </g>
        <g transform="translate(160,375)">
            <rect fill="#aec7e8" class="bar" width="60.6025396298448" height="19"/>
            <text x="62.6025396298448" y="10" fill="black" dy=".35em">121.7</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,395)">
            <rect fill="#ff7f0e" class="bar" width="88.93684123163747" height="19"/>
            <text x="90.93684123163747" y="10" fill="black" dy=".35em">178.6</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,425)">
            <rect fill="#1f77b4" class="bar" width="282.5960660635737" height="19"/>
            <text x="284.5960660635737" y="10" fill="black" dy=".35em">567.5</text>
            <text class="label" x="-15" y="30" dy=".35em">1M seq read</text>
        </g>
        <g transform="translate(160,445)">
            <rect fill="#aec7e8" class="bar" width="517.138351730434" height="19"/>
            <text x="519.138351730434" y="10" fill="black" dy=".35em">1038.5</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,465)">
            <rect fill="#ff7f0e" class="bar" width="600" height="19"/>
            <text x="602" y="10" fill="black" dy=".35em">1204.9</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,495)">
            <rect fill="#1f77b4" class="bar" width="32.06905137355797" height="19"/>
            <text x="34.06905137355797" y="10" fill="black" dy=".35em">64.4</text>
            <text class="label" x="-15" y="30" dy=".35em">1M seq write</text>
        </g>
        <g transform="translate(160,515)">
            <rect fill="#aec7e8" class="bar" width="56.867789858079504" height="19"/>
            <text x="58.867789858079504" y="10" fill="black" dy=".35em">114.2</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,535)">
            <rect fill="#ff7f0e" class="bar" width="90.3311478130965" height="19"/>
            <text x="92.3311478130965" y="10" fill="black" dy=".35em">181.4</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,565)">
            <rect fill="#1f77b4" class="bar" width="93.74719893767117" height="19"/>
            <text x="95.74719893767117" y="10" fill="black" dy=".35em">188.26</text>
            <text class="label" x="-15" y="30" dy=".35em">Average</text>
        </g>
        <g transform="translate(160,585)">
            <rect fill="#aec7e8" class="bar" width="131.41339530251472" height="19"/>
            <text x="133.41339530251472" y="10" fill="black" dy=".35em">263.9</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g transform="translate(160,605)">
            <rect fill="#ff7f0e" class="bar" width="178.2222591086397" height="19"/>
            <text x="180.2222591086397" y="10" fill="black" dy=".35em">357.9</text>
            <text class="label" x="-15" y="30" dy=".35em"/>
        </g>
        <g class="y axis" transform="translate(160, -5)">
            <g class="tick" style="opacity: 1;" transform="translate(0,640)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,576)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,512)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,448)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,384)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,320)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,256)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,192.00000000000003)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,127.99999999999997)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,63.999999999999986)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <g class="tick" style="opacity: 1;" transform="translate(0,0)">
            <line x2="0" y2="0"/>
            <text dy=".32em" style="text-anchor: end;" x="-3" y="0"/>
            </g>
            <path class="domain" d="M0,0H0V640H0"/>
        </g>
        <g transform="translate(782,5)">
            <rect width="18" height="18" style="fill: rgb(31, 119, 180); stroke: rgb(31, 119, 180);"/>
            <text class="legend" x="22" y="14">LXC</text>
        </g>
        <g transform="translate(782,27)">
            <rect width="18" height="18" style="fill: rgb(174, 199, 232); stroke: rgb(174, 199, 232);"/>
            <text class="legend" x="22" y="14">VM</text>
        </g>
        <g transform="translate(782,49)">
            <rect width="18" height="18" style="fill: rgb(255, 127, 14); stroke: rgb(255, 127, 14);"/>
            <text class="legend" x="22" y="14">Proxmox</text>
        </g>
</svg>
<figcaption style="text-align: center; font-size: 16px; color: black;">Fig. 1 - Disk speed in MB/s</figcaption>
</figure>

### Conclusion

Looking at the results, I am not surprised at all that Docker installed directly on Proxmox is the fastest option. What is surprising is that the VM performance was better than the LXC container. I would have thought that since there's a very thing virtualization layer between the Proxmox host and the LXC container the LXC performance would be better than a fully virtualized VM. 

Of course there's a very good chance that there are some settings I could have changed to get better performance out of all 3 options but I wanted to just get some quick numbers for "out of the box" performance.


