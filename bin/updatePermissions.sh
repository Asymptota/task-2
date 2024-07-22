#!/usr/bin/env bash

HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

echo "Konfiguracja uprawnień"
echo "    Użytkownik apache: $HTTPDUSER"
echo "    Użytkownik aktualny: `whoami`"

for DIRECTORY in "var/log" "var/cache"
do
    if [ -d "$DIRECTORY" ]; then
        echo "    Ustawianie uprawnień dla: $DIRECTORY"
        setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX  $DIRECTORY
        setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX $DIRECTORY
        chmod -R 777 $DIRECTORY
    fi
done

echo "DONE"

