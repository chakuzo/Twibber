CREATE TABLE twibber_entry (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
to_id INT NULL DEFAULT NULL ,
nickname VARCHAR( 40 ) NOT NULL ,
text TEXT NOT NULL ,
date VARCHAR( 50 ) NOT NULL
)
