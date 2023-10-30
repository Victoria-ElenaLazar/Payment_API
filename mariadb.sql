CREATE USER IF NOT EXISTS root@localhost IDENTIFIED BY 'root';
SET PASSWORD FOR root@localhost = PASSWORD ('root');
GRANT ALL ON *.* TO root@localhost WITH GRANT OPTION ;

CREATE USER IF NOT EXISTS root@'%' IDENTIFIED BY 'root';
SET PASSWORD FOR root@'%' = PASSWORD ('root');
GRANT ALL ON *.* TO root@'%' WITH GRANT OPTION ;

CREATE USER IF NOT EXISTS paymentuser@'%' IDENTIFIED BY 'root';
SET PASSWORD FOR paymentuser@'%' = PASSWORD ('root');
CREATE DATABASE IF NOT EXISTS payment_api;
GRANT ALL ON payment_api.* TO paymentuser@'%';
FLUSH PRIVILEGES;