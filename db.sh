#!/bin/bash

set -e

# TODO: rsync or otherwise combine the uploaded files and then fix the FIXMEs below
# they are saved in /usr/share/wordpress/wp-content/uploads

config() {
    TEMPF=$(tempfile)
    sudo cat /etc/wordpress/config-*.php | grep DB_ | grep define | cut -d "'" -f 2,4 | sed "s/'/=/" | sort -u > "$TEMPF"
    . "$TEMPF"
    # not used outside of config() anymore
    URL=$(echo "SELECT option_value FROM wp_options WHERE option_name = 'siteurl';" | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME | tail -1)
    NEWS_URL="${URL}/newsletter"
    BASE_URL="$(basename "$URL")"
    if [ "$BASE_URL" = "www.freegeek.org" ]; then
	OTHER_URL="testwww.freegeek.org"
    elif [ "$BASE_URL" = "testwww.freegeek.org" ]; then
	OTHER_URL="www.freegeek.org"
    else
	echo "ERROR: Unknown BASE_URL: $BASE_URL"
	exit 1
    fi
    cat "$TEMPF"
    echo "BASE_URL=$BASE_URL"
    echo
    rm "$TEMPF"
    set -x
}

usage() {
    echo "Usage: $(basename $0) dump|load file.sql"
    exit 1
}

if [ -z "$1" -o -z "$2" ]; then
    if [ "$1" != "mysql" ]; then
	usage
    fi
fi    

parse_sql_dump() {
    cat "$1"   # FIXME
    return     # FIXME
    if echo "$BASE_URL" | grep -q "$OTHER_URL"; then
	sed -e "s/${BASE_URL}/${OTHER_URL}/g" -e "s/${OTHER_URL}/${BASE_URL}/g" "$1"
    else
	sed "s/${OTHER_URL}/${BASE_URL}/g" "$1"
    fi
}

do_mysql() {
    MYSQL="mysql"
    if [ -n "$1" ]; then
	MYSQL="$1"
    fi
    $MYSQL -u $DB_USER -p$DB_PASSWORD $DB_NAME
}

case "$1" in
    load)
	config
	parse_sql_dump "$2" | do_mysql
	echo "UPDATE wp_options SET option_value = '$URL' WHERE option_name = 'siteurl'; UPDATE news_wp_options SET option_value = '$NEWS_URL' WHERE option_name = 'siteurl';" | do_mysql # FIXME: handled with the sed, now
	;;
    dump)
	config
	do_mysql mysqldump > "$2"
	;;
    mysql)
	config
	do_mysql
	;;
    *)
	usage
	;;
esac
