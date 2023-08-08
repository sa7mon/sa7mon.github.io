---
author: "Dan Salmon"
date: "2023-08-07T00:00:00Z"
summary: "Contributing to Homebrew - so easy a caveman could do it"
draft: true
tags: ["homebrew", "macos"]
slug: "homebrew-is-awesome"
title: "Homebrew is awesome"
type: "post"
---


# Homebrew is awesome

Now that the Golang rewrite of [S3Scanner](https://github.com/sa7mon/S3Scanner) is complete, I have a few TODOs to get it packaged for a few different package managers. Mostly I'm just curious what the process is to, for example, get a package into Homebrew.

**I'm happy to report it is very easy and totally painless!**

I started by following the [Formula Cookbook](https://docs.brew.sh/Formula-Cookbook) which lays out every option you might need when building your own *formula* (Homebrew's term for *package*). I was able to follow along easily and refer to the many other formulae for Go packages. The S3Scanner formula was easier to create than others probably are because there are no runtime dependencies and only one build dependency - Go.

I used the [aztfexport formula](https://github.com/Homebrew/homebrew-core/blob/master/Formula/aztfexport.rb) as a reference for no reason other than it was near the top of the search results for "go" in the [formula repo](https://github.com/Homebrew/homebrew-core). As a bonus, it uses the same method to inject the version number via link flags: `go build -ldflags="-X 'main.version=${VERSION}'"`.

Around 12:45 AM I submitted my [pull request](https://github.com/Homebrew/homebrew-core/pull/138025) which was only about 1 hour after I first opened the documentation! The next morning I had a code change suggestion to review from a maintainer which took all of one click to accept. After that, a series of seemingly automated actions took place triggered by the maintainer adding and removing labels from the PR.

The result of the actions was that a day after I submitted my PR, it was accepted and merged into the `homebrew-core` repository. My formula was now available! Immediately after getting the email from Github, I pulled up a terminal to do `brew update && brew install s3scanner` and sure enough, there was my package.

v3.0.0 of S3Scanner is now available from Homebrew and the process to contribute could not have been smoother. Homebrew is awesome.