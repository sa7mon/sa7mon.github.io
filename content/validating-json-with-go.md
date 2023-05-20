+++
author = "Dan Salmon"
date = 2020-10-19T00:00:00Z
description = "How fast can we validate a large text file of JSON?"
draft = false
tags = ["programming", "golang", "go"]
slug = "validate-json-quickly-go"
title = "Validating JSON Quickly with Go"
summary = "How fast can we validate a large text file of JSON?"
type = "post"
+++

<!-- # Validating JSON Quickly with Golang -->

I was recently working on a project that involved a large amount of data. I had a dataset of approximately __800 million__ records that I wanted to do some analytics on. The dataset was in the form of a single file consisting of JSON objects, one per line. 

In order to do my analytics I wanted to get this data into a SQLite database for easier querying. My plan was to import this gigantic file into a MongoDB instance since the data seemed to come from MongoDB originally and it can quickly parse JSON objects, then copy the data to a SQLite database using CSV as an intermediary if needed.

My first attempt was to naively use `mongoimport` on the dataset file. This failed rather quickly due to something I hadn't considered: not all of the lines in the dataset file contained valid JSON. Whoever had created this dataset had mangled the export:

```
{"_id":{"$oid":"af331c12ec52fb31d6bdd78a"},"title":+Infinity,"date_created":"","state":"complete","last_updated":"3/19/2009 04:57:02","done":1.0}
```
```
{"_id":{"$oid":"af331c12ec52fb31d68976f"},"title":"About","date_created":NaN,"state":"complete","last_updated":"3/19/2009 04:57:12","done":1.0}
```
Some records included `NaN` and `+Infinity` as values in key-value pairs which, because they are unquoted, are not valid JSON values. 

The first thing I did was use GNU `split` to chop up the dataset file into smaller, more manageable pieces. It was clear that dealing with a __138 GB__ flat file was going to be too cumbersome.

Next, I wrote a very simple Node.js script to read and parse each file line by line and echo the line number of any invalid lines.

```javascript
var currentLine = 1;

var lineReader = require('readline').createInterface({
    input: require('fs').createReadStream('sample.bson.json')
});
  
lineReader.on('line', function (line) {
    try {
        JSON.parse(line)
    } catch(ex) {
        console.log(`Line: ${currentLine}` + '\n' + line + '\n\n' + ex)
    }
    currentLine++;
    if (currentLine % 1000000 == 0) {
        console.log(`Parsed ${currentLine} lines`)
    }
});
```

After testing the script on a few "known bad" lines, I confirmed that it was working correctly. I threw one of the 30M line data files at it, but man was it slow.

```shell
$ time node validate.js big_01.json

...

real    11m41.919s
user    11m54.003s
sys     0m28.124s
```

12 minutes to crank through the 6.4GB file puts the throughput at about 9 MB/s. I should mention that the machine I'm testing on is not new by any stretch - it's a dual-core 2010 MacBook Pro with 16GB RAM and the drive this file is on is a 1TB 7200 RPM HDD. That being said, the drive is able to achieve sequential read speeds around 200MB/s according to my very un-scientific testing

```shell
$ dd if=big_01.json of=/dev/null bs=$((1024 * 1024))
6570+1 records in
6570+1 records out
6889608071 bytes transferred in 31.837189 secs (216401268 bytes/sec)
```

That is to say: disk speed is not the bottleneck with this script. While running the script, I was also watching updates from `htop` and could see it was consuming about 75% CPU usage, which means one core was almost completely utilized. That's when I remembered that Node is single-threaded. 🤦

This seemed like a good excuse for me to sharpen my Golang skills, which I would say hover around 'novice' currently. After doing some searching, I found an excellent example of what I was trying to do [on StackOverflow](https://stackoverflow.com/a/22129435). Using this as the base, I added file reading logic and came up with the following simple code:

```golang
package main

import (
	"bufio"
	"encoding/json"
	"flag"
	"fmt"
	"log"
	"os"
)

func isJSON(s string) bool {
    var js map[string]interface{}
    return json.Unmarshal([]byte(s), &js) == nil
}

func main() {
	fptr := flag.String("file", "test.txt", "file path to read from")
	uptr := flag.Int("update", 1000000, "How many lines should be checked before printing an update")
    flag.Parse()

    f, err := os.Open(*fptr)
    if err != nil {
        log.Fatal(err)
    }
    defer func() {
        if err = f.Close(); err != nil {
        	log.Fatal(err)
    	}
	}()
	
	s := bufio.NewScanner(f)

	lineNumber := 1
    for s.Scan() {
		if isJSON(s.Text()) == false {
			fmt.Printf("Error: line %v\n", lineNumber)
		}
		lineNumber++
		if lineNumber % *uptr == 0 {
			fmt.Printf("%v lines processed\n", lineNumber)
		}
    }
    if s.Err() != nil {
        log.Fatal(err)
    }
}
```

This seemed to validate JSON accurately, so I compiled and ran it against the same JSON file as before as a benchmark.

```shell
$ time ./first_try.bin -file big_01.json

real    9m30.545s
```

Nice, we've trimmed off 2 whole minutes! This is a very nice improvement, but I had a feeling we could do even better.

I looked around for an alternative JSON parsing library and came across [valyala/fastjson](https://github.com/valyala/fastjson). This was very easy to drop into my script - here are the new relevant lines:

```golang
s := bufio.NewScanner(f)
var p fastjson.Parser

lineNumber := 1
for s.Scan() {
	_, err := p.Parse(s.Text())
	if err != nil {
		fmt.Printf("Error: line %v\n", lineNumber)
	}
	lineNumber++
```

Running against the same file, I was shocked at the new speed:

```shell
$ time ./fastjson.bin -file big_01.json`

real    1m7.910s
```

__The new version completed in just over 1 minute.__ That's a speedup of 10x!

I was incredibly pleased with these results, but wanted to see if I could squeeze any more performance out of Go. I saw in the documentation that the fastjson `Parser` has a `ParseBytes` method that takes an array of bytes instead of a string. This is good because if I just pass a byte array from the Scanner to the Parser, I could skip the Scanner `.Text()` memory allocation. 

The relevant section of the for-loop now looked like this:

```golang
for s.Scan() {
	_, err := p.ParseBytes(s.Bytes())
	if err != nil {
		fmt.Printf("Error: line %v\n", lineNumber)
	}
```

This resulted in even better time:

```shell
$ time ./fastjson_bytes.bin -file big_01.json

real	0m57.196s
```

Parsing bytes instead of a string improved our time by 15%!

It was at this point that I started trying to feed the validated dataset chunks into MongoDB, but got a number of "invalid JSON" type errors. I re-ran the original Node version and compared the output to the output of the latest Go version. The Node version showed dozens of problem files while the Go version showed only 1-2. 

Doing some digging into the problem, it looked like my mistake was in mixing up the term "validate" with "parse". After switching to the `fastjson.ValidateBytes()` method, the Go version now validated the files properly and the output matched that of the Node version. 

The switch to `ValidateBytes()`	did add about 23 seconds to our overall time, but the most important feature of this tool is that it must validate accurately.

```shell
time ./fastjson_validatebytes.bin -file big_01.json

real    1m23.334s
```

## Lessons Learned

Performance tuning is fun, but make sure you don't lose sight of your goal.

## Further Improvements

I'm sure I could have taken this optimization project much further. One idea was to take advantage of Go's multi-threaded ability by reading in large chunks of the file at a time and validating multiple chunks simultaneously by spinning up a thread for each chunk. If you have an example of doing this or have other ideas on how I could have improved this further, please do reach out. My contact info can be found [here](/contact).
