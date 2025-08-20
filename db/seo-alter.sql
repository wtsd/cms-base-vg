ALTER TABLE `tblArticle` 
ADD COLUMN `h1` TEXT NULL AFTER `with_images`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

ALTER TABLE `tblCategory` 
ADD COLUMN `h1` TEXT NULL AFTER `gallery_id`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`/*,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`*/;

ALTER TABLE `tblGallery` 
ADD COLUMN `h1` TEXT NULL AFTER `gal_id`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

ALTER TABLE `tblImage` 
ADD COLUMN `h1` TEXT NULL AFTER `gal_id`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

ALTER TABLE `tblOffer` 
ADD COLUMN `h1` TEXT NULL AFTER `is_recommended`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

ALTER TABLE `tblPCategory` 
ADD COLUMN `h1` TEXT NULL AFTER `post_desc`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

ALTER TABLE `tblVendor` 
ADD COLUMN `h1` TEXT NULL AFTER `site`,
ADD COLUMN `h2` TEXT NULL AFTER `h1`,
ADD COLUMN `meta_keywords` TEXT NULL AFTER `h2`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_keywords`,
ADD COLUMN `title` TEXT NULL AFTER `meta_description`;

