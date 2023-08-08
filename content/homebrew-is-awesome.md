# Homebrew is awesome

Now that the Golang rewrite of S3Scanner is complete, I have a few TODOs to get packages setup for a few different package managers. Mostly I'm just curious what the process is to, for example, get a package into Homebrew.

I'm happy to report that it is very easy and totally painless.

The process started with their "Formula Cookbook" which lays out every option you might need when building your own formula. I was able to follow along easily and refer to the many other formulae for Go packages. The S3Scanner formula was easier to create than others probably are because there are no runtime dependencies and only one build dependency - Go.

I used the [aztfexport formula](https://github.com/Homebrew/homebrew-core/blob/master/Formula/aztfexport.rb) as a reference for no reason other than it was near the top of the search results for "go" in the formula repo. As a bonus, it uses the same method to inject the version number via link flags: `go build -ldflags="-X 'main.version=${VERSION}'"`.

Around 12:45 AM I created [my pull request](https://github.com/Homebrew/homebrew-core/pull/138025) which was maybe 1 hour after I began reading documentation! The next morning I had a code change suggestion to review from a maintainer which took all of one click to accept. After that, a series of seemingly automated actions took place coreographed by the maintainer adding labels to the PR.

The result of the actions was that one day later, the PR was merged to the `homebrew-core` repository and my formula was available! Immediately after getting the email from Github that this had happened, I pulled up a terminal to do `brew update && brew install s3scanner` which worked!

So yeah v3.0.0 of s3scanner is now available from Homebrew and the process to contribute could not have been smoother. Homebrew is awesome.