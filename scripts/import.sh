HOST=localhost

mysql --host=$HOST --compress --user=mantis --password=mantis --database=mantis < mantis.dmp
