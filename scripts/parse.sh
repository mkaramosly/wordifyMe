#!/usr/bin/env bash


watchDir=/var/data
regex=".json"

inotifywait -r -m "$watchDir" --format '%w%f' -e close_write |
    while read file; do
        if [[ $file =~ $regex ]] ; then
            file=`basename $file`
            IFS='.' read -r -a array <<< "$file"

            curl -X GET http://uihack/parser/"${array[0]}"
        fi
    done
