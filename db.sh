#!/bin/bash

set -e

config() {
    TEMPF=$(tempfile)
    sudo cat /etc/wordpress/config-*.php | grep DB_ | grep define | cut -d "'" -f 2,4 | sed "s/'/=/" | sort -u > "$TEMPF"
    . "$TEMPF"
    URL=$(echo "SELECT option_value FROM wp_options WHERE option_name = 'siteurl';" | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME | tail -1)
    cat "$TEMPF"
    echo "URL=$URL"
    echo
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
	echo "UPDATE wp_options SET option_value = '$URL' WHERE option_name = 'siteurl';" | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME
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
