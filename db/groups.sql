ALTER TABLE `tblUser` ADD `user_id` INT NOT NULL AFTER `tel`;

DROP TABLE IF EXISTS `tblGroup`;
CREATE TABLE `tblGroup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `role` enum('admin','user','guest') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
ALTER TABLE `tblGroup`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tblGroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


INSERT INTO `tblGroup` (`name`, `comment`, `user_id`, `status`, `role`) VALUES
('Администраторы', '', 1, 1, 'admin'),
('Пользователи', '', 1, 1, 'admin');
