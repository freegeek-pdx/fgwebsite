#!/bin/sh

REMOTE=oahu
LOCAL=press

set -e

HOSTNAME=$(hostname)

if [ "$HOSTNAME" = "$REMOTE" -o "$HOSTNAME" = "$LOCAL" ]; then
    echo "Run this on your own machine, it only works internally"
    exit 1
fi

ssh "$REMOTE" /usr/share/wordpress/wp-content/db.sh dump file.sql
scp "${REMOTE}:file.sql" ./
scp file.sql "${LOCAL}:"
ssh "$LOCAL" /usr/share/wordpress/wp-content/db.sh load file.sql
