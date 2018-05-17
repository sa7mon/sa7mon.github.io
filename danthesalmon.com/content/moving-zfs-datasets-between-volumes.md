+++
author = "Dan Salmon"
categories = ["homelab"]
date = 2017-11-29T03:07:25Z
description = "Move your data quickly with minimum downtime. "
draft = false
slug = "moving-zfs-datasets-between-volumes"
tags = ["homelab", "freenas"]
title = "Moving ZFS datasets between volumes"
post = "post"

+++

If you run ZFS like I do (and I think you should) there will probably come at time when you need to migrate a dataset from one volume to another. This was done in FreeNAS (FreeBSD), but the steps should apply to any \*nix system with ZFS


The great thing about ZFS is that we can do most of this migration with our services and everything still running.

We'll start by taking a snapshot of the dataset we want to move.

```bash
zfs snapshot oldpool/mydataset@snapshot1
```

Then we'll copy over the dataset to the new volume. If you have a large dataset you may want to run this command in a **screen** or **tmux** session. You can still use the old dataset while this is running. 

```bash
zfs send oldpool/mydataset@snapshot1 | zfs receive newpool/mydataset
```

After this is done, turn off all jails and file shares that access the old volume. Then we take a second snapshot

```bash
zfs snapshot oldpool/mydataset@snapshot2
```

Now we can do an "incremental" send that will just copy over the difference between this snapshot and the first. Should be much quicker than the first send

```bash
zfs send -i oldpool/mydataset@snapshot1 oldpool/mydataset@snapshot2 | zfs receive newpool/mydataset
```

After this send is done, you should be good to go. Make sure to point your file shares and jail storage sources to the new location. Because we used **zfs send**, all the permissions and metadata will be the exact same on the other end.



