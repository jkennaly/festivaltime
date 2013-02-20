#! /bin/bash
# username is 1
# pw is 2
# db name is 3
# db host is 4

mysqldump -h $4 -u $1 -p$2 --add-drop-table --no-data $3 | grep ^DROP | mysql -h $4 -u $1 -p$2 $3
