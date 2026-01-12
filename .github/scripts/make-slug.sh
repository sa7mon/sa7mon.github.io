#!/usr/bin/env bash

# $# - number of arguments = flags * 2
if [ "$#" -ne 4 ]; then
    echo "Usage: $0 -t <title> -d <date>"
    echo "       date format: YYYY-MM-DD"
    exit 1
fi

while getopts t:d: flag
do
    case "${flag}" in
        t) title=${OPTARG};;
        d) date=${OPTARG};;
    esac
done

export slug=$(echo "$title" | tr '[:upper:]' '[:lower:]' | sed 's/ /-/g' | sed 's/[^a-z0-9-]//g')
export FILE_SLUG="$(date +'%Y-%m-%d')_${slug}_$date"
echo "$FILE_SLUG"