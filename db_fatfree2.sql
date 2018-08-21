/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.31-MariaDB : Database - rimbamed_rimbacms
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`rimbamed_rimbacms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `rimbamed_rimbacms`;

/*Table structure for table `cms_category` */

DROP TABLE IF EXISTS `cms_category`;

CREATE TABLE `cms_category` (
  `category_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_parent_id` int(5) NOT NULL DEFAULT '0',
  `category_seotitle` varchar(255) NOT NULL,
  `category_picture` varchar(255) NOT NULL,
  `category_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `cms_category` */

insert  into `cms_category`(`category_id`,`category_parent_id`,`category_seotitle`,`category_picture`,`category_active`,`update_user`,`update_timestamp`) values (8,0,'service','gallery/img1.jpg','Y','hesti','2018-04-18 21:09:49'),(9,8,'life-style','default.png','Y','hesti','2018-04-02 14:56:24'),(10,9,'food','','Y','hesti','2018-04-02 14:56:43');

/*Table structure for table `cms_category_text` */

DROP TABLE IF EXISTS `cms_category_text`;

CREATE TABLE `cms_category_text` (
  `category_text_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_id` int(5) NOT NULL,
  `category_lang_code` varchar(3) NOT NULL,
  `category_title` varchar(255) NOT NULL,
  PRIMARY KEY (`category_text_id`),
  UNIQUE KEY `category_id` (`category_id`,`category_lang_code`),
  KEY `lang_code` (`category_lang_code`),
  CONSTRAINT `cms_category_text_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `cms_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_category_text_ibfk_2` FOREIGN KEY (`category_lang_code`) REFERENCES `cms_lang` (`lang_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Data for the table `cms_category_text` */

insert  into `cms_category_text`(`category_text_id`,`category_id`,`category_lang_code`,`category_title`) values (33,9,'id','Gaya Hidup'),(34,9,'gb','Life Style'),(35,10,'id','Kuliner'),(36,10,'gb','Food'),(41,8,'id','Pelayanan'),(42,8,'gb','Service');

/*Table structure for table `cms_comment` */

DROP TABLE IF EXISTS `cms_comment`;

CREATE TABLE `cms_comment` (
  `comment_id` int(5) NOT NULL AUTO_INCREMENT,
  `comment_parent_id` int(5) NOT NULL DEFAULT '0',
  `comment_post_id` int(5) NOT NULL,
  `comment_name` varchar(100) NOT NULL,
  `comment_email` varchar(100) NOT NULL,
  `comment_url` varchar(255) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` date NOT NULL,
  `comment_time` time NOT NULL,
  `comment_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `comment_status` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `cms_comment` */

insert  into `cms_comment`(`comment_id`,`comment_parent_id`,`comment_post_id`,`comment_name`,`comment_email`,`comment_url`,`comment_text`,`comment_date`,`comment_time`,`comment_active`,`comment_status`) values (1,0,3,'hesti wahyu','hesti@gmail.com','','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-02-26','00:00:00','Y','Y'),(2,1,3,'nugroho','nugroho@gmail.com','','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-02-26','00:00:00','Y','Y'),(3,2,3,'hesti wahyu','hesti@gmail.com','','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-02-26','00:00:00','Y','Y'),(4,0,3,'putri','putri@gmail.com','','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-02-28','00:00:00','Y','Y'),(6,0,4,'hesti','hesti@gmail.com','post-seo-2','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-03-03','04:56:14','Y','Y'),(7,6,4,'wahyu','wahyu@gmail.com','post-seo-2','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-03-03','05:06:37','Y','Y'),(8,1,3,'hesti wahyu nugroho','hesti@gmail.com','','Lorem ipsum dolor sit amet, luctus posuere semper felis consectetuer hendrerit, enim varius enim, tellus tincidunt tellus est sed mattis, libero elit mi suscipit. A nulla venenatis','2018-03-30','02:48:57','Y','Y');

/*Table structure for table `cms_component` */

DROP TABLE IF EXISTS `cms_component`;

CREATE TABLE `cms_component` (
  `component_id` int(5) NOT NULL AUTO_INCREMENT,
  `component` varchar(100) NOT NULL,
  `component_type` enum('component','widget') NOT NULL DEFAULT 'component',
  `component_datetime` datetime NOT NULL,
  `component_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `cms_component` */

insert  into `cms_component`(`component_id`,`component`,`component_type`,`component_datetime`,`component_active`) values (1,'archives','widget','2018-03-03 12:34:31','Y'),(2,'search','component','2018-03-06 14:39:39','Y'),(3,'subscribe','component','2018-03-06 14:48:28','Y'),(4,'tag','widget','2018-03-09 16:49:16','Y'),(5,'post','widget','2018-03-11 21:23:10','Y'),(6,'category','widget','2018-03-11 22:19:40','Y'),(7,'textarea','component','2018-03-28 12:02:13','Y');

/*Table structure for table `cms_gallery` */

DROP TABLE IF EXISTS `cms_gallery`;

CREATE TABLE `cms_gallery` (
  `gallery_id` int(5) NOT NULL AUTO_INCREMENT,
  `gallery_title` varchar(255) NOT NULL,
  `gallery_seotitle` varchar(255) NOT NULL,
  `gallery_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `gallery_hits` int(10) DEFAULT '0',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `cms_gallery` */

insert  into `cms_gallery`(`gallery_id`,`gallery_title`,`gallery_seotitle`,`gallery_active`,`gallery_hits`,`update_user`,`update_timestamp`) values (1,'File Manager','file-manager','N',0,'hesti','2018-04-05 21:48:27'),(2,'Album 2','album-2','Y',7,'hesti','2018-04-02 14:48:07'),(3,'Album 3','album-3','Y',5,'hesti','2018-03-30 11:15:45'),(4,'Album 1','album-1','Y',49,'hesti','2018-04-02 14:48:03');

/*Table structure for table `cms_gallery_images` */

DROP TABLE IF EXISTS `cms_gallery_images`;

CREATE TABLE `cms_gallery_images` (
  `images_id` int(5) NOT NULL AUTO_INCREMENT,
  `images_gallery_id` int(5) NOT NULL,
  `images_title` varchar(255) NOT NULL,
  `images_content` text NOT NULL,
  `images_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`images_id`),
  KEY `images_gallery_id` (`images_gallery_id`),
  CONSTRAINT `cms_gallery_images_ibfk_1` FOREIGN KEY (`images_gallery_id`) REFERENCES `cms_gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `cms_gallery_images` */

insert  into `cms_gallery_images`(`images_id`,`images_gallery_id`,`images_title`,`images_content`,`images_picture`) values (1,4,'gambar 1','gambar 1 dari album 1','img1.jpg'),(2,4,'gambar 2','','img2.jpg'),(3,4,'gambar 3','','img3.jpg'),(4,1,'Logo Rimba Media','logo rimba media','20180416064158.png'),(5,1,'breadcrumb','','20180623134741.jpg');

/*Table structure for table `cms_lang` */

DROP TABLE IF EXISTS `cms_lang`;

CREATE TABLE `cms_lang` (
  `lang_id` int(5) NOT NULL AUTO_INCREMENT,
  `lang_title` varchar(50) NOT NULL,
  `lang_code` varchar(3) NOT NULL,
  `lang_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `cms_lang` */

insert  into `cms_lang`(`lang_id`,`lang_title`,`lang_code`,`lang_active`,`update_user`,`update_timestamp`) values (1,'Indonesia','id','Y','hesti','2018-04-02 14:48:58'),(2,'English','gb','Y','hesti','2018-04-02 14:49:01');

/*Table structure for table `cms_menu` */

DROP TABLE IF EXISTS `cms_menu`;

CREATE TABLE `cms_menu` (
  `menu_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `menu_parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `menu_title` varchar(255) NOT NULL DEFAULT '',
  `menu_url` varchar(255) NOT NULL DEFAULT '',
  `menu_class` varchar(255) NOT NULL DEFAULT '',
  `menu_position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `menu_group_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `menu_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `menu_target` varchar(10) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Data for the table `cms_menu` */

insert  into `cms_menu`(`menu_id`,`menu_parent_id`,`menu_title`,`menu_url`,`menu_class`,`menu_position`,`menu_group_id`,`menu_active`,`menu_target`) values (1,0,'Beranda','','',1,2,'Y','none'),(2,0,'Kategori','','',2,2,'Y','none'),(3,2,'Pelayanan','/category/service','',1,2,'Y',''),(4,2,'Gaya Hidup','/category/life-style','',2,2,'Y',''),(5,0,'Tag','/tag','',3,2,'Y','none'),(6,0,'Galeri','/gallery','',4,2,'Y','none'),(7,0,'Tentang','/about','',5,2,'Y','none'),(8,0,'Kontak','/contacts','',6,2,'Y','none'),(9,0,'Home','','',1,3,'Y','none'),(10,0,'Category','','',2,3,'Y','none'),(11,10,'Service','/category/service','',1,3,'Y',''),(12,10,'Life Style','/category/life-style','',2,3,'Y',''),(13,0,'Tag','/tag','',3,3,'Y','none'),(14,0,'Gallery','/gallery','',4,3,'Y','none'),(15,0,'About','/about','',5,3,'Y','none'),(16,0,'Contact','/contacts','',6,3,'Y','none'),(17,0,'Dashboard','/adashboard','fa-home',1,1,'Y',''),(18,0,'Category','/acategory','fa-tag',2,1,'Y',''),(19,0,'Tag','/atag','fa fa-tags',3,1,'Y','none'),(20,0,'Post','/apost','fa-pencil',4,1,'Y','none'),(21,0,'Pages','/apages','fa-file',5,1,'Y','none'),(22,0,'Setting','','fa-cog',50,1,'Y',''),(23,22,'Menu','/amenu','fa-list',1,1,'Y','none'),(24,22,'Other','/asetting','fa-cogs',99,1,'Y',''),(25,22,'Theme','/atheme','fa-globe',3,1,'Y',''),(27,22,'Widget','/awidget','fa-th',5,1,'Y',''),(28,22,'Component','/acomponent','fa-cogs',4,1,'Y',''),(29,0,'Comment','/acomment','fa-comment',6,1,'Y',''),(30,0,'Subscribe','/asubscribe','fa-envelope',7,1,'Y',''),(31,22,'User','/auser','fa-user',2,1,'Y',''),(32,0,'Gallery','/agallery','fa-picture-o',8,1,'Y',''),(33,22,'Config and Route','/aconfig','fa-cogs',6,1,'Y',''),(34,0,'Testimoni','/atestimoni','fa-comments',9,1,'Y','');

/*Table structure for table `cms_menu_group` */

DROP TABLE IF EXISTS `cms_menu_group`;

CREATE TABLE `cms_menu_group` (
  `menu_group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menu_group_title` varchar(255) NOT NULL,
  PRIMARY KEY (`menu_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `cms_menu_group` */

insert  into `cms_menu_group`(`menu_group_id`,`menu_group_title`) values (1,'dashboard'),(2,'id'),(3,'gb');

/*Table structure for table `cms_pages` */

DROP TABLE IF EXISTS `cms_pages`;

CREATE TABLE `cms_pages` (
  `pages_id` int(5) NOT NULL AUTO_INCREMENT,
  `pages_seotitle` varchar(255) NOT NULL,
  `pages_picture` varchar(255) NOT NULL,
  `pages_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `cms_pages` */

insert  into `cms_pages`(`pages_id`,`pages_seotitle`,`pages_picture`,`pages_active`,`update_user`,`update_timestamp`) values (1,'about','gallery/img1.jpg','Y','hesti','2018-04-16 16:54:57'),(2,'contacts','','Y','hesti','2018-06-22 16:38:36');

/*Table structure for table `cms_pages_text` */

DROP TABLE IF EXISTS `cms_pages_text`;

CREATE TABLE `cms_pages_text` (
  `pages_text_id` int(5) NOT NULL AUTO_INCREMENT,
  `pages_id` int(5) NOT NULL,
  `pages_lang_code` varchar(3) NOT NULL,
  `pages_title` varchar(255) NOT NULL,
  `pages_content` text,
  PRIMARY KEY (`pages_text_id`),
  UNIQUE KEY `pages_id` (`pages_id`,`pages_lang_code`),
  KEY `lang_code` (`pages_lang_code`),
  CONSTRAINT `cms_pages_text_ibfk_1` FOREIGN KEY (`pages_id`) REFERENCES `cms_pages` (`pages_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_pages_text_ibfk_2` FOREIGN KEY (`pages_lang_code`) REFERENCES `cms_lang` (`lang_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `cms_pages_text` */

insert  into `cms_pages_text`(`pages_text_id`,`pages_id`,`pages_lang_code`,`pages_title`,`pages_content`) values (9,1,'id','Tentang Perusahaan Kami','Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makn\r\n'),(10,1,'gb','About Our Company','Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.\r\n'),(11,2,'id','Kontak Kami','<p>Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makn</p>\r\n'),(12,2,'gb','Our Contacts','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.</p>\r\n');

/*Table structure for table `cms_post` */

DROP TABLE IF EXISTS `cms_post`;

CREATE TABLE `cms_post` (
  `post_id` int(5) NOT NULL AUTO_INCREMENT,
  `post_seotitle` varchar(255) NOT NULL,
  `post_tag` text NOT NULL,
  `post_date` date NOT NULL,
  `post_time` time NOT NULL,
  `post_publishdate` datetime NOT NULL,
  `post_editor` int(5) NOT NULL DEFAULT '1',
  `post_headline` enum('Y','N') NOT NULL DEFAULT 'N',
  `post_comment` enum('Y','N') NOT NULL DEFAULT 'Y',
  `post_picture` varchar(255) NOT NULL,
  `post_picture_desc` varchar(255) NOT NULL,
  `post_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `post_hits` int(10) NOT NULL DEFAULT '1',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post` */

insert  into `cms_post`(`post_id`,`post_seotitle`,`post_tag`,`post_date`,`post_time`,`post_publishdate`,`post_editor`,`post_headline`,`post_comment`,`post_picture`,`post_picture_desc`,`post_active`,`post_hits`,`update_user`,`update_timestamp`) values (3,'post-seo','health,eat clean,eat local','2018-02-14','15:57:00','2018-02-14 15:57:00',1,'Y','Y','gallery/20180416064158.png','pic desc','Y',113,'hesti','2018-04-16 17:39:14'),(4,'post-seo-2','health,eat local,nutrition','2018-02-28','00:00:00','2018-02-28 00:00:00',1,'Y','Y','gallery/20180416064158.png','','Y',69,'hesti','2018-04-16 17:35:47'),(9,'post-seo-3','nutrition,i quit sugar','2018-03-02','00:00:00','2018-03-02 00:00:00',1,'Y','Y','gallery/20180416064158.png','','Y',44,'hesti','2018-04-16 17:36:36'),(10,'24-7-service','service,pelayanan','2018-04-17','00:00:00','2018-04-17 00:00:00',1,'N','N','','','Y',16,'hesti','2018-04-18 20:18:28'),(11,'professional-team','professional team,service,pelayanan','2018-04-17','00:00:00','2018-04-17 00:00:00',1,'N','N','','','Y',18,'hesti','2018-04-17 14:27:13');

/*Table structure for table `cms_post_category` */

DROP TABLE IF EXISTS `cms_post_category`;

CREATE TABLE `cms_post_category` (
  `post_category_id` int(5) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  PRIMARY KEY (`post_category_id`),
  KEY `category_id` (`category_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `cms_post_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `cms_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_post_category_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `cms_post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post_category` */

insert  into `cms_post_category`(`post_category_id`,`post_id`,`category_id`) values (43,4,8),(44,4,9),(45,9,8),(46,9,9),(47,9,10),(50,3,8),(51,3,9),(53,11,8),(55,10,8);

/*Table structure for table `cms_post_text` */

DROP TABLE IF EXISTS `cms_post_text`;

CREATE TABLE `cms_post_text` (
  `post_text_id` int(5) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL,
  `post_lang_code` varchar(3) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_content` text,
  PRIMARY KEY (`post_text_id`),
  UNIQUE KEY `post_id` (`post_id`,`post_lang_code`),
  KEY `lang_code` (`post_lang_code`),
  CONSTRAINT `cms_post_text_ibfk_2` FOREIGN KEY (`post_lang_code`) REFERENCES `cms_lang` (`lang_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cms_post_text_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `cms_post` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post_text` */

insert  into `cms_post_text`(`post_text_id`,`post_id`,`post_lang_code`,`post_title`,`post_content`) values (57,4,'id','Dari mana <span>asalnya</span>?','Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seor\r\n'),(58,4,'gb','Where does it <span>come from</span>?','Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.\r\n'),(59,9,'id','Mengapa kita <span>menggunakannya</span>?','Sudah merupakan fakta bahwa seorang pembaca akan terpengaruh oleh isi tulisan dari sebuah halaman saat ia melihat tata letaknya. Maksud penggunaan Lorem Ipsum adalah karena ia kurang lebih memiliki penyebaran huruf yang normal, ketimbang menggunakan kalimat seperti Bagian isi disini, bagian isi disini, sehingga ia seolah menjadi naskah Inggris yang bisa dibaca. Banyak paket Desktop Publishing dan editor\r\n'),(60,9,'gb','Why do we span>use it</span>?','It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content here, content here, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for lorem ipsum will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose [injected humour and the like].\r\n'),(63,3,'id','Apakah <span>Lorem Ipsum</span> itu?','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf.</p>\r\n\r\n<p>Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipo</p>\r\n'),(64,3,'gb','What is <span>Lorem Ipsum</span>?','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n'),(69,11,'id','Tim Professional','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf.</p>'),(70,11,'gb','Professional Team','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n'),(73,10,'id','<span>24/7</span> Pelayanan','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf.</p>\r\n'),(74,10,'gb','<span>24/7</span> Service','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n');

/*Table structure for table `cms_setting` */

DROP TABLE IF EXISTS `cms_setting`;

CREATE TABLE `cms_setting` (
  `setting_id` int(5) NOT NULL AUTO_INCREMENT,
  `setting_groups` varchar(50) NOT NULL,
  `setting_options` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

/*Data for the table `cms_setting` */

insert  into `cms_setting`(`setting_id`,`setting_groups`,`setting_options`,`setting_value`) values (1,'general','web_name','Rimba CMS'),(2,'general','web_url','http://rimbamedia.com'),(3,'general','address','Ds. Diwak Rt 02/Rw 01, Kec. Bergas, Kab. Semarang 50552'),(4,'general','web_keyword','Rimba Media, RimbaMedia, CMS, Rimba Media CMS'),(5,'general','web_owner','Rimba Media'),(6,'general','email','info@rimbamedia.com'),(7,'general','telephone','(+62) 821 1533 1455'),(8,'general','fax','000-0000-0000'),(9,'general','longitude','110.4267757'),(10,'general','latitude','-7.19834'),(11,'image','favicon','fav.png'),(12,'image','logo','logo.png'),(13,'config','img_thumb','238x206'),(14,'local','country','Indonesia'),(15,'local','region_state','Yogyakarta'),(16,'local','timezone','Asia/Jakarta'),(17,'config','maintenance','N'),(18,'config','member_registration','N'),(19,'config','comment','N'),(20,'config','item_per_page','5'),(21,'config','google_analytics',''),(22,'config','recaptcha_sitekey','6LckEgETAAAAAPdqrQSY_boMDLZRL1vpkAatVqKf'),(23,'config','recaptcha_secretkey','6LckEgETAAAAAHqx4VFD4zNL96P9UEikD8BHfT28'),(24,'mail','mail_protocol','SSL'),(25,'mail','mail_hostname','smtp.gmail.com'),(26,'mail','mail_username','rimbamediasb@gmail.com'),(27,'mail','mail_password','pass'),(28,'mail','mail_port','465'),(29,'config','permalink','slug/post-title'),(30,'config','slug_permalink','detailpost'),(31,'image','img_header','gallery/20180416064158.png'),(32,'general','app_version','1.0.0'),(33,'general','app_name','RimbaCMS'),(34,'general','app_year','2018'),(35,'config','img_size','1024'),(36,'config','img_ext','jpg,png,gif'),(37,'file','compress_size','5120'),(38,'file','compress_ext','zip'),(39,'general','postal_code','50552'),(40,'general','web_description','Description Rimba Media SB Description Rimba Media SB Description Rimba Media SB Description Rimba Media SB Description Rimba Media SB'),(41,'config','fb_first_name','Rimba'),(42,'config','fb_last_name','Media'),(43,'config','fb_username','@RimbaMediaSb'),(44,'config','tw_creator','@RimbaMediaSb'),(45,'config','fb_admins','facebook_ID'),(46,'general','project_done','1'),(47,'general','happy_client','1'),(48,'general','our_employees','1'),(49,'image','img_home_service','gallery/img1.jpg'),(50,'image','img_home_testimoni','gallery/20180416064158.png'),(51,'image','img_breadcrumb','gallery/20180623134741.jpg'),(52,'image','img_maintenance','gallery/20180416064158.png');

/*Table structure for table `cms_subscribe` */

DROP TABLE IF EXISTS `cms_subscribe`;

CREATE TABLE `cms_subscribe` (
  `subscribe_id` int(5) NOT NULL AUTO_INCREMENT,
  `subscribe_email` varchar(100) NOT NULL,
  `subscribe_name` varchar(255) NOT NULL,
  `subscribe_active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`subscribe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `cms_subscribe` */

insert  into `cms_subscribe`(`subscribe_id`,`subscribe_email`,`subscribe_name`,`subscribe_active`) values (1,'gedankgorenk@rocketmail.com','hesti','Y');

/*Table structure for table `cms_tag` */

DROP TABLE IF EXISTS `cms_tag`;

CREATE TABLE `cms_tag` (
  `tag_id` int(5) NOT NULL AUTO_INCREMENT,
  `tag_title` varchar(100) NOT NULL,
  `tag_seotitle` varchar(100) NOT NULL,
  `tag_count` int(5) NOT NULL,
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `cms_tag` */

insert  into `cms_tag`(`tag_id`,`tag_title`,`tag_seotitle`,`tag_count`,`update_user`,`update_timestamp`) values (1,'health','health',6,'hesti','2018-04-02 15:03:48'),(2,'eat clean','eat-clean',5,'hesti','2018-04-02 15:03:48'),(3,'eat local','eat-local',6,'hesti','2018-04-02 15:03:48'),(4,'nutrition','nutrition',4,'hesti','2018-04-02 15:04:16'),(5,'i quit sugar','i-quit-sugar',2,'hesti','2018-04-02 15:06:23'),(7,'service','service',4,'hesti','2018-04-17 14:23:36'),(9,'pelayanan','pelayanan',4,'hesti','2018-04-17 14:23:36'),(10,'professional team','professional-team',1,'hesti','2018-04-17 14:27:14');

/*Table structure for table `cms_testimoni` */

DROP TABLE IF EXISTS `cms_testimoni`;

CREATE TABLE `cms_testimoni` (
  `testimoni_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `testimoni_name` varchar(255) DEFAULT NULL,
  `testimoni_text` text,
  `testimoni_job` varchar(100) DEFAULT NULL,
  `testimoni_picture` varchar(150) DEFAULT NULL,
  `testimoni_active` enum('Y','N') DEFAULT 'N',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`testimoni_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `cms_testimoni` */

insert  into `cms_testimoni`(`testimoni_id`,`testimoni_name`,`testimoni_text`,`testimoni_job`,`testimoni_picture`,`testimoni_active`,`update_user`,`update_timestamp`) values (1,'David Max 1','Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.','Ceo & Founder','gallery/img1.jpg','Y','hesti','2018-06-23 17:08:33'),(2,'David Max 2','Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.','Ceo & Founder','gallery/img1.jpg','Y','hesti','2018-06-23 17:08:35'),(3,'David Max 3','Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec ac nec.','Ceo & Founder','','Y','hesti','2018-06-23 17:08:39');

/*Table structure for table `cms_theme` */

DROP TABLE IF EXISTS `cms_theme`;

CREATE TABLE `cms_theme` (
  `theme_id` int(5) NOT NULL AUTO_INCREMENT,
  `theme_title` varchar(50) NOT NULL,
  `theme_author` varchar(50) NOT NULL,
  `theme_folder` varchar(20) NOT NULL,
  `theme_active` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `cms_theme` */

insert  into `cms_theme`(`theme_id`,`theme_title`,`theme_author`,`theme_folder`,`theme_active`) values (1,'Rimba','hesti','rimba','N');

/*Table structure for table `cms_user` */

DROP TABLE IF EXISTS `cms_user`;

CREATE TABLE `cms_user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_real_name` varchar(255) DEFAULT NULL,
  `user_user_name` varchar(50) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_desc` text,
  `user_no_password` tinyint(1) DEFAULT '0',
  `user_active` enum('Y','N') DEFAULT 'N',
  `user_force_logout` tinyint(1) DEFAULT '0',
  `user_active_lang_code` varchar(5) NOT NULL,
  `user_last_logged_in` datetime DEFAULT '0000-00-00 00:00:00',
  `user_last_ip` varchar(15) DEFAULT NULL,
  `insert_user` varchar(50) DEFAULT NULL,
  `insert_timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `user_skin` varchar(20) DEFAULT 'blue',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `Uniqe_username` (`user_user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `cms_user` */

insert  into `cms_user`(`user_id`,`user_real_name`,`user_user_name`,`user_email`,`user_password`,`user_desc`,`user_no_password`,`user_active`,`user_force_logout`,`user_active_lang_code`,`user_last_logged_in`,`user_last_ip`,`insert_user`,`insert_timestamp`,`update_user`,`update_timestamp`,`user_skin`) values (1,'hesti wahyu nugroho','hesti','hesti@gmail.com','dd903de671a906601e2c887f383be291','',0,'Y',0,'1','2018-06-23 14:01:03','127.0.0.1','hesti','0000-00-00 00:00:00','hesti','2018-06-23 15:10:34','yellow');

/*Table structure for table `cms_user_group` */

DROP TABLE IF EXISTS `cms_user_group`;

CREATE TABLE `cms_user_group` (
  `usergroup_user_id` bigint(20) NOT NULL,
  `usergroup_group_id` bigint(20) NOT NULL,
  KEY `usergroup_user_id` (`usergroup_user_id`,`usergroup_group_id`),
  CONSTRAINT `cms_user_group_ibfk_1` FOREIGN KEY (`usergroup_user_id`) REFERENCES `cms_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cms_user_group` */

insert  into `cms_user_group`(`usergroup_user_id`,`usergroup_group_id`) values (1,1);

/*Table structure for table `cms_visitor` */

DROP TABLE IF EXISTS `cms_visitor`;

CREATE TABLE `cms_visitor` (
  `visitor_id` int(5) NOT NULL AUTO_INCREMENT,
  `visitor_ip` varchar(20) DEFAULT NULL,
  `visitor_os` varchar(30) DEFAULT NULL,
  `visitor_browser` varchar(120) DEFAULT NULL,
  `visitor_hits` int(10) DEFAULT NULL,
  `visitor_date` date DEFAULT NULL,
  PRIMARY KEY (`visitor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `cms_visitor` */

insert  into `cms_visitor`(`visitor_id`,`visitor_ip`,`visitor_os`,`visitor_browser`,`visitor_hits`,`visitor_date`) values (1,'127.0.0.1','Linux','Mozilla Firefox v.59.0',62,'2018-04-12'),(2,'127.0.0.1','Linux','Mozilla Firefox v.59.0',4,'2018-04-01'),(3,'127.0.0.1','Linux','Mozilla Firefox v.59.0',2,'2018-04-02'),(4,'127.0.0.1','Linux','Mozilla Firefox v.59.0',1,'2018-04-03'),(5,'127.0.0.2','Linux','Mozilla Firefox v.59.0',5,'2018-04-01'),(6,'127.0.0.3','Linux','Mozilla Firefox v.59.0',3,'2018-04-01'),(7,'127.0.0.2','Linux','Mozilla Firefox v.59.0',2,'2018-04-02'),(8,'127.0.0.3','Linux','Mozilla Firefox v.59.0',4,'2018-04-02'),(9,'127.0.0.1','Linux','Mozilla Firefox v.59.0',4,'2018-04-04'),(10,'127.0.0.1','Linux','Mozilla Firefox v.59.0',4,'2018-03-01'),(11,'127.0.0.1','Linux','Mozilla Firefox v.59.0',2,'2018-03-02'),(12,'127.0.0.1','Linux','Mozilla Firefox v.59.0',1,'2018-03-03'),(13,'127.0.0.2','Linux','Mozilla Firefox v.59.0',5,'2018-03-01'),(14,'127.0.0.3','Linux','Mozilla Firefox v.59.0',3,'2018-03-01'),(15,'127.0.0.2','Linux','Mozilla Firefox v.59.0',2,'2018-02-02'),(16,'127.0.0.3','Linux','Mozilla Firefox v.59.0',4,'2018-02-02'),(17,'127.0.0.1','Linux','Mozilla Firefox v.59.0',4,'2018-02-04'),(18,'127.0.0.1','Linux','Mozilla Firefox v.59.0',309,'2018-04-13'),(19,'127.0.0.1','Linux','Mozilla Firefox v.59.0',309,'2018-04-13'),(20,'127.0.0.1','Linux','Mozilla Firefox v.59.0',134,'2018-04-14'),(21,'127.0.0.1','Linux','Mozilla Firefox v.59.0',344,'2018-04-16'),(22,'127.0.0.1','Linux','Mozilla Firefox v.59.0',97,'2018-04-17'),(23,'127.0.0.1','Linux','Mozilla Firefox v.59.0',50,'2018-04-18'),(24,'127.0.0.1','Linux','Mozilla Firefox v.59.0',64,'2018-04-19'),(25,'127.0.0.1','Linux','Mozilla Firefox v.59.0',1,'2018-04-23'),(26,'127.0.0.1','Linux','Mozilla Firefox v.59.0',3,'2018-04-25'),(27,'127.0.0.1','Linux','Mozilla Firefox v.59.0',7,'2018-04-26'),(28,'127.0.0.1','Linux','Mozilla Firefox v.59.0',6,'2018-05-09'),(29,'127.0.0.1','Linux','Mozilla Firefox v.59.0',6,'2018-05-10'),(30,'127.0.0.1','Linux','Mozilla Firefox v.59.0',5,'2018-05-19'),(31,'127.0.0.1','Linux','Mozilla Firefox v.60.0',70,'2018-06-11'),(32,'127.0.0.1','Linux','Mozilla Firefox v.60.0',422,'2018-06-19'),(33,'127.0.0.1','Linux','Mozilla Firefox v.60.0',251,'2018-06-22'),(34,'127.0.0.1','Linux','Mozilla Firefox v.60.0',232,'2018-06-23'),(35,'127.0.0.1','Linux','Mozilla Firefox v.60.0',232,'2018-06-23');

/*Table structure for table `cms_widget` */

DROP TABLE IF EXISTS `cms_widget`;

CREATE TABLE `cms_widget` (
  `widget_id` int(5) NOT NULL AUTO_INCREMENT,
  `widget_component_id` int(5) DEFAULT NULL,
  `widget_position` enum('L','R','B') DEFAULT 'L',
  `widget_title` varchar(100) DEFAULT NULL,
  `widget_text` text,
  `widget_sort` tinyint(3) DEFAULT NULL,
  `widget_active` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `cms_widget` */

insert  into `cms_widget`(`widget_id`,`widget_component_id`,`widget_position`,`widget_title`,`widget_text`,`widget_sort`,`widget_active`) values (1,1,'R','Archives',NULL,1,'Y'),(2,7,'R','Title','<b>Text or HTML</b>\r\n<p>Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>',2,'Y'),(3,7,'L','About CMS','<p>Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>',1,'Y'),(4,7,'B','About','<p>Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>',1,'Y'),(5,1,'B','Archives',NULL,2,'Y'),(6,2,'R','Search',NULL,0,'Y'),(7,3,'R','Subscribe',NULL,3,'Y'),(8,4,'R','Tag',NULL,4,'Y'),(9,5,'R','Post',NULL,5,'Y'),(10,6,'R','Category',NULL,6,'Y');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
