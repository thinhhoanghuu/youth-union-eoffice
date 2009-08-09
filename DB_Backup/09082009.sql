-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 09, 2009 at 08:29 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `nukeviet`
--

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_authors`
--

CREATE TABLE IF NOT EXISTS `nukeviet_authors` (
  `aid` varchar(25) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `pwd` varchar(40) COLLATE latin1_general_ci DEFAULT NULL,
  `radminsuper` tinyint(2) NOT NULL DEFAULT '1',
  `admlanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `checknum` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `last_login` int(11) DEFAULT NULL,
  `last_ip` varchar(15) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `agent` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`aid`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `nukeviet_authors`
--

INSERT INTO `nukeviet_authors` (`aid`, `name`, `url`, `email`, `pwd`, `radminsuper`, `admlanguage`, `checknum`, `last_login`, `last_ip`, `agent`) VALUES
('admin', 'God', 'http://localhost/vanphongdientu', 'admin@gmail.com', 'c44a471bd78cc6c2fea32b9fe028d30a', 1, '', '4ff1f780e2dccf28b1c8bd4092bc311b', 1249713580, '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/530.19.2 (KHTML, lik');

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_blocks`
--

CREATE TABLE IF NOT EXISTS `nukeviet_blocks` (
  `bid` int(10) NOT NULL AUTO_INCREMENT,
  `bkey` int(1) NOT NULL DEFAULT '0',
  `title` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `url` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `bposition` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `weight` int(10) NOT NULL DEFAULT '1',
  `active` int(1) NOT NULL DEFAULT '1',
  `refresh` int(10) NOT NULL DEFAULT '0',
  `time` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `blanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `blockfile` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `view` int(1) NOT NULL DEFAULT '0',
  `expire` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `action` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `module` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `bid` (`bid`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `nukeviet_blocks`
--

INSERT INTO `nukeviet_blocks` (`bid`, `bkey`, `title`, `url`, `bposition`, `weight`, `active`, `refresh`, `time`, `blanguage`, `blockfile`, `view`, `expire`, `action`, `link`, `module`) VALUES
(1, 0, 'Tiá»‡n Ã­ch trÃªn site', '', 'l', 1, 1, 0, '1240857254', 'vietnamese', 'block-Menu1_default.php', 0, '0', 'd', '', 'all'),
(2, 0, 'Tra bÃ i theo ngÃ y', '', 'l', 2, 1, 0, '1246781227', 'vietnamese', 'block-Calendar.php', 0, '0', 'd', '', 'all'),
(3, 0, 'Nháº­n tin qua mail', '', 'l', 3, 1, 0, '1246781289', 'vietnamese', 'block-Letter.php', 0, '0', 'd', '', 'all'),
(4, 0, 'Trá»±c tuyáº¿n trÃªn site', '', 'l', 4, 1, 0, '1246781347', 'vietnamese', 'block-Online.php', 0, '0', 'd', '', 'all'),
(5, 0, 'Select language', '', 'r', 1, 1, 0, '1246781398', 'vietnamese', 'block-Languages.php', 0, '0', 'd', '', 'all'),
(6, 0, 'TÃ¬m kiáº¿m', '', 'r', 2, 1, 0, '1246781442', 'vietnamese', 'block-Search.php', 0, '0', 'd', '', 'all'),
(7, 0, 'Danh má»¥c tin', '', 'r', 3, 1, 0, '1246781473', 'vietnamese', 'block-Categories.php', 0, '0', 'd', '', 'all'),
(8, 0, 'BÃ¬nh chá»n', '', 'r', 4, 1, 0, '1246781535', 'vietnamese', 'block-RandomVoting.php', 0, '0', 'd', '', 'all'),
(9, 0, 'Lá»‹ch váº¡n sá»±', '', 'r', 5, 1, 0, '1246781644', 'vietnamese', 'block-Amlich.php', 0, '0', 'd', '', 'all'),
(10, 0, 'Danh ngÃ´n', '', 'c', 1, 1, 0, '1246782623', 'vietnamese', 'block-Danhngon_ty_cs.php', 0, '0', 'd', '', 'all'),
(11, 0, 'Main menu', '', 'l', 1, 1, 0, '1240857254', 'english', 'block-Menu1_default.php', 0, '0', 'd', '', 'all'),
(12, 0, 'Calendar', '', 'l', 2, 1, 0, '1246781227', 'english', 'block-Calendar.php', 0, '0', 'd', '', 'all'),
(13, 0, 'Newsletter', '', 'l', 3, 1, 0, '1246781289', 'english', 'block-Letter.php', 0, '0', 'd', '', 'all'),
(14, 0, 'Online', '', 'l', 4, 1, 0, '1246781347', 'english', 'block-Online.php', 0, '0', 'd', '', 'all'),
(15, 0, 'Select language', '', 'r', 1, 1, 0, '1246781398', 'english', 'block-Languages.php', 0, '0', 'd', '', 'all'),
(16, 0, 'Search', '', 'r', 2, 1, 0, '1246781442', 'english', 'block-Search.php', 0, '0', 'd', '', 'all'),
(17, 0, 'Categories', '', 'r', 3, 1, 0, '1246781473', 'english', 'block-Categories.php', 0, '0', 'd', '', 'all'),
(18, 0, 'Random Voting', '', 'r', 4, 1, 0, '1246781535', 'english', 'block-RandomVoting.php', 0, '0', 'd', '', 'all'),
(19, 0, 'Lunar Calendar', '', 'r', 5, 1, 0, '1246781644', 'english', 'block-Amlich.php', 0, '0', 'd', '', 'all'),
(20, 0, 'Famous saying', '', 'c', 1, 1, 0, '1246782623', 'english', 'block-Danhngon_ty_cs.php', 0, '0', 'd', '', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_contact`
--

CREATE TABLE IF NOT EXISTS `nukeviet_contact` (
  `pid` int(3) NOT NULL AUTO_INCREMENT,
  `phone_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `add_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_num` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `fax_num` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `email_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `web_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `note_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_contact_contact`
--

CREATE TABLE IF NOT EXISTS `nukeviet_contact_contact` (
  `pid` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `add_name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `phone_num` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `email_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `dip_name` int(3) DEFAULT NULL,
  `messenger` text COLLATE latin1_general_ci,
  `reply` int(3) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_contact_contact`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_contact_dept`
--

CREATE TABLE IF NOT EXISTS `nukeviet_contact_dept` (
  `did` int(3) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `dept_email` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `dept_contact` int(3) DEFAULT NULL,
  PRIMARY KEY (`did`),
  KEY `did` (`did`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nukeviet_contact_dept`
--

INSERT INTO `nukeviet_contact_dept` (`did`, `dept_name`, `dept_email`, `dept_contact`) VALUES
(1, 'admin', 'admin@gmail.com', 3);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_files`
--

CREATE TABLE IF NOT EXISTS `nukeviet_files` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  `url` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `version` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `homepage` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `ip_sender` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `totalvotes` int(11) NOT NULL DEFAULT '0',
  `totalcomments` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`),
  KEY `lid` (`lid`),
  KEY `cid` (`cid`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_files_categories`
--

CREATE TABLE IF NOT EXISTS `nukeviet_files_categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `cdescription` text COLLATE latin1_general_ci NOT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `cid` (`cid`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_files_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_files_comments`
--

CREATE TABLE IF NOT EXISTS `nukeviet_files_comments` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `url` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `host_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `comment` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `tid` (`tid`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_files_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_files_poolchec`
--

CREATE TABLE IF NOT EXISTS `nukeviet_files_poolchec` (
  `lid` int(11) NOT NULL,
  `time` varchar(14) COLLATE latin1_general_ci NOT NULL,
  `host_addr` varchar(48) COLLATE latin1_general_ci NOT NULL,
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `nukeviet_files_poolchec`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_headlines`
--

CREATE TABLE IF NOT EXISTS `nukeviet_headlines` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `headlinesurl` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`hid`),
  KEY `hid` (`hid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `nukeviet_headlines`
--

INSERT INTO `nukeviet_headlines` (`hid`, `sitename`, `headlinesurl`) VALUES
(1, 'PHP-Nuke', 'http://phpnuke.org/backend.php'),
(2, 'NukeCops', 'http://www.nukecops.com/backend.php'),
(3, 'NukeViet', 'http://nukeviet.vn/phpbb/rss.php');

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_message`
--

CREATE TABLE IF NOT EXISTS `nukeviet_message` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `content` text COLLATE latin1_general_ci NOT NULL,
  `date` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `expire` int(7) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `view` int(1) NOT NULL DEFAULT '1',
  `mlanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `mid` (`mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `nukeviet_message`
--

INSERT INTO `nukeviet_message` (`mid`, `title`, `content`, `date`, `expire`, `active`, `view`, `mlanguage`) VALUES
(1, 'Báº¡n Ä‘ang sá»­ dá»¥ng há»‡ thá»‘ng xÃ¢y dá»±ng website NUKEVIET!', '<center><br>Xin chÃºc má»«ng, viá»‡c cÃ i Ä‘áº·t há»‡ thá»‘ng <b><font color=blue>NUKEVIET</font></b> Ä‘Ã£ hoÃ n táº¥t!\r\n<br>Há»‡ thá»‘ng nÃ y cÃ³ chá»©c nÄƒng giÃºp báº¡n xÃ¢y dá»±ng webportal dá»±a trÃªn ná»n táº£ng PHP-Nuke.<br>Báº¡n sáº½ luÃ´n tÃ¬m tháº¥y sá»± giÃºp Ä‘á»¡ trong viá»‡c cÃ i Ä‘áº·t, sá»­ dá»¥ng cÅ©ng nhÆ° nhá»¯ng modules, blocks, giao diá»‡n má»›i cho NUKEVIET táº¡i <a href=http://nukeviet.vn>website nukeviet.vn</a>.</center>', '1249713485', 0, 1, 1, 'vietnamese'),
(2, 'NukeViet installation successful!', '<center>You are using NukeViet web-portal version 2.0. The installation is completed.<br />Thanks for choice <a href=http://nukeviet.vn>NukeViet</a>!</center>', '1249713485', 0, 1, 1, 'english');

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_modules`
--

CREATE TABLE IF NOT EXISTS `nukeviet_modules` (
  `mid` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `custom_title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `active` int(1) NOT NULL DEFAULT '0',
  `view` int(1) NOT NULL DEFAULT '0',
  `bltype` tinyint(1) NOT NULL DEFAULT '1',
  `inmenu` tinyint(1) NOT NULL DEFAULT '1',
  `theme` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `admins` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`mid`),
  KEY `mid` (`mid`),
  KEY `title` (`title`),
  KEY `custom_title` (`custom_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `nukeviet_modules`
--

INSERT INTO `nukeviet_modules` (`mid`, `title`, `custom_title`, `active`, `view`, `bltype`, `inmenu`, `theme`, `admins`) VALUES
(1, 'News', 'Tin tá»©c', 1, 0, 1, 4, '', ''),
(2, 'Contact', 'LiÃªn há»‡', 1, 0, 1, 4, '', ''),
(3, 'Search', 'TÃ¬m kiáº¿m', 1, 0, 4, 4, '', ''),
(4, 'Voting', 'ThÄƒm dÃ² dÆ° luáº­n', 1, 0, 1, 4, '', ''),
(5, 'Your_Account', 'ThÃ´ng tin thÃ nh viÃªn', 1, 0, 3, 1, '', ''),
(6, 'Newsletter', 'Tin tá»©c qua email', 1, 0, 4, 1, '', ''),
(7, 'Files', 'Táº£i Files', 1, 0, 1, 4, '', ''),
(8, 'AutoTranslate', 'Dá»‹ch tá»± Ä‘á»™ng', 1, 0, 1, 1, '', ''),
(9, 'Sitemap', 'SÆ¡ Ä‘á»“ site', 1, 0, 4, 3, '', ''),
(10, 'Weblinks', 'Weblinks', 1, 0, 1, 1, '', ''),
(11, 'Addnews', 'Gá»­i bÃ i', 1, 0, 1, 2, '', ''),
(12, 'Rss', 'Rss', 1, 0, 1, 0, '', ''),
(13, 'Support', 'Support', 1, 0, 1, 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_newsletter`
--

CREATE TABLE IF NOT EXISTS `nukeviet_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `html` int(11) NOT NULL DEFAULT '0',
  `checkkey` int(11) NOT NULL DEFAULT '0',
  `time` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `newsletterid` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_newsletter`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_newsletter_send`
--

CREATE TABLE IF NOT EXISTS `nukeviet_newsletter_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `text` text COLLATE latin1_general_ci,
  `html` text COLLATE latin1_general_ci,
  `send` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_newsletter_send`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvsupport_all`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvsupport_all` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `language` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `question` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `answer` text COLLATE latin1_general_ci NOT NULL,
  `view` int(10) NOT NULL DEFAULT '0',
  `publtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `question` (`question`),
  KEY `publtime` (`publtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `nukeviet_nvsupport_all`
--

INSERT INTO `nukeviet_nvsupport_all` (`id`, `catid`, `language`, `question`, `answer`, `view`, `publtime`) VALUES
(1, 1, 'vietnamese', 'LÃ m tháº¿ nÃ o gÃµ tiáº¿ng Viá»‡t trÃªn Website nÃ y?', 'Diá»…n Ä‘Ã n Ä‘Ã£ tÃ­ch há»£p bá»™ gÃµ AVIM - bá»™ gÃµ tiáº¿ng Viá»‡t trÃªn Web nÃªn báº¡nhoÃ n toÃ n cÃ³ thá»ƒ gÃµ tiáº¿ng Viá»‡t cÃ³ dáº¥u má»™t cÃ¡ch dá»… dÃ ng (náº¿u truy cáº­p báº±ng cÃ¡c trÃ¬nh duyá»‡t mÃ  AVIM há»— trá»£ nhÆ°: Opera 9, FireFox 1.5,2 ,Internet Explorer 5,6,7) mÃ  khÃ´ng cáº§n báº¥t cá»© cÃ´ng cá»¥ nÃ o khÃ¡c.Bá»™ gÃµ Ä‘Ã£ Ä‘Æ°á»£c cáº¥u hÃ¬nh Ä‘á»ƒ cÃ³ thá»ƒ tÆ°Æ¡ng thÃ­ch vá»›i táº¥t cáº£ cÃ¡c kiá»ƒu gÃµ hiá»‡n nay, vÃ¬ váº­y náº¿u báº¡n Ä‘Ã£ tá»«ng soáº¡n tháº£o má»™t tÃ i liá»‡u tiáº¿ng Viá»‡t, hÃ£y gÃµ nhÆ° báº¡n Ä‘Ã£ tá»«ng gÃµ! Náº¿u báº¡n chÆ°a biáº¿t cÃ¡c gÃµ tiáº¿ng Viá»‡t cÃ³ dáº¥u, xin hÃ£y xem CÃ¡ch gÃµ tiáº¿ng Viá»‡t cÃ³ dáº¥u vÃ  CÃ¡c kiá»ƒu gÃµ tiáº¿ng Viá»‡t cÃ³ dáº¥u! HÃ£y viáº¿t tiáº¿ng Viá»‡t cÃ³ dáº¥u trong trÆ°á»ng há»£p cÃ³ thá»ƒ.', 0, 1249713485),
(2, 1, 'vietnamese', 'LÃ m tháº¿ nÃ o Ä‘á»ƒ láº¥y láº¡i máº­t kháº©u cá»§a mÃ¬nh?', 'Náº¿u báº¡n lá»¡ quÃªn máº­t kháº©u vÃ o Website thÃ¬ cÅ©ng Ä‘á»«ng lo láº¯ng! Tuy máº­t kháº©u cá»§a báº¡n khÃ´ng thá»ƒ phá»¥c há»“i láº¡i Ä‘Æ°á»£c nhÆ°ng cÃ³thá»ƒ Ä‘Æ°á»£c táº¡o láº¡i dá»… dÃ ng. Trong trang Ä‘Äƒng nháº­p, báº¡n hÃ£y báº¥m vÃ o liÃªn káº¿t <em>QuÃªn máº­t kháº©u</em>. HÃ£y lÃ m theo hÆ°á»›ng dáº«n cá»§a cÃ´ng cá»¥ nÃ y vÃ  báº¡n sáº½ cÃ³ thá»ƒ Ä‘Äƒng nháº­p trá»Ÿ láº¡i vÃ o há»‡ thá»‘ng má»™t cÃ¡ch nhanh chÃ³ng.', 0, 1249713485),
(3, 1, 'vietnamese', 'Táº¡i sao tÃ´i khÃ´ng thá»ƒ Ä‘Äƒng nháº­p Ä‘Æ°á»£c?', 'CÃ³ thá»ƒ cÃ³ má»™t vÃ i nguyÃªn nhÃ¢n dáº«n Ä‘áº¿n viá»‡c nÃ y. TrÆ°á»›c tiÃªn, hÃ£y cháº¯c cháº¯n ráº±ng tÃªn thÃ nh viÃªn vÃ  máº­t kháº©u cá»§a báº¡n Ä‘Ã£ nháº­p vÃ o chÃ­nh xÃ¡c. Kiá»ƒm tra xem cÃ³ pháº£i do phÃ­m CapsLock Ä‘ang báº­t khÃ´ng? CÃ³ pháº£i do bá»™ gÃµ tiáº¿ng Viá»‡t Ä‘ang bá» dáº¥u khÃ´ng? Náº¿u váº­y hÃ£y táº¯t nÃ³ Ä‘i trÆ°á»›c khi gÃµ máº­t kháº©u.<br /><br />Náº¿u báº¡n Ä‘Ã£ nháº­p chÃ­nh xÃ¡c mÃ  váº«n khÃ´ng thá»ƒ Ä‘Äƒng nháº­p Ä‘Æ°á»£c, hÃ£y liÃªn há»‡ vá»›i ngÆ°á»i quáº£n trá»‹ Ä‘á»ƒ cháº¯c cháº¯n ráº±ng báº¡n khÃ´ng bá»‹ cáº¥m tham gia. CÅ©ng cÃ³ thá»ƒ,ngÆ°á»i quáº£n lÃ½ website Ä‘Ã£ cáº¥u hÃ¬nh sai há»‡ thá»‘ng á»Ÿ má»™t chá»— nÃ o Ä‘Ã³ vÃ  há»cáº§n báº¡n thÃ´ng bÃ¡o Ä‘á»ƒ biáº¿t mÃ  tiáº¿n hÃ nh kháº¯c phá»¥c.', 0, 1249713485),
(4, 1, 'vietnamese', 'Táº¡i sao tÃ´i cáº§n pháº£i Ä‘Äƒng kÃ½ lÃ m thÃ nh viÃªn?', 'Báº¡n cÃ³ thá»ƒ khÃ´ng cáº§n pháº£i lÃ m nhÆ° tháº¿ nhÆ°ng Ä‘Ã´i khi báº¡n cáº§n pháº£i Ä‘Äƒng kÃ½ má»›i cÃ³ thá»ƒ truy cáº­p cÃ¡c khu vá»±c dÃ nh cho thÃ nh viÃªn, tuá»³ theo yÃªu cáº§u cá»§a ngÆ°á»i quáº£n trá»‹. Tuy nhiÃªn, viá»‡c Ä‘Äƒng kÃ½ lÃ m thÃ nh viÃªn sáº½ giÃºp báº¡n sá»­ dá»¥ng Ä‘Æ°á»£c háº¿t táº¥t cáº£ cÃ¡c chá»©c nÄƒng mÃ  Website chá»‰ dÃ nh cho cÃ¡c thÃ nh viÃªn Ä‘Ã£ Ä‘Äƒng kÃ½ vÃ  khÃ´ng dÃ nh cho khÃ¡ch sá»­ dá»¥ng, vÃ­ dá»¥ nhÆ° gá»­itin nháº¯n, gá»­i Email Ä‘áº¿n cÃ¡c thÃ nh viÃªn khÃ¡c, tham gia vÃ o cÃ¡c nhÃ³mâ€¦ Báº¡n chá»‰ máº¥t vÃ i phÃºt Ä‘á»ƒ hoÃ n táº¥t viá»‡c Ä‘Äƒng kÃ½, vÃ¬ váº­y hÃ£y Ä‘Äƒng kÃ½ lÃ m thÃ nh viÃªn cá»§a chÃºng tÃ´i.', 0, 1249713485),
(5, 1, 'vietnamese', 'MÃ£ chá»‘ng Spam lÃ  gÃ¬?', 'ÄÃ¢y lÃ  chuá»—i kÃ½ tá»± ngáº«u nhiÃªn gá»“m 6 chá»¯ sá»‘, Website báº¯t báº¡n nháº­p vÃ o dÃ£y kÃ½ tá»± nÃ y Ä‘á»ƒ há»‡ thá»‘ng hiá»ƒu ráº±ng Ä‘Ã¢y lÃ  do con ngÆ°á»i nháº­p chá»© khÃ´ng pháº£i do cÃ¡c chÆ°Æ¡ng trÃ¬nh tá»± Ä‘á»™ng hoáº·c Virut mÃ¡y tÃ­nh.<br />', 0, 1249713485),
(6, 1, 'vietnamese', 'TÃ´i cÃ³ thá»ƒ sá»­ dá»¥ng mÃ£ HTML Ä‘Æ°á»£c khÃ´ng?', 'Äá»ƒ báº£o vá»‡ Website, chÃºng tÃ´i khÃ´ng cho sá»­ dá»¥ng HTML. Náº¿u báº¡n muá»‘n gá»­i cho chÃºng tÃ´i nhá»¯ng bÃ i viáº¿t cÃ³ Ä‘á»‹nh dáº¡ng, vui lÃ²ng soáº¡n trÃªn Microsoft Word vÃ  gá»­i cho chÃºng tÃ´i qua email.<br />', 0, 1249713485),
(7, 1, 'vietnamese', 'Website nÃ y xÃ¢y dá»±ng báº±ng mÃ£ nguá»“n nÃ o?', 'Website nÃ y Ä‘Æ°á»£c viáº¿t báº±ng ngÃ´n ngá»¯ PHP, xÃ¢y dá»±ng trÃªn ná»n táº£ng mÃ£ nguá»“n má»Ÿ NukeViet. Vui lÃ²ng truy cáº­p <a href="http://nukeviet.vn" target="_blank">nukeviet.vn</a> Ä‘á»ƒ biáº¿t thÃªm chi tiáº¿t.<br />', 1, 1249713485),
(8, 1, 'vietnamese', 'CÃ¡ch sá»­ dá»¥ng bá»™ gÃµ tiáº¿ng Viá»‡t trÃªn Website?', '<h4>HÆ°á»›ng dáº«n sá»­ dá»¥ng bá»™ gÃµ tiáº¿ng viá»‡t tÃ­ch há»£p trÃªn Web</h4><ul><li><p align="justify"><b>Website Ä‘Ã£ tÃ­ch há»£p sáºµn bá»™ gÃµ tiáº¿ng Viá»‡t AVIM</b>. Báº¡n cÃ³ thá»ƒ gÃµ tiáº¿ng viá»‡t cÃ³ dáº¥u mÃ  khÃ´ng cáº§n cÃ¡c chÆ°Æ¡ng trÃ¬nh há»— trá»£ tiáº¿ng Viá»‡t khÃ¡c nhÆ° Vietkey hay Unikey (nÃªn táº¯t Vietkey, Unikey Ä‘i Ä‘á»ƒ trÃ¡nh xung Ä‘á»™t).</p></li></ul><p align="justify"><i><b><h4>CÃ¡ch sá»­ dá»¥ng:</h4></b></i></p><ul><li><p align="justify">Máº·c Ä‘á»‹nh, bá»™ gÃµ Ä‘Ã£ Ä‘Æ°á»£c báº­t vá»›i cháº¿ Ä‘á»™ Tá»± Ä‘á»™ng. Cháº¿ Ä‘á»™ tá»± Ä‘á»™ng lÃ  cháº¿ Ä‘á»™ mÃ  báº¡n cÃ³ thá»ƒ gÃµ tiáº¿ng Viá»‡t báº±ng báº¥t ká»³ kiá»ƒu gÃµ nÃ o (Telex, VNI, VIQR ...) Ä‘á»u Ä‘Æ°á»£c.</p></li><li><p align="justify">áº¤n phÃ­m F9 (á»Ÿ hÃ ng trÃªn cÃ¹ng cá»§a bÃ n phÃ­m) Ä‘á»ƒ Ä‘á»•i láº§n lÆ°á»£t cÃ¡c kiá»ƒu gÃµ (Telex, VNI, VIQR) cho phÃ¹ há»£p vá»›i báº¡n. Náº¿u khÃ´ng rÃµ mÃ¬nh thÆ°á»ng gÃµ tiáº¿ng Viá»‡t theo kiá»ƒu gÃµ nÃ o, báº¡n hÃ£y chá»n cháº¿ Ä‘á»™ Tá»± Ä‘á»™ng. Äá»ƒ biáº¿t thÃªm vá» cÃ¡ch gÃµ chá»¯ Viá»‡t, xin xem thÃªm <a href="http://mangvn.org/nukeviet/modules.php?name=Dictionary&file=gotiengviet">táº¡i Ä‘Ã¢y</a>.</p></li><li><p align="justify">áº¤n phÃ­m F8 Ä‘á»ƒ táº¯t/báº­t cháº¿ Ä‘á»™ kiá»ƒm tra chÃ­nh táº£.</p></li><li><p align="justify">Nháº¥p F7 Ä‘á»ƒ chá»n cháº¿ Ä‘á»™ bá» dáº¥u kiá»ƒu cÅ© (oÃ ) hay má»›i (Ã²a). </p></li><li><p align="justify">Nháº¥p F12 Ä‘á»ƒ báº­t táº¯t cháº¿ Ä‘á»™ bá» dáº¥u tiáº¿ng Viá»‡t.</p></li></ul><p align="justify"><i><b><h4>ChÃº Ã½:</h4></b></i></p><ul><li><p align="justify">Táº¯t Vietkey, Unikey trÃªn mÃ¡y tÃ­nh cá»§a báº¡n náº¿u báº¡n muá»‘n sá»­ dá»¥ng bá»™ gÃµ cÃ³ sáºµn trÃªn web Ä‘á»ƒ trÃ¡nh bá»‹ lá»—i khi gÃµ tiáº¿ng Viá»‡t.</p></li><li><p align="justify">Náº¿u báº¡n muá»‘n sá»­ dá»¥ng Vietkey hoáº·c Unikey thÃ¬ pháº£i táº¯t bá»™ gÃµ AVIM trÃªn web Ä‘i, lÆ°u Ã½ cáº¥u hÃ¬nh Vietkey vÃ  Unikey vá»›i báº£ng mÃ£ lÃ  Unicode.</p></li><li><p align="justify">Báº¡n cÃ³ thá»ƒ tháº¥y thÃ´ng tin vá» bá»™ gÃµ sáº½ thá»ƒ hiá»‡n trÃªn thanh tráº¡ng thÃ¡i (Statusbar, náº±m á»Ÿ cuá»‘i cá»­a sá»•) cá»§a trÃ¬nh duyá»‡t Internet Explorer hay Opera (trong FireFox khÃ´ng nhÃ¬n tháº¥y nhÆ°ng váº«n hoáº¡t Ä‘á»™ng bÃ¬nh thÆ°á»ng) <br />&nbsp;</p></li></ul>', 1, 1249713485),
(9, 1, 'vietnamese', 'CÃ³ cÃ¡c kiá»ƒu gÃµ chá»¯ Viá»‡t cÃ³ thá»ƒ sá»­ dá»¥ng trÃªn Website nÃ y?', '<ul><li><p align="justify"><b>CÃ¡c chá»¯ cÃ¡i Ä‘áº·c biá»‡t cá»§a tiáº¿ng Viá»‡t</b> Ä‘Æ°á»£c gÃµ qua bÃ n phÃ­m báº±ng cÃ¡ch gÃµ chá»¯ cÃ¡i latin tÆ°Æ¡ng á»©ng rá»“i gÃµ liá»n Ä‘Ã³ má»™t phÃ­m thá»© hai (xem báº£ng dÆ°á»›i).&nbsp;<br /></p></li><li><p align="justify"><b>CÃ¡c tá»« cÃ³ dáº¥u thanh cá»§a tiáº¿ng Viá»‡t</b> Ä‘Æ°á»£c gÃµ qua bÃ n phÃ­m báº±ng cÃ¡ch gÃµ tá»« khÃ´ng dáº¥u vÃ  liá»n Ä‘Ã³ gÃµ 1 phÃ­m nháº¥t Ä‘á»‹nh khÃ¡c (xem báº£ng dÆ°á»›i).</p></li></ul><table cellspacing="1" cellpadding="2" width="400" border="0" align="center">  <tbody><tr>    <td align="center"><b>Dáº¥u &amp; nguyÃªn Ã¢m</b></td>    <td align="center"><b>CÃ¡ch gÃµ Telex</b></td>    <td align="center"><b>CÃ¡ch gÃµ VNI</b></td>    <td align="center"><b>CÃ¡ch gÃµ VIQR</b></td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Ã¢</td>    <td align="center">aa</td>    <td align="center">a6</td>    <td align="center">a^</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Ãª</td>    <td align="center">ee</td>    <td align="center">e6</td>    <td align="center">e^</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Ã´</td>    <td align="center">oo</td>    <td align="center">o6</td>    <td align="center">o^</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Äƒ</td>    <td align="center">aw</td>    <td align="center">a8</td>    <td align="center">a(</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Æ¡</td>    <td align="center">ow</td>    <td align="center">o7</td>    <td align="center">o+</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Æ°</td>    <td align="center">uw</td>    <td align="center">u7</td>    <td align="center">u+</td>  </tr>  <tr bgcolor="#f0f0f0">    <td align="center">Ä‘</td>    <td align="center">dd</td>    <td align="center">d9</td>    <td align="center">dd</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">sáº¯c</td>    <td align="center">s</td>    <td align="center">1</td>    <td align="center">''</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">huyá»n</td>    <td align="center">f</td>    <td align="center">2</td>    <td align="center">`</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">náº·ng</td>    <td align="center">j</td>    <td align="center">5</td>    <td align="center">.</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">há»i</td>    <td align="center">r</td>    <td align="center">3</td>    <td align="center">?</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">ngÃ£</td>    <td align="center">x</td>    <td align="center">4</td>    <td align="center">~</td>  </tr>  <tr bgcolor="#e0e0e0">    <td align="center">xoÃ¡ dáº¥u</td>    <td align="center">z</td>    <td align="center">0</td>    <td align="center">-</td>  </tr>  <tr bgcolor="#d5d5d5">    <td align="center">VÃ­ dá»¥: <br />Tiáº¿ng viá»‡t</td>    <td align="center">Vis duj: <br />Tieengs Vieetj</td>    <td align="center">Vi1 du5: <br />Tie6ng1 Vie6t5</td>    <td align="center">Vi'' du.: <br />Tie^''ng Vie^.t</td>  </tr></tbody></table>Cháº¿ Ä‘á»™ tá»± Ä‘á»™ng lÃ  cháº¿ Ä‘á»™ mÃ  báº¡n cÃ³ thá»ƒ gÃµ báº±ng báº¥t ká»³ cÃ¡ch nÃ o Ä‘á»u Ä‘Æ°á»£c.<b><br />LÆ°u Ã½:</b><ul><li>Dáº¥u cá»§a má»™t tá»« cÃ³ thá»ƒ Ä‘Æ°á»£c gÃµ vÃ o ngay sau nguyÃªn Ã¢m mang dáº¥u, nhÆ°ng Ä‘á»ƒ trÃ¡nh Ä‘iá»n dáº¥u sai nÃªn Ä‘Ã¡nh sau tá»«, dáº¥u sáº½ Ä‘Æ°á»£c tá»± Ä‘á»™ng Ä‘Ã¡nh vÃ o vá»‹ trÃ­ phÃ¹ há»£p.&nbsp;<br /></li><li>Trong trÆ°á»ng há»£p gÃµ nháº§m dáº¥u, cÃ³ thá»ƒ sá»­a láº¡i báº±ng cÃ¡ch chuyá»ƒn con trá» tá»›i cuá»‘i tá»« Ä‘Ã³ vÃ  gÃµ luÃ´n vÃ o phÃ­m dáº¥u Ä‘Ãºng mÃ  khÃ´ng cáº§n pháº£i xoÃ¡ cáº£ tá»« Ä‘i gÃµ láº¡i.&nbsp;<br /></li><li>Äá»ƒ gÃµ vÃ o nhá»¯ng chá»¯ cÃ¡i hoáº·c chá»¯ sá»‘ Ä‘Ã£ Ä‘Æ°á»£c dÃ¹ng lÃ m phÃ­m Ä‘Ã¡nh dáº¥u thÃ¬ gÃµ phÃ­m Ä‘Ã³ liá»n 2 láº§n, vÃ­ dá»¥: aw táº¡o Äƒ, nhÆ°ng aww táº¡o aw, hoáº·c aaa táº¡o aa...&nbsp;<br /></li><li>Äá»ƒ viáº¿t nhanh nÃªn dÃ¹ng cÃ¡ch gÃµ Telex, vÃ¬ cÃ¡c phÃ­m dáº¥u Ä‘Æ°á»£c chá»n 1 cÃ¡ch khoa há»c, phÃ¹ há»£p vá»›i sá»± phÃ¢n bá»‘ cÃ¡c dáº¥u vÃ  cÃ¡c tá»• há»£p thÆ°á»ng gáº·p.</li></ul>', 1, 1249713485);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvsupport_cat`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvsupport_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subid` int(11) NOT NULL DEFAULT '0',
  `language` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `active` int(1) NOT NULL DEFAULT '0',
  `weight` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `subid` (`subid`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nukeviet_nvsupport_cat`
--

INSERT INTO `nukeviet_nvsupport_cat` (`id`, `subid`, `language`, `title`, `active`, `weight`) VALUES
(1, 0, 'vietnamese', 'Sá»­ dá»¥ng Website', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvsupport_user`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvsupport_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `view` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `language` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `question` text COLLATE latin1_general_ci NOT NULL,
  `answer` text COLLATE latin1_general_ci NOT NULL,
  `questiontime` int(11) NOT NULL DEFAULT '0',
  `answertime` int(11) NOT NULL DEFAULT '0',
  `sendername` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `senderemail` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `senderip` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `status` (`status`),
  KEY `title` (`title`),
  KEY `questiontime` (`questiontime`),
  KEY `answertime` (`answertime`),
  KEY `sendername` (`sendername`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_nvsupport_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvvotings`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvvotings` (
  `pollid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `votes` text COLLATE latin1_general_ci NOT NULL,
  `optiontext` text COLLATE latin1_general_ci NOT NULL,
  `options` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `acomm` int(1) NOT NULL DEFAULT '0',
  `totalvotes` int(11) NOT NULL DEFAULT '0',
  `totalcomm` int(11) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `planguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ttbc` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pollid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `nukeviet_nvvotings`
--

INSERT INTO `nukeviet_nvvotings` (`pollid`, `question`, `votes`, `optiontext`, `options`, `acomm`, `totalvotes`, `totalcomm`, `time`, `planguage`, `ttbc`) VALUES
(1, 'ÄÃ¡nh giÃ¡ cá»§a báº¡n vá» Website nÃ y?', '0|0|0|0|0', 'Tuyá»‡t vá»i|Tá»‘t|Trung bÃ¬nh|KhÃ´ng cÃ³ gÃ¬ Ä‘á»ƒ nÃ³i|Ráº¥t tá»“i', 5, 2, 0, 0, 1249713485, 'vietnamese', 1),
(2, 'Báº¡n Ä‘áº¿n tá»« khu vá»±c nÃ o?', '0|0|0|0|0', 'HÃ  Ná»™i|Huáº¿|Quy NhÆ¡n|TP Há»“ ChÃ­ Minh|Má»™t khu vá»±c khÃ¡c', 5, 2, 0, 0, 1249713485, 'vietnamese', 1),
(3, 'How are you feeling about Website?', '0|0|0|0|0', 'Excellent|Good|Normal|Bad|Very bad', 5, 2, 0, 0, 1249713485, 'english', 1),
(4, 'Where are you from?', '0|0|0|0|0', 'Euro|Asian|America|Australia|Africa', 5, 2, 0, 0, 1249713485, 'english', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvvoting_comments`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvvoting_comments` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `pollid` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `url` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `host_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `subject` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `comment` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `tid` (`tid`),
  KEY `pollID` (`pollid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_nvvoting_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_nvvoting_votes`
--

CREATE TABLE IF NOT EXISTS `nukeviet_nvvoting_votes` (
  `ip` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `vottime` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `pollid` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `nukeviet_nvvoting_votes`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stats`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stats` (
  `online` text COLLATE latin1_general_ci NOT NULL,
  `clients` text COLLATE latin1_general_ci NOT NULL,
  `hits` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `nukeviet_stats`
--

INSERT INTO `nukeviet_stats` (`online`, `clients`, `hits`) VALUES
('127.0.0.1:1:1249725475', '127.0.0.1:1249725467', 3);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `aid` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `hometext` text COLLATE latin1_general_ci,
  `bodytext` text COLLATE latin1_general_ci NOT NULL,
  `images` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `comments` int(11) DEFAULT '0',
  `counter` mediumint(8) unsigned DEFAULT NULL,
  `notes` text COLLATE latin1_general_ci NOT NULL,
  `ihome` int(1) NOT NULL DEFAULT '1',
  `alanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `acomm` int(1) NOT NULL DEFAULT '0',
  `imgtext` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `source` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `newsst` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `sid` (`sid`),
  KEY `catid` (`catid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_auto`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_auto` (
  `anid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `aid` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `time` varchar(19) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `hometext` text COLLATE latin1_general_ci NOT NULL,
  `bodytext` text COLLATE latin1_general_ci NOT NULL,
  `images` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `notes` text COLLATE latin1_general_ci NOT NULL,
  `ihome` int(1) NOT NULL DEFAULT '0',
  `alanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `acomm` int(1) NOT NULL DEFAULT '0',
  `imgtext` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `source` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `topicid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`anid`),
  KEY `anid` (`anid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_auto`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_cat`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_cat` (
  `catid` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `weight` int(10) NOT NULL DEFAULT '1',
  `catimage` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `ihome` int(1) NOT NULL DEFAULT '0',
  `storieshome` int(11) NOT NULL DEFAULT '0',
  `linkshome` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`catid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_cat`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_comments`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_comments` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `url` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `host_name` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `comment` text COLLATE latin1_general_ci NOT NULL,
  `online` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `tid` (`tid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_images`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_images` (
  `imgid` int(11) NOT NULL AUTO_INCREMENT,
  `imgtitle` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `imgtext` text COLLATE latin1_general_ci NOT NULL,
  `imglink` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sid` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `ihome` int(1) NOT NULL DEFAULT '0',
  `ilanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`imgid`),
  KEY `imgid` (`imgid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_temp`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_temp` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `aid` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `hometext` text COLLATE latin1_general_ci,
  `bodytext` text COLLATE latin1_general_ci NOT NULL,
  `images` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `alanguage` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sender_ip` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `imgtext` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `source` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `topicid` int(11) NOT NULL DEFAULT '0',
  `notes` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_stories_topic`
--

CREATE TABLE IF NOT EXISTS `nukeviet_stories_topic` (
  `topicid` int(11) NOT NULL AUTO_INCREMENT,
  `topictitle` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`topicid`),
  KEY `topicid` (`topicid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_stories_topic`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_users`
--

CREATE TABLE IF NOT EXISTS `nukeviet_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `user_password` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `user_regdate` int(11) NOT NULL DEFAULT '0',
  `user_viewemail` tinyint(2) DEFAULT NULL,
  `user_avatar` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT 'gallery/blank.gif',
  `user_avatar_type` tinyint(4) NOT NULL DEFAULT '3',
  `user_email` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_icq` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `user_website` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_from` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `user_sig` text COLLATE latin1_general_ci,
  `user_yim` varchar(25) COLLATE latin1_general_ci DEFAULT NULL,
  `user_interests` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_telephone` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `name` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `lastname` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `viewuname` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `opros` text COLLATE latin1_general_ci,
  `remember` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `nukeviet_users`
--

INSERT INTO `nukeviet_users` (`user_id`, `username`, `user_password`, `user_regdate`, `user_viewemail`, `user_avatar`, `user_avatar_type`, `user_email`, `user_icq`, `user_website`, `user_from`, `user_sig`, `user_yim`, `user_interests`, `user_telephone`, `name`, `lastname`, `viewuname`, `opros`, `remember`) VALUES
(1, 'Anonymous', '', 1249713485, NULL, '', 0, '', NULL, '', NULL, NULL, NULL, '', '', '', '', '', '', 0),
(2, 'minsu', 'e10adc3949ba59abbe56e057f20f883e', 1249713485, 1, 'gallery/091.gif', 3, 'minsu@gmail.com', NULL, 'http://localhost/vanphongdientu', NULL, NULL, NULL, '', '', '', 'minsu', '', 'TÃªn cá»§a báº¡n|bravo', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_users_temp`
--

CREATE TABLE IF NOT EXISTS `nukeviet_users_temp` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `viewuname` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_email` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_password` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `opros` text COLLATE latin1_general_ci NOT NULL,
  `check_num` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `time` varchar(14) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `nukeviet_users_temp`
--


-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_weblinks_cats`
--

CREATE TABLE IF NOT EXISTS `nukeviet_weblinks_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `language` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ihome` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nukeviet_weblinks_cats`
--

INSERT INTO `nukeviet_weblinks_cats` (`id`, `title`, `description`, `language`, `ihome`) VALUES
(1, 'Links with Site', 'CÃ¡c Website liÃªn káº¿t.', 'vietnamese', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nukeviet_weblinks_links`
--

CREATE TABLE IF NOT EXISTS `nukeviet_weblinks_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `urlimg` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `addtime` int(11) DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nukeviet_weblinks_links`
--

INSERT INTO `nukeviet_weblinks_links` (`id`, `cid`, `title`, `url`, `urlimg`, `description`, `addtime`, `hits`, `active`) VALUES
(1, 1, 'MÃ£ nguá»“n má»Ÿ NukeViet', 'http://nukeviet.vn', 'http://nukeviet.ws/img/nukeviet.png', 'Website chÃ­nh thá»©c há»— trá»£ sá»­ dá»¥ng mÃ£ nguá»“n má»Ÿ táº¡o web NukeViet.', 1249713485, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sc_base_union`
--

CREATE TABLE IF NOT EXISTS `sc_base_union` (
  `base_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `level` smallint(6) NOT NULL,
  `parent` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `confirm` tinyint(1) NOT NULL,
  PRIMARY KEY (`base_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_base_union`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_classify_members`
--

CREATE TABLE IF NOT EXISTS `sc_classify_members` (
  `member_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `base_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `point` int(11) NOT NULL,
  `note` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `year` year(4) NOT NULL,
  KEY `member_id` (`member_id`),
  KEY `base_id` (`base_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_classify_members`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_dependence`
--

CREATE TABLE IF NOT EXISTS `sc_dependence` (
  `member_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `base_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  KEY `member_id` (`member_id`),
  KEY `base_id` (`base_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_dependence`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_events`
--

CREATE TABLE IF NOT EXISTS `sc_events` (
  `event_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_events`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_join_events`
--

CREATE TABLE IF NOT EXISTS `sc_join_events` (
  `member_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `event_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  KEY `member_id` (`member_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_join_events`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_position_detail`
--

CREATE TABLE IF NOT EXISTS `sc_position_detail` (
  `member_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `position_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `base_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  KEY `member_id` (`member_id`),
  KEY `base_id` (`base_id`),
  KEY `position_id` (`position_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_position_detail`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_position_list`
--

CREATE TABLE IF NOT EXISTS `sc_position_list` (
  `position_id` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sc_position_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `sc_youth_union_members`
--

CREATE TABLE IF NOT EXISTS `sc_youth_union_members` (
  `member_id` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `female` tinyint(1) NOT NULL,
  `native_land` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `birthday` date NOT NULL,
  `join_date` date NOT NULL,
  `status` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `current_branch` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `fee_union` date NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sc_youth_union_members`
--

