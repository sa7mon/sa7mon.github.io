#!/usr/bin/env bash

# $# - number of arguments = flags * 2
if [ "$#" -ne 12 ]; then
    echo "Usage: $0 -i <title> -s <slug> -u <url> -t <tags> -d <description> -f <format>"
    exit 1
fi

while getopts i:s:u:t:d:f: flag
do
    case "${flag}" in
        i) title=${OPTARG};;
        s) slug=${OPTARG};;
        u) url=${OPTARG};;
        t) tags=${OPTARG};;
        d) desc=${OPTARG};;
        f) format=${OPTARG};;
    esac
done

echo "args:"
for i; do 
   echo $i 
done

hugo new links/$slug.md
export FILE="content/links/$slug.md"
export SITE=$(echo $url | awk -F/ '{print $3}')
sed -i -E "s/title: .+$/title: \"$title\"/" $FILE
sed -i -E "s/site: $/site: $SITE/" $FILE
sed -i -E "s/link_tags: .+$/link_tags: $tags/" $FILE
sed -i -E "s/formats: .+$/formats: $format/" $FILE
printf "\n$url\n$desc" >> $FILE
cat $FILE