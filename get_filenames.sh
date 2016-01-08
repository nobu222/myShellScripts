#!/bin/sh

find . -type f | while read FILE
do
    echo ${FILE}
done
