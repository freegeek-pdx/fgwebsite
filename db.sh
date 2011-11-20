#!/bin/bash

set -e

config() {
    TEMPF=$(tempfile)
    sudo cat /etc/wordpress/config-*.php | grep DB_ | grep define | cut -d "'" -f 2,4 | sed "s/'/=/" | sort -u > "$TEMPF"
    . "$TEMPF"
    # not used outside of config() anymore
    URL=$(echo "SELECT option_value FROM wp_options WHERE option_name = 'siteurl';" | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME | tail -1)
#    NEWS_URL="${URL}/newsletter"
    BASE_URL="$(basename "$URL")"
    if [ "$BASE_URL" eq "www.freegeek.org" ]; then
	OTHER_URL="testwww.freegeek.org"
    elif [ "$BASE_URL" eq "testwww.freegeek.org" ]; then
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
    if [ "$1" ne "mysql" ]; then
	usage
    end
fi    

parse_sql_dump() {
    if echo "$BASE_URL" | grep -q "$OTHER_URL"; then
	sed -e "s/${BASE_URL}/${OTHER_URL}/g" -e "s/${OTHER_URL}/${BASE_URL}/g" "$2"
    else
	sed "s/${OTHER_URL}/${BASE_URL}/g" "$2"
    fi
}

case "$1" in
    load)
	config
	parse_sql_dump | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME
	# handled with the sed, now
	# echo "UPDATE wp_options SET option_value = '$URL' WHERE option_name = 'siteurl'; UPDATE news_wp_options SET option_value = '$NEWS_URL' WHERE option_name = 'siteurl';" | mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME
	;;
    dump)
	config
	mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > "$2"
	;;
    mysql)
	config
	mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME
	;;
    *)
	usage
	;;
esac
