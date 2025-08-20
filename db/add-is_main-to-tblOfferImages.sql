ALTER TABLE `tblOfferImages` ADD `is_main` TINYINT NOT NULL DEFAULT '0' AFTER `ord`;
SELECT GROUP_CONCAT(`id` SEPARATOR ',') FROM `tblOfferImages` WHERE `id` IN (SELECT min(`id`) FROM `tblOfferImages` GROUP BY `offer_id`);
UPDATE `tblOfferImages` SET `is_main` = 1 WHERE `id` IN (

1,3,5,7,8,9,10,11,14,17,18,19,20,59,60,62,63,64,65,66,67,68,69,70,71,72


)


ALTER TABLE `tblOrder` ADD `status` ENUM('unpaid','paid','in progress','done') NULL DEFAULT NULL AFTER `comment`;