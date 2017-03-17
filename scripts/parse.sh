#!/usr/bin/env bash


watchDir=/var/data
regex=".json"

inotifywait -r -m "$watchDir" --format '%w%f' -e close_write |
    while read file; do
        if [[ $file =~ $regex ]] ; then

            curl http://uihack/parse/1
        fi
    done
