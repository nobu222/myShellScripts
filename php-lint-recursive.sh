#!/bin/sh

echo "PHP Version: $(php -r 'echo PHP_VERSION;')"

if [ $# -eq 0 ]
then
    scan_dir=$(pwd)
else
    scan_dir=$1
fi

files=$(find $scan_dir -name "*.php")
total=0
errors=0

for file in $files
do
    OK=0
    result=$(php -l $file) && OK=1

    if [ $OK -eq 0 ]
    then
        echo "$result"
        errors=$(expr $errors + 1)
    fi

    total=$(expr $total + 1)
done

success=$(expr $total - $errors)

echo "\n$total files. $success ok. $errors errors."

if [ $errors -gt 0 ]
then
    exit 1
fi
