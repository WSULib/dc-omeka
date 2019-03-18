CREATE DATABASE {{mysql_database}} CHARACTER SET utf8 COLLATE utf8_general_ci;;
CREATE USER '{{mysql_username}}'@'localhost' IDENTIFIED BY '{{mysql_password}}';
GRANT ALL PRIVILEGES ON * . * TO '{{mysql_username}}'@'localhost';
FLUSH PRIVILEGES;