#!/usr/bin/env bash


watchDir=/var/data
regex=".analyze"

inotifywait -r -m "$watchDir" --format '%w%f' -e close_write |
    while read file; do
        if [[ $file =~ $regex ]] ; then
            curl -X GET http://uihack/analyze/1
        fi
    done
