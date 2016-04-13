#!/bin/sh

mysql --host=localhost --compress -u root -e "create database if not exists mantis;"
