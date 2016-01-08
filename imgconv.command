#!/bin/sh

walk(){
   for file in $1/*.$2; do
			 newfile=`echo $file | sed "s/${2}/${3}/"`
			 convert "$file" "$newfile"
    done
}
 
echo -n "select type( "From" "To" ) : "
read $2 $3
$1=`dirname $0`
echo $1 $2 $3
walk $1 $2 $3
