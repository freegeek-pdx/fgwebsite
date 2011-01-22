#!/bin/bash

set -e

config() {
    TEMPF=$(tempfile)
    sudo cat /etc/wordpress/config-*.php | grep DB_ | grep define | cut -d "'" -f 2,4 | sed "s/'/=/" | sort -u > "$TEMPF"
    cat "$TEMPF"
    echo
    . "$TEMPF"
    rm "$TEMPF"
}

usage() {
    echo "Usage: $(basename $0) dump|load file.sql"
    exit 1
}

if [ -z "$1" -o -z "$2" ]; then
    usage
fi    

case "$1" in
    load)
	config
	set -x
	mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME < "$2"
	;;
    dump)
	config
	set -x
	mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > "$2"
	;;
    *)
	usage
	;;
esac
