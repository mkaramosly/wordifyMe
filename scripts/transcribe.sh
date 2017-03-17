#!/usr/bin/env bash


watchDir=/var/data
regex=".wav"

inotifywait -r -m "$watchDir" --format '%w%f' -e close_write |
    while read file; do
        if [[ $file =~ $regex ]] ; then
            curl -X GET http://uihack/transcribe/1
        fi
    done
