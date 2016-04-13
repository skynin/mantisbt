#!/bin/sh

HOST=localhost

DT=$(date +"%Y-%m-%d_%H-%M-%S")

echo $DT

mysqldump --host=$HOST --compress --user=mantis --password=mantis mantis > mantis.dmp

#zip -r mantis_"$DT".zip mantis.dmp