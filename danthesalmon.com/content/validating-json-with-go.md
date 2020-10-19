+++
author = "Dan Salmon"
date = 2020-10-19T00:00:00Z
description = "How fast can we validate a large text file of JSON?"
draft = true
tags = ["programming", "golang", "go"]
slug = "validate-json-quickly-go"
title = "Validating JSON Quickly with Go"
summary = "How fast can we validate a large text file of JSON?"
type = "post"
+++

# Validating JSON Quickly with Golang

Recently, I was working on a project that involved a large amount of data. Like a lot. I had a dataset of approximately __800 million__ records that I wanted to do some analytics on. The dataset was in the form of a single file consisting of JSON objects, one per line. 

In order to do my analytics I wanted to get this data into a SQLite database for easier querying. My plan was to import this gigantic file into a MongoDB instance since it can quickly parse JSON objects, then export to CSV as an intermediate, and finally import into SQLite. 

My first attempt was to naively use `mongoimport` on the dataset file. This failed rather quickly due to something I hand't considered: not all of the lines in the datset file container valid JSON. Whoever had created this dataset had mangled the export and some of the JSON objects contained Javascript artifacts.

Example:

```
{"_id":{"$oid":"af331c12ec52fb31d6bdd78a"},"title":+Infinity,"date_created":"","state":"complete","last_updated":"3/19/2009 04:57:02","done":1.0}
```
```
{"_id":{"$oid":"af331c12ec52fb31d68976f"},"title":"About","date_created":NaN,"state":"complete","last_updated":"3/19/2009 04:57:12","done":1.0}
```

As you can see from these examples, some records included `NaN` and `+Infinity` as values in key-value pairs which, because they are unquoted, are not valid JSON values. 

The next thing I did to make this process easier was to use GNU split to chop up the dataset file into smaller, more manageable pieces. It was clear that dealing with a __138 GB__ flat file was going to be too cumbersome.

Next, I wrote a very simple Node.js script to read each file line-by-line and attempt to parse the JSON on each line. The script is very simple, the only work is done by `JSON.parse()`:

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

After testing the script on a few "known bad" lines, I confirmed that it was working correctly. Grabbing one of the dataset chunk files with around 30 million lines, I threw the script it. It chugged through the whole thing, but man was it slow:

```
$ time node validate.js big_01.json

(output trimmed)

real    11m41.919s
user    11m54.003s
sys     0m28.124s
```

Almost 12 minutes to crank through the 6.4GB file - that's about 9 MB/s. I should mention that the machine I'm testing on is not new by any stretch - it's a dual-core 2010 MacBook Pro with 16GB RAM and the drive this file is on is a 1TB 7200 RPM HDD. That being said, the drive is able to achieve sequential read speeds around 200MB/s according to my very un-scientific test:

```
$ dd if=big_01.json of=/dev/null bs=$((1024 * 1024))
6570+1 records in
6570+1 records out
6889608071 bytes transferred in 31.837189 secs (216401268 bytes/sec)
```

All of that is to say that disk speed is not the bottlneck with this script. While running the script, I was also watching updates from `htop`. I could see that the script was hovering around 75% CPU usage, which means one core was almost completely utilized. That's when I remembered that Node is single-threaded. 

In order to achieve faster parsing speed, I figured I'd need to use a language other than Node. This seemed like a good excuse for me to sharpen my Golang skills. I am currently very much a Go novice, but I like to take any opportunity to learn more about it through examples.

After doing some Googling, I found an excellent example of what I was trying to do [on StackOverflow](https://stackoverflow.com/a/22129435). Using this as the base, I added file reading logic and came up with the following simple code:

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
    err = s.Err()
    if err != nil {
        log.Fatal(err)
    }
}
```

This seemed to validate JSON accurately, so I compiled and ran it against the same JSON file as before as a benchmark.

```
$ time ./first_try.bin -file ../big.testjson

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

After re-compiling and running the new binary against the test file, I was frankly shocked at the new speed:

```
$ time ./fastjson.bin -file ../big.testjson`

real    1m7.910s
```

__The new version completed in just over 1 minute.__ That's a speedup of 10x!

I was incredibly pleased with these results, but wanted to see if I could squeeze any more performance out of Go. I saw in the documentation that the fastjson `Parser` has a `ParseBytes` method that takes an array of bytes instead of a string. This is good because if I just pass a byte array from the Scanner to the Parser, I could skip the Scanner `.Text()` memory allocation. 

The relevant secion of the for-loop now looked like this:

```golang
for s.Scan() {
	_, err := p.ParseBytes(s.Bytes())
	if err != nil {
		fmt.Printf("Error: line %v\n", lineNumber)
	}
```

Re-compiling and re-running against the test file resulted in even better time:

```
$ time ./fastjson_bytes.bin -file ../big.testjson
	real	0m57.196s
```

Parsing bytes instead of a string improved our time by 15%. 

It was at this point that I started trying to feed the validated dataset chunks into MongoDB, but got a number of "invalid JSON" type errors. I re-ran the original Node version and compared the output to the output of the latest Go version. The Node version showed dozens of problem files while the Go version showed only 1-2. 

Doing some digging into the problem, it looked like my mistake was in mixing up the term "validate" with "parse". After switching to the `fastjson.ValidateBytes()` method, the Go version now validated the files properly and the output matched that of the Node version. 

The switch to `ValidateBytes()`	did add about 23 seconds to our overall time, but the most important feature of this tool is that it must validate accurately.

```
time ./fastjson_validatebytes.bin -file ../big.testjson
	real    1m23.334s
```

## Further Improvements

I'm sure I could have taken this optimization project much further. One idea was to take advantage of Go's multi-threaded ability by reading in large chunks of the file at a time and validating multiple chunks simultaneously by spinng up a thread for each chunk. If you have an example of doing this or have other ideas on how I could have improved this further, please do reach out. My contact info can be found [here](/contact).
