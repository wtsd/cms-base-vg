SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_basetest`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblArticle`
--

DROP TABLE IF EXISTS `tblArticle`;
CREATE TABLE `tblArticle` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `lead` text NOT NULL,
  `f_text` text NOT NULL,
  `cdate` timestamp NULL DEFAULT NULL,
  `mdate` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(4) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `tags` text NOT NULL,
  `url` varchar(127) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ord` tinyint(4) NOT NULL,
  `is_commented` tinyint(4) NOT NULL DEFAULT '0',
  `rewrite` varchar(63) NOT NULL,
  `with_images` tinyint(4) NOT NULL DEFAULT '0',
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `title` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblArticleAttachment`
--

DROP TABLE IF EXISTS `tblArticleAttachment`;
CREATE TABLE `tblArticleAttachment` (
  `id` int(11) NOT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `art_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblArticleImages`
--

DROP TABLE IF EXISTS `tblArticleImages`;
CREATE TABLE `tblArticleImages` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `art_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblArticleStat`
--

DROP TABLE IF EXISTS `tblArticleStat`;
CREATE TABLE `tblArticleStat` (
  `article_id` int(11) NOT NULL,
  `views` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `dislikes` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblArticleTag`
--

DROP TABLE IF EXISTS `tblArticleTag`;
CREATE TABLE `tblArticleTag` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `cdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblBranch`
--

DROP TABLE IF EXISTS `tblBranch`;
CREATE TABLE `tblBranch` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `tel` varchar(25) NOT NULL,
  `opens_at` time NOT NULL,
  `closes_at` time NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_public` tinyint(4) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  `comment` text NOT NULL,
  `city` int(11) NOT NULL,
  `country` int(11) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `contact` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblCallRequest`
--

DROP TABLE IF EXISTS `tblCallRequest`;
CREATE TABLE `tblCallRequest` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `tel` varchar(45) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblCategory`
--

DROP TABLE IF EXISTS `tblCategory`;
CREATE TABLE `tblCategory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lead` text NOT NULL,
  `f_text` text NOT NULL,
  `cdate` timestamp NULL DEFAULT NULL,
  `mdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(4) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `tags` text NOT NULL,
  `url` varchar(127) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ord` tinyint(4) NOT NULL,
  `rewrite` varchar(63) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `is_routed` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblCity`
--

DROP TABLE IF EXISTS `tblCity`;
CREATE TABLE `tblCity` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblComments`
--

DROP TABLE IF EXISTS `tblComments`;
CREATE TABLE `tblComments` (
  `id` int(11) NOT NULL,
  `comment` text,
  `name` varchar(45) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `fid` int(11) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('offer','article') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblCountry`
--

DROP TABLE IF EXISTS `tblCountry`;
CREATE TABLE `tblCountry` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblFeedback`
--

DROP TABLE IF EXISTS `tblFeedback`;
CREATE TABLE `tblFeedback` (
  `id` int(11) NOT NULL,
  `ip` varchar(127) NOT NULL,
  `title` varchar(127) NOT NULL,
  `body` text NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mdate` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `recipient` varchar(127) NOT NULL,
  `sender` varchar(127) NOT NULL,
  `comment` text NOT NULL,
  `additional` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblGallery`
--

DROP TABLE IF EXISTS `tblGallery`;
CREATE TABLE `tblGallery` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rewrite` varchar(63) NOT NULL,
  `lead` text NOT NULL,
  `tags` text NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(4) NOT NULL,
  `ord` tinyint(4) NOT NULL,
  `gal_id` int(11) NOT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `title` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblGroup`
--

DROP TABLE IF EXISTS `tblGroup`;
CREATE TABLE `tblGroup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `role` enum('admin','user','guest') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblImage`
--

DROP TABLE IF EXISTS `tblImage`;
CREATE TABLE `tblImage` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `descr` text NOT NULL,
  `tags` text NOT NULL,
  `fname` varchar(255) NOT NULL,
  `cdate` timestamp NULL DEFAULT NULL,
  `gal_id` int(11) NOT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `title` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblOffer`
--

DROP TABLE IF EXISTS `tblOffer`;
CREATE TABLE `tblOffer` (
  `id` int(11) NOT NULL,
  `pcat_id` int(11) NOT NULL,
  `name` varchar(127) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `descr` text NOT NULL,
  `is_special` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rewrite` varchar(127) NOT NULL,
  `ord` int(11) NOT NULL,
  `price` float NOT NULL,
  `price_2` float NOT NULL,
  `price_3` float NOT NULL,
  `is_recommended` tinyint(4) NOT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `title` text,
  `is_available` tinyint(4) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblOfferImages`
--

DROP TABLE IF EXISTS `tblOfferImages`;
CREATE TABLE `tblOfferImages` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `fname` varchar(63) NOT NULL,
  `descr` text,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  `ord` tinyint(4) NOT NULL,
  `is_main` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblOfferPcat`
--

DROP TABLE IF EXISTS `tblOfferPcat`;
CREATE TABLE `tblOfferPcat` (
  `id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `pcat_id` int(11) DEFAULT NULL,
  `mdate` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblOrder`
--

DROP TABLE IF EXISTS `tblOrder`;
CREATE TABLE `tblOrder` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `client_type` int(11) NOT NULL DEFAULT '1',
  `email` varchar(128) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `street` varchar(128) DEFAULT NULL,
  `building` varchar(128) DEFAULT NULL,
  `corpus` varchar(128) DEFAULT NULL,
  `apartment` varchar(128) DEFAULT NULL,
  `code` varchar(128) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `sum` int(11) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(32) NOT NULL,
  `address` text,
  `comment` text,
  `status` enum('unpaid','paid','in_progress','done','deleted') DEFAULT 'unpaid',
  `int_comment` text NOT NULL,
  `delivery_cost` float NOT NULL,
  `taxes` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblOrderOffers`
--

DROP TABLE IF EXISTS `tblOrderOffers`;
CREATE TABLE `tblOrderOffers` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblPCategory`
--

DROP TABLE IF EXISTS `tblPCategory`;
CREATE TABLE `tblPCategory` (
  `id` int(11) NOT NULL,
  `name` varchar(127) NOT NULL,
  `pcat_id` int(11) DEFAULT '0',
  `rewrite` varchar(127) NOT NULL,
  `descr` text NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ord` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `post_desc` text NOT NULL,
  `photo` varchar(45) DEFAULT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `is_routed` tinyint(4) DEFAULT '0',
  `title` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblPCategoryImages`
--

DROP TABLE IF EXISTS `tblPCategoryImages`;
CREATE TABLE `tblPCategoryImages` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pcat_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblPSpec`
--

DROP TABLE IF EXISTS `tblPSpec`;
CREATE TABLE `tblPSpec` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `stype` tinyint(4) NOT NULL,
  `pcat_id` int(11) NOT NULL,
  `values` text NOT NULL,
  `defval` varchar(32) DEFAULT NULL,
  `required` tinyint(4) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  `ord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblPSpecVal`
--

DROP TABLE IF EXISTS `tblPSpecVal`;
CREATE TABLE `tblPSpecVal` (
  `id` int(11) NOT NULL,
  `pspec_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `val` text NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblRelated`
--

DROP TABLE IF EXISTS `tblRelated`;
CREATE TABLE `tblRelated` (
  `id` int(11) NOT NULL,
  `obj_id_1` int(11) DEFAULT NULL,
  `obj_id_2` int(11) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) DEFAULT NULL,
  `ctype` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblRoute`
--

DROP TABLE IF EXISTS `tblRoute`;
CREATE TABLE `tblRoute` (
  `id` int(11) NOT NULL,
  `from` varchar(100) DEFAULT NULL,
  `to` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblSlide`
--

DROP TABLE IF EXISTS `tblSlide`;
CREATE TABLE `tblSlide` (
  `id` int(11) NOT NULL,
  `slider_id` int(11) DEFAULT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `ord` tinyint(4) DEFAULT NULL,
  `h2` varchar(45) DEFAULT NULL,
  `text` text,
  `cdate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblSlider`
--

DROP TABLE IF EXISTS `tblSlider`;
CREATE TABLE `tblSlider` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `indicators` tinyint(4) DEFAULT NULL,
  `uri` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblSubscription`
--

DROP TABLE IF EXISTS `tblSubscription`;
CREATE TABLE `tblSubscription` (
  `id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `ip` varchar(31) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblTag`
--

DROP TABLE IF EXISTS `tblTag`;
CREATE TABLE `tblTag` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `cdate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblUser`
--

DROP TABLE IF EXISTS `tblUser`;
CREATE TABLE `tblUser` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `f_name` varchar(63) NOT NULL,
  `comment` text,
  `descr` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `mdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cdate` timestamp NULL DEFAULT NULL,
  `email` varchar(63) NOT NULL,
  `class` tinyint(4) NOT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL,
  `ckey` varchar(55) NOT NULL,
  `tel` varchar(24) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tblVendor`
--

DROP TABLE IF EXISTS `tblVendor`;
CREATE TABLE `tblVendor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rewrite` varchar(45) DEFAULT NULL,
  `country` int(11) NOT NULL,
  `descr` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `site` varchar(255) NOT NULL,
  `h1` text,
  `h2` text,
  `meta_keywords` text,
  `meta_description` text,
  `title` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblArticle`
--
ALTER TABLE `tblArticle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rewrite_UNIQUE` (`rewrite`);

--
-- Indexes for table `tblArticleAttachment`
--
ALTER TABLE `tblArticleAttachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fname` (`fname`,`art_id`);

--
-- Indexes for table `tblArticleImages`
--
ALTER TABLE `tblArticleImages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblArticleStat`
--
ALTER TABLE `tblArticleStat`
  ADD PRIMARY KEY (`article_id`),
  ADD UNIQUE KEY `article_id_UNIQUE` (`article_id`);

--
-- Indexes for table `tblArticleTag`
--
ALTER TABLE `tblArticleTag`
  ADD KEY `PK` (`article_id`,`tag_id`);

--
-- Indexes for table `tblBranch`
--
ALTER TABLE `tblBranch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblCallRequest`
--
ALTER TABLE `tblCallRequest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblCategory`
--
ALTER TABLE `tblCategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblCity`
--
ALTER TABLE `tblCity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblComments`
--
ALTER TABLE `tblComments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblCountry`
--
ALTER TABLE `tblCountry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblFeedback`
--
ALTER TABLE `tblFeedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblGallery`
--
ALTER TABLE `tblGallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblGroup`
--
ALTER TABLE `tblGroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblImage`
--
ALTER TABLE `tblImage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblOffer`
--
ALTER TABLE `tblOffer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rewrite` (`rewrite`);

--
-- Indexes for table `tblOfferImages`
--
ALTER TABLE `tblOfferImages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblOfferPcat`
--
ALTER TABLE `tblOfferPcat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblOrder`
--
ALTER TABLE `tblOrder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblOrderOffers`
--
ALTER TABLE `tblOrderOffers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblPCategory`
--
ALTER TABLE `tblPCategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblPCategoryImages`
--
ALTER TABLE `tblPCategoryImages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblPSpec`
--
ALTER TABLE `tblPSpec`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblPSpecVal`
--
ALTER TABLE `tblPSpecVal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pspec_id` (`pspec_id`,`offer_id`);

--
-- Indexes for table `tblRelated`
--
ALTER TABLE `tblRelated`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblRoute`
--
ALTER TABLE `tblRoute`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `from_UNIQUE` (`from`);

--
-- Indexes for table `tblSlide`
--
ALTER TABLE `tblSlide`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblSlider`
--
ALTER TABLE `tblSlider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblSubscription`
--
ALTER TABLE `tblSubscription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblTag`
--
ALTER TABLE `tblTag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `tblUser`
--
ALTER TABLE `tblUser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tblVendor`
--
ALTER TABLE `tblVendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rewrite_UNIQUE` (`rewrite`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblArticle`
--
ALTER TABLE `tblArticle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblArticleAttachment`
--
ALTER TABLE `tblArticleAttachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblArticleImages`
--
ALTER TABLE `tblArticleImages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblBranch`
--
ALTER TABLE `tblBranch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblCallRequest`
--
ALTER TABLE `tblCallRequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblCategory`
--
ALTER TABLE `tblCategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblComments`
--
ALTER TABLE `tblComments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblFeedback`
--
ALTER TABLE `tblFeedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblGallery`
--
ALTER TABLE `tblGallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblGroup`
--
ALTER TABLE `tblGroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblImage`
--
ALTER TABLE `tblImage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblOffer`
--
ALTER TABLE `tblOffer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblOfferImages`
--
ALTER TABLE `tblOfferImages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblOfferPcat`
--
ALTER TABLE `tblOfferPcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblOrder`
--
ALTER TABLE `tblOrder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblOrderOffers`
--
ALTER TABLE `tblOrderOffers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblPCategory`
--
ALTER TABLE `tblPCategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblPCategoryImages`
--
ALTER TABLE `tblPCategoryImages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblPSpec`
--
ALTER TABLE `tblPSpec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblPSpecVal`
--
ALTER TABLE `tblPSpecVal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblRelated`
--
ALTER TABLE `tblRelated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblRoute`
--
ALTER TABLE `tblRoute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblSlide`
--
ALTER TABLE `tblSlide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblSlider`
--
ALTER TABLE `tblSlider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblSubscription`
--
ALTER TABLE `tblSubscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblTag`
--
ALTER TABLE `tblTag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblUser`
--
ALTER TABLE `tblUser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tblVendor`
--
ALTER TABLE `tblVendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

