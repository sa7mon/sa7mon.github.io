+++
author = "Dan Salmon"
date = 2018-07-03T00:40:32Z
description = "Using Docker, we can run Oracle 12c on MacOS"
draft = true
tags = ["oracle","docker","macOS"]
slug = "running-oracle-12c-on-macos"
title = "Running Oracle 12c on MacOS"
type = "post"
+++

In this post, we will go through the steps of installing Oracle 12c in a Docker container and connect to the database using SQL Developer on a Mac. This is a much lighter way of running Oracle locally compared to running a full Windows virtual machine with VMWare or Virtualbox. 

### 1. Install Docker

**- If your Mac is newer than 2010**<br>
You can simply install “Docker for Mac”.
Download and install “Docker for Mac” [from here](https://store.docker.com/editions/community/docker-ce-desktop-mac)  (You’ll have to create a quick account to download it. Annoying, I know)
 
**- If your Mac is 2010 or older**<br>
You can still run Docker, but there will be a few extra steps. You’ll install a tool called “Docker Toolbox” which includes everything you need.
Download and install “Docker Toolbox” [here](https://docs.docker.com/toolbox/overview/#ready-to-get-started).
 
### 2. Pull Docker image
* Open the Terminal app and run ```docker version```. If you get no errors, Docker is installed correctly and running. 
* Run ```docker pull sath89/oracle-12c```

### 3. Create container
* Create a directory to save our Oracle database outside the container: ```mkdir ~/oracle_data```
* ```docker run -d -p 8080:8080 -p 1521:1521 -v ~/oracle_data/:/u01/app/oracle sath89/oracle-12c``` This should return a long hash string.
* Open Kitematic and find the container we just created.
* Click the "Start" button. The first time you start the container, it will take a 5-10 minutes for the database to build. Just wait until the log reads ```Database ready to use. Enjoy! ;)```

### 4. Install SQL Developer
* Head to [this page](https://www.oracle.com/technetwork/developer-tools/sql-developer/downloads/index.html), click the Agree button and hit Download. You'll have to create a quick account to continue through the download (very annoying, I agree).
* Unzip, drag the app into the Applications folder, and fire it up

### 5. Connect to Oracle database
* In SQL Developer, click the green plus button to create a new connection.
* Get the IP address of your Oracle container by opening Kitematic and switching to the Settings and then Hostname / Ports. It may just be 'localhost'.
	* (Check if this is always localhost when using Docker for Mac)
* The default credentials to connect are as follows:
	* **Connection Name:** Whatever you want. I suggest "Docker - Oracle System"
	* **Username:** system
	* **Password:** oracle
	* **Hostname:** localhost
	* **Port:** 1521
	* **SID:** xe

### 6. Creating new databases

### Potential Problems

* ```IO Error: The Network Adapter could not establish the connection```
   
 This means that you are trying to connect with SQL Developer, but your Docker container is stopped. Open Kitematic, select your Oracle container, and click "Start". When the logs read "Database ready to use. Enjoy! ;)"

