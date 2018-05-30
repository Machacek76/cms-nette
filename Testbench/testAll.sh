#/bin/bash

echo "##############################################\n"

echo "> TEST START\n"


echo ">> TEST HomepagePresenterTest.php"

php HomepagePresenterTest.php

echo "\n###############################################\n"

echo ">> TEST AdminAclPresenterTest.php"
php AdminAclPresenterTest.php

echo ">> TEST AdminSignPresenterTest.php"

php  AdminSignPresenterTest.php


echo "\n##############################################\n"

echo ">> TEST ApiUserPresenterTester.php"

php ApiUserPresenterTester.php


echo "\n##############################################\n"
echo "> TEST END"
