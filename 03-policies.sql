CREATE USER 'admin'@'localhost' IDENTIFIED BY 'A!dm1n';
CREATE USER 'analyst'@'localhost' IDENTIFIED BY 'An@lyt1c';
CREATE USER 'guest'@'localhost' IDENTIFIED BY 'Gu3st!';

GRANT ALL PRIVILEGES ON demo.* TO 'admin'@'localhost';
GRANT SELECT ON demo.* TO 'analyst'@'localhost';
GRANT SELECT ON demo.items TO 'guest'@'localhost';  

FLUSH PRIVILEGES;