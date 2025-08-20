CREATE TABLE `tblRoute` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `from` VARCHAR(100) NULL,
  `to` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `from_UNIQUE` (`from` ASC));

ALTER TABLE `tblCategory` 
ADD COLUMN `is_routed` TINYINT NULL DEFAULT 0 AFTER `meta_description`;

ALTER TABLE `tblOffer` 
ADD COLUMN `is_routed` TINYINT NULL DEFAULT 0 AFTER `meta_description`;

ALTER TABLE `tblPCategory` 
ADD COLUMN `is_routed` TINYINT NULL DEFAULT 0 AFTER `meta_description`;

