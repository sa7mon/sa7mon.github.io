+++
author = "Dan Salmon"
date = 2020-02-25T04:57:15Z
description = ""
draft = true
tags = ["research", "tumblr"]
slug = "tumblr-has-a-spam-problem"
title = "Tumblr Has a Spam Problem"
type = "post"
+++

# Background

I have an account on Tumblr, though I seem to be one of the few people still on the site. Over the past few years, I've noticed an uptick in the amount of spam accounts following me. A considerable increase, in fact, so I decided to try to quantify this problem.


# Searching for Spam

I saw this as a good chance to try out Django and learn about building a distributed application. What I ended up building could best be described as a spider.

# Spam Criteria

The first thing I had to do was create some criteria to use for categorizing accounts as "spam". With a few variations, here are the distinguishing features of a spam account:

* The majority of posts by the account are reblogs of posts from other spam accounts
* The reblogged posts are either risque photos of women or ads for seedy mobile games
* The URL or account name of the blog is either complete gibberish or the name of an account that was previously active, but got deactivated. That old account most likely had a few popular posts with lots of notes.
* If the post was an image of a woman, the caption was usually in the format "Name (number Images)". i.e. "Natalie (30 Images)"
* Any link in the body of the post used a URL shortening service such as bit[.]ly or j[.]mp

Here is an example of a pretty typical post:

![spam-1](spam-1-moveme.PNG)

I began to look just for posts that had that text format of "Name (number Images)", but it was not specific enough because the posts would sometimes use other words instead of "Images" ("selfies", "videos", etc.) or sometimes they would just have the name alone. Also, some of the posts were for seedy-looking mobile games:

![spam-2](spam-2-moveme.png)

I decided I would focus my criteria mainly on the shortened links because regular users posting links don't generally use link shortening services. This may be a generalization, but in practice it yielded a very low false positive rate.

Utilizing the Tumblr API, I was able to get back the HTML source of any post I wanted to classify as spam or non-spam. Here are the spam conditions I settled on:

* Photo caption matches the regex pattern `<a href=.+(bit\.ly|j\.mp).+><h1>.+<\/h1><\/a>`
* Publisher field contains a URL within the j[.]mp or bit[.]ly domain
* Source field contains a URL within the j[.]mp or bit[.]ly domain

If any one of the criteria are met, the account is classified as spam. (The "publisher" and "source" fields are user-editable fields that add links to your post.)

## Process

To accomplish the spam-finding, I built an API and a client application in Python. The flowed looks something like this:

1. The client queries our API for the name of a blog to check
2. The client queries the Tumblr API for the latest 20 posts for this blog. Why 20 posts? Because that's the max number of posts the Tumblr API returns with one request and there's a daily limit of API requests we can make.
3. For each blog post, compare it against our list of criteria. If we determine the post is spam:
    * Send a request back to our API marking this blog as spam in the database
    * Collect the names of blogs that interracted with this spam post by checking the notes (who liked it and who reblogged it)
    * Send these blog names back to our API as TODO items
4. Send a request back to our API marking that blog as checked, and start the process back over at Step 1.

Or for those who prefer a more visual explanation:

![spam-1](flowchart-moveme.png)


## Results

Time for the numbers: 

* Total blogs checked: `4,647,267`
* Non-spam blogs: `4,449,854`
* Spam blogs: `197,413`

And here are the criteria that were hit:

* bitly or jmp publisher: `29,488`
* bitly or jmp source: `118,694`
* photo caption regex: `19,846`
* other: `29,384`

(The "other" category is due to me not storing in the database what criteria was matched when I first started the project.)

I was able to find nearly 200,000 spam accounts with my simple searching methods. I am certain there are far more than this if I would have kept the application running indefinitely. 

I should note that it would be inaccurate for someone to look at those numbers and extrapolate that about 4% of all Tumblr accounts are spam. The accounts I was looking at were not chosen at random, but rather were analyzed because they interacted with a suspected spam account. 

## Conclusion

Maybe it's due to the internal turmoil that comes from being bought and sold twice in 6 years, but it really seems like Tumblr is not doing a great job at curbing the spam problem. 