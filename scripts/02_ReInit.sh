#!/bin/sh

PARAMS="--host=localhost --compress -u root"

mysql $PARAMS -e "drop database if exists mantis;"
mysql $PARAMS -e "create database if not exists mantis;"

mysql $PARAMS -e "grant all privileges on mantis.* to mantis@127.0.0.1 identified by 'mantis' with grant option;"
mysql $PARAMS -e "grant all privileges on mantis.* to mantis@localhost identified by 'mantis' with grant option;"
mysql $PARAMS -e "grant all privileges on mantis.* to mantis@'%'       identified by 'mantis' with grant option;"

