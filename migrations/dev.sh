#!/bin/bash

# sh migrations/prod.sh
# ! ATTENTION: il faut avoir la ligne APP_ENV=dev avant de lancer cette commande

RED='\033[0;31m'; # red color
NC='\033[0m'; # No Color

echo "${RED}don't forget add APP_ENV=dev to your .env.local... continue ? [yes/no] (default no)\n${NC}";
read REPLY;
if [ "$REPLY" != "yes" ]; then
   exit
fi

rm -f .env.local.php

rm -f vendor/;

composer install;

composer update ;
