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

The first thing I had to do was create some criteria to use for categorizing spam accounts. With a few variations, here are the distinguishing features of a spam account:

* The majority of posts by the account are reblogs of posts from other spam accounts
* The reblogged posts are either risque photos of women or ads for seedy mobile games
* The URL or account name of the blog is either complete gibberish or the name of an account that was previously active, but got deactivated.

Looking harder at the spam posts, I found that they all included links and used various link shortening services. This made finding spam posts fairly easy because regular users posting links don't use such link shortening services. This may be a generalization, but I've found that false positive rates were very good using this as a criteria.

The other commonality that the spam posts have is in the posts that feature girls. In those posts, there is usually a link surrounding an `<h1>` element


## Categorizing

```python

h1name = r'<a href=.+(bit\.ly|j\.mp).+><h1>.+<\/h1><\/a>' # https://regexr.com/4fa6d

if 'caption' in post and re.search(h1name, post['caption']):
    return_info['spam'] = True
    return_info['condition'] = 'photo-caption-h1name'
elif 'publisher' in post and post['publisher'] == 'j.mp':
    return_info['spam'] = True
    return_info['condition'] = 'publisher-jmp'
elif 'source_title' in post and post['source_title'] == 'j.mp':
    return_info['spam'] = True
    return_info['condition'] = 'sourcetitle-jmp'
elif 'publisher' in post and post['publisher'] == 'bit.ly':
    return_info['spam'] = True
    return_info['condition'] = 'publisher-bitly'
elif 'source_title' in post and post['source_title'] == 'bit.ly':
    return_info['spam'] = True
    return_info['condition'] = 'sourcetitle-bitly'
else:
    return_info['spam'] = False
```

**Results**

'photo-caption-h1name%' - 19,846
'publisher-jmp%' - 18,167
'sourcetitle-jmp%' - 49,325
'publisher-bitly%' - 11,321
'sourcetitle-bitly%' - 69,369
'imported%' - 29,384

## Outline

* Rough results
* Background
	* Kept seeing so many spam accounts, decided to investigate
	* Saw it as a good chance to learn Django and how to make a distributed application
* Spam criteria
	* Some SFW examples

## Data

```
select count(*) from api_blog 4,647,267
select count(*) from api_blog where spam = true 197,413
select count(*) from api_blog where spam = false 4,449,854
```