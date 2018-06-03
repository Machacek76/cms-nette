#/bin/bash

rm -r ./_temp/cache/*
rm -r ./../temp/_glCache/*

echo "##############################################\n"

echo "> TEST START\n"


echo ">> TEST HomepagePresenterTest.php"

php HomepagePresenterTest.php

echo "\n###############################################\n"

echo ">> TEST AdminAclPresenterTest.php"
php AdminAclPresenterTest.php

echo ">> TEST AdminSignPresenterTest.php"

php  AdminSignPresenterTest.php

echo ">> TEST AdminUserPresenterTest.php"

php  AdminUserPresenterTest.php


echo "\n##############################################\n"

echo ">> TEST ApiUserPresenterTester.php"

php ApiUserPresenterTester.php


echo "\n##############################################\n"
echo "> TEST END"
