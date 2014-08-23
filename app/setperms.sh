#!/bin/sh

BASE="$( cd "$( dirname "$0" )" && pwd )"
USER=www

rm -rf $BASE/cache/*
rm -rf $BASE/logs/*

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:"$USER":rwX $BASE/cache $BASE/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:"$USER":rwX $BASE/cache $BASE/logs
