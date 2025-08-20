ALTER TABLE  `tblUser`
    ADD  `is_deleted` TINYINT NOT NULL DEFAULT  '0'
    ADD  `group` INT NOT NULL 
    ADD  `key` VARCHAR( 55 ) NOT NULL 
    ADD  `tel` VARCHAR( 24 ) NOT NULL ;
    ADD UNIQUE (`email`);