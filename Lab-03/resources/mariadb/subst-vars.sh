#!/bin/sh

sed -e "s/\${DB_NAME}/$DB_NAME/" \
    -e "s/\${DB_USERNAME}/$DB_USERNAME/" \
    -e "s/\${DB_PASSWORD}/$DB_PASSWORD/" \
    /tmp/init-db.sql.template > /tmp/init-db.sql

mariadb --user="root" --password="$MARIADB_ROOT_PASSWORD" < /tmp/init-db.sql
