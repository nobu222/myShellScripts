#!/bin/sh
# 依存コマンド
# node.js
# casperjs
# phantomjs
# imgmagick

# 手順
# host.txtにキャプチャしたいURLを入力
# casper.commandをクリックで実行

cd `dirname $0`

pbpaste > ./host.txt

count=1
cat './host.txt' | grep '^[https?]' | while read line
do
  sh cap.sh ${line} ${count}
	# casperjs cap.js --hostname=${line} --count=${count}
	sh crop.sh ${HOME}/Desktop/${count}-full
	count=$((count+1))
done

convert ${HOME}/Desktop/*-full-*.jpg ${HOME}/Desktop/capture.pdf
# mv ./capture.pdf ${HOME}/Desktop/

rm ${HOME}/Desktop/*-full*.jpg
rm ${HOME}/Desktop/*-full*.png
