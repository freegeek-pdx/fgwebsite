#!/bin/sh

set -e 
DIR=~/rsync_uploads/
if [ ! -d $DIR ]; then
    mkdir $DIR
fi
cd $DIR

WWW=oahu.freegeek.org
TEST=press

rsync -atzv $WWW:/usr/share/wordpress/wp-content/uploads/ www/
rsync -atzv $TEST:/usr/share/wordpress/wp-content/uploads/ testwww/
rsync -rutv www/ synced/
rsync -rutv testwww/ synced/
rsync -atzv synced/ testwww/
rsync -atzv synced/ www/
rsync -atzv synced/ $TEST:/usr/share/wordpress/wp-content/uploads/
rsync -atzv synced/ $WWW:/usr/share/wordpress/wp-content/uploads/
