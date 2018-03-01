#!/bin/sh
fname=`date +"%Y.%m.%d-%H.%M.%S"`
echo logs will be saved in logs/$fname.log
php -c . -S 0.0.0.0:8787 -t doc_root 2> logs/errors-$fname.log  &
