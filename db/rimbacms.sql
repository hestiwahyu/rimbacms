/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.31-MariaDB : Database - rimbacms
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `cms_category` */

insert  into `cms_category`(`category_id`,`category_parent_id`,`category_seotitle`,`category_picture`,`category_active`,`update_user`,`update_timestamp`) values (8,0,'category-1','','Y','admin','2018-08-21 09:12:28'),(9,0,'category-2','','Y','admin','2018-08-21 09:13:20'),(10,0,'category-3','','Y','admin','2018-08-21 09:13:34'),(11,9,'category-4','','Y','admin','2018-08-21 09:13:50');

/*Table structure for table `cms_category_text` */

DROP TABLE IF EXISTS `cms_category_text`;

CREATE TABLE `cms_category_text` (
  `category_text_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_id` int(5) NOT NULL,
  `category_lang_code` varchar(3) NOT NULL,
  `category_title` varchar(255) NOT NULL,
  PRIMARY KEY (`category_text_id`),
  UNIQUE KEY `category_id` (`category_id`,`category_lang_code`),
  KEY `lang_code` (`category_lang_code`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Data for the table `cms_category_text` */

insert  into `cms_category_text`(`category_text_id`,`category_id`,`category_lang_code`,`category_title`) values (55,9,'id','Kategori 2'),(57,10,'id','Kategori 3'),(53,8,'id','Kategori 1'),(59,11,'id','Kategori 4'),(54,8,'gb','Category 1'),(56,9,'gb','Category 2'),(58,10,'gb','Category 3'),(60,11,'gb','Category 4');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `cms_comment` */

insert  into `cms_comment`(`comment_id`,`comment_parent_id`,`comment_post_id`,`comment_name`,`comment_email`,`comment_url`,`comment_text`,`comment_date`,`comment_time`,`comment_active`,`comment_status`) values (1,0,3,'hesti wahyu nugroho','hesti.wahyu.nugroho@gmail.com','apakah-lorem-ipsum-itu','Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...','2018-08-21','04:31:33','Y','N'),(2,1,3,'hesti wahyu nugroho','hesti@gmail.com','apakah-lorem-ipsum-itu','There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...','2018-08-21','04:31:54','Y','Y');

/*Table structure for table `cms_component` */

DROP TABLE IF EXISTS `cms_component`;

CREATE TABLE `cms_component` (
  `component_id` int(5) NOT NULL AUTO_INCREMENT,
  `component` varchar(100) NOT NULL,
  `component_type` enum('component','widget') NOT NULL DEFAULT 'component',
  `component_datetime` datetime NOT NULL,
  `component_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`component_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `cms_component` */

insert  into `cms_component`(`component_id`,`component`,`component_type`,`component_datetime`,`component_active`) values (1,'archives','widget','2018-03-03 12:34:31','Y'),(2,'search','component','2018-03-06 14:39:39','Y'),(3,'subscribe','component','2018-03-06 14:48:28','Y'),(4,'tag','widget','2018-03-09 16:49:16','Y'),(5,'post','widget','2018-03-11 21:23:10','Y'),(6,'category','widget','2018-03-11 22:19:40','Y'),(7,'textarea','component','2018-03-28 12:02:13','Y');

/*Table structure for table `cms_contact` */

DROP TABLE IF EXISTS `cms_contact`;

CREATE TABLE `cms_contact` (
  `contact_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_message` text,
  `update_user` varchar(50) DEFAULT NULL,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `cms_contact` */

insert  into `cms_contact`(`contact_id`,`contact_name`,`contact_email`,`contact_message`,`update_user`,`update_timestamp`) values (1,'hesti','hesti@gmal.com','dadsada','hesti','2018-06-26 11:30:28');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `cms_gallery` */

insert  into `cms_gallery`(`gallery_id`,`gallery_title`,`gallery_seotitle`,`gallery_active`,`gallery_hits`,`update_user`,`update_timestamp`) values (1,'File Manager','file-manager','Y',2,'hesti','2018-04-05 21:48:27');

/*Table structure for table `cms_gallery_images` */

DROP TABLE IF EXISTS `cms_gallery_images`;

CREATE TABLE `cms_gallery_images` (
  `images_id` int(5) NOT NULL AUTO_INCREMENT,
  `images_gallery_id` int(5) NOT NULL,
  `images_title` varchar(255) NOT NULL,
  `images_content` text NOT NULL,
  `images_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`images_id`),
  KEY `images_gallery_id` (`images_gallery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `cms_gallery_images` */

insert  into `cms_gallery_images`(`images_id`,`images_gallery_id`,`images_title`,`images_content`,`images_picture`) values (1,1,'Logo Rimba Media dan Tulisan','logo rimba media dan tulisan','20180416064158.png'),(2,1,'breadcrumb','breadcrumb','20180623134741.jpg'),(3,1,'Logo Rimba Media','logo rimba media','20180626114254.jpg'),(4,1,'Logo Rimba Media Border','Logo Rimba Media Border','20180626114411.jpg');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Data for the table `cms_menu` */

insert  into `cms_menu`(`menu_id`,`menu_parent_id`,`menu_title`,`menu_url`,`menu_class`,`menu_position`,`menu_group_id`,`menu_active`,`menu_target`) values (1,0,'Beranda','/','',1,2,'Y','none'),(2,0,'Kategori','','',2,2,'Y',''),(3,2,'Kategori 1','/category/category-1','',1,2,'Y',''),(4,2,'Kategori 2','/category/category-2','',2,2,'Y',''),(5,0,'Galeri','/gallery','',3,2,'Y',''),(6,0,'Blog','/blog','',4,2,'Y',''),(7,0,'Tentang','/about','',5,2,'Y','none'),(8,0,'Kontak','/contacts','',6,2,'Y','none'),(9,0,'Home','/','',1,3,'Y','none'),(10,0,'Category','','',2,3,'Y',''),(11,10,'Category 1','/category/category-1','',1,3,'Y',''),(12,10,'Category 2','/category/category-2','',2,3,'Y',''),(13,0,'Gallery','/category/gallery','',3,3,'Y',''),(14,0,'Blog','/blog','',4,3,'Y',''),(15,0,'About','/about','',5,3,'Y','none'),(16,0,'Contact','/contacts','',6,3,'Y','none'),(17,0,'Dashboard','/adashboard','fa-home',1,1,'Y',''),(18,0,'Category','/acategory','fa-tag',2,1,'Y',''),(19,0,'Tag','/atag','fa fa-tags',3,1,'Y','none'),(20,0,'Post','/apost','fa-pencil',4,1,'Y','none'),(21,0,'Pages','/apages','fa-file',5,1,'Y','none'),(22,0,'Setting','','fa-cog',50,1,'Y',''),(23,22,'Menu','/amenu','fa-list',1,1,'Y','none'),(24,22,'Other','/asetting','fa-cogs',99,1,'Y',''),(25,22,'Theme','/atheme','fa-globe',3,1,'Y',''),(27,22,'Widget','/awidget','fa-th',5,1,'Y',''),(28,22,'Component','/acomponent','fa-cogs',4,1,'Y',''),(29,0,'Comment','/acomment','fa-comment',6,1,'Y',''),(30,0,'Subscribe','/asubscribe','fa-envelope',7,1,'Y',''),(31,22,'User','/auser','fa-user',2,1,'Y',''),(32,0,'Gallery','/agallery','fa-picture-o',8,1,'Y',''),(33,22,'Config and Route','/aconfig','fa-cogs',6,1,'Y',''),(34,0,'Testimoni','/atestimoni','fa-comments',9,1,'Y','');

/*Table structure for table `cms_menu_group` */

DROP TABLE IF EXISTS `cms_menu_group`;

CREATE TABLE `cms_menu_group` (
  `menu_group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menu_group_title` varchar(255) NOT NULL,
  PRIMARY KEY (`menu_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `cms_pages` */

insert  into `cms_pages`(`pages_id`,`pages_seotitle`,`pages_picture`,`pages_active`,`update_user`,`update_timestamp`) values (1,'about','gallery/20180626114411.jpg','Y','admin','2018-07-10 15:16:02'),(2,'contacts','','Y','hesti','2018-06-26 11:34:39');

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
  KEY `lang_code` (`pages_lang_code`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `cms_pages_text` */

insert  into `cms_pages_text`(`pages_text_id`,`pages_id`,`pages_lang_code`,`pages_title`,`pages_content`) values (13,2,'id','Kontak Kami','<p>Anda bisa mengunjungi markas kami di Ds. Diwak Rt 02/Rw 01, Kec. Bergas, Kab. Semarang 50552.</p>\r\n'),(14,2,'gb','Our Contacts','<p>You can visit our headquarters in Ds. Diwak Rt 02 / Rw 01, Kec. Bergas, Kab. Semarang 50552</p>\r\n'),(19,1,'id','Tentang Rimba Media','<p>Rimba Media merupakan sebuah penyedia jasa yang bergerak pada bidang konsultasi IT, Pembuatan Website (Company Profile), Pembuatan e-Office (Sistem Informasi Perkantoran), Pembuatan Aplikasi Android / IOS, dan Pembuatan Toko Online / E-Commerce.<br />\r\nSegmen utama kami adalah UKM (Usaha Kecil Menengah) dan Organisasi (Instansi Pemerintahan, LSM, Komunitas dan Perusahaan Profit maupun Non-Profit) bahkan personal yang membutuhkan solusi terbaik terhadap kebutuhan Teknologi.<br />\r\nKami mengutamakan Kualitas untuk mencapai Kepuasaan Anda dalam wajah solusi tepat terhadap kebutuhan Anda.</p>\r\n'),(20,1,'gb','About Rimba Media','<p>Rimba Media is a service provider engaged in IT consulting, Website Development (Company Profile), Making e-Office (Office of Information System), Making Android Applications / IOS, and Making Online Store / E-Commerce.<br />\r\nOur main segments are Small and Medium Enterprises (SMEs) and Organization (Government Agencies, Non-Governmental Organizations, Communities and Profit and Non-Profit Companies) and even those who need the best solution to Technology needs.<br />\r\nWe put Quality first to achieve your satisfaction in the face of the right solution to your needs.</p>\r\n');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post` */

insert  into `cms_post`(`post_id`,`post_seotitle`,`post_tag`,`post_date`,`post_time`,`post_publishdate`,`post_editor`,`post_headline`,`post_comment`,`post_picture`,`post_picture_desc`,`post_active`,`post_hits`,`update_user`,`update_timestamp`) values (3,'apakah-lorem-ipsum-itu','WebGIS,QGIS,Quantum GIS,MySQL','2018-02-14','15:57:00','2018-02-14 15:57:00',1,'Y','Y','gallery/20180626114254.jpg','pic desc','Y',16,'admin','2018-08-21 09:31:12'),(4,'dari-mana-asalnya','CMS,Website','2018-02-28','00:00:00','2018-02-28 00:00:00',1,'Y','N','gallery/20180626114254.jpg','','Y',5,'admin','2018-08-21 09:25:08'),(9,'mengapa-kita-menggunakannya','MySQL,CodeIgniter,Website,Sistem Informasi','2018-03-02','00:00:00','2018-03-02 00:00:00',1,'Y','N','gallery/20180626114254.jpg','','Y',5,'admin','2018-08-21 09:26:34'),(10,'dari-mana-saya-bisa-mendapatkannya','Android,Ionic,IOS','2018-04-17','00:00:00','2018-04-17 00:00:00',1,'Y','N','gallery/20180626114254.jpg','','Y',4,'admin','2018-08-21 09:27:36'),(11,'lorem-ipsum','e-Commerce,Online Shop','2018-04-17','00:00:00','2018-04-17 00:00:00',1,'Y','N','gallery/20180626114254.jpg','','Y',4,'admin','2018-08-21 09:33:14');

/*Table structure for table `cms_post_category` */

DROP TABLE IF EXISTS `cms_post_category`;

CREATE TABLE `cms_post_category` (
  `post_category_id` int(5) NOT NULL AUTO_INCREMENT,
  `post_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  PRIMARY KEY (`post_category_id`),
  KEY `category_id` (`category_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post_category` */

insert  into `cms_post_category`(`post_category_id`,`post_id`,`category_id`) values (95,3,8),(91,4,8),(92,9,8),(93,10,8),(96,11,8),(87,12,10),(88,13,10),(89,14,9),(97,11,9);

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
  KEY `lang_code` (`post_lang_code`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;

/*Data for the table `cms_post_text` */

insert  into `cms_post_text`(`post_text_id`,`post_id`,`post_lang_code`,`post_title`,`post_content`) values (153,3,'id','Apakah Lorem Ipsum itu?','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.</p>\r\n\r\n<p>Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makna salah satu kata latin yang dianggap paling tidak jelas, yakni consectetur, yang diambil dari salah satu bagian Lorem Ipsum. Setelah ia mencari maknanya di di literatur klasik, ia mendapatkan sebuah sumber yang tidak bisa diragukan. Lorem Ipsum berasal dari bagian 1.10.32 dan 1.10.33 dari naskah &quot;de Finibus Bonorum et Malorum&quot; (Sisi Ekstrim dari Kebaikan dan Kejahatan) karya Cicero, yang ditulis pada tahun 45 sebelum masehi. BUku ini adalah risalah dari teori etika yang sangat terkenal pada masa Renaissance. Baris pertama dari Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, berasal dari sebuah baris di bagian 1.10.32.</p>\r\n'),(154,3,'gb','What is Lorem Ipsum?','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n'),(145,4,'id','Dari mana asalnya?','<p>Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makna salah satu kata latin yang dianggap paling tidak jelas, yakni consectetur, yang diambil dari salah satu bagian Lorem Ipsum. Setelah ia mencari maknanya di di literatur klasik, ia mendapatkan sebuah sumber yang tidak bisa diragukan. Lorem Ipsum berasal dari bagian 1.10.32 dan 1.10.33 dari naskah &quot;de Finibus Bonorum et Malorum&quot; (Sisi Ekstrim dari Kebaikan dan Kejahatan) karya Cicero, yang ditulis pada tahun 45 sebelum masehi. BUku ini adalah risalah dari teori etika yang sangat terkenal pada masa Renaissance. Baris pertama dari Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, berasal dari sebuah baris di bagian 1.10.32.</p>\r\n\r\n<p>Bagian standar dari teks Lorem Ipsum yang digunakan sejak tahun 1500an kini di reproduksi kembali di bawah ini untuk mereka yang tertarik. Bagian 1.10.32 dan 1.10.33 dari &quot;de Finibus Bonorum et Malorum&quot; karya Cicero juga di reproduksi persis seperti bentuk aslinya, diikuti oleh versi bahasa Inggris yang berasal dari terjemahan tahun 1914 oleh H. Rackham.</p>\r\n'),(146,4,'gb','Where does it come from?','<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n'),(147,9,'id','Mengapa kita menggunakannya?','<p>Sudah merupakan fakta bahwa seorang pembaca akan terpengaruh oleh isi tulisan dari sebuah halaman saat ia melihat tata letaknya. Maksud penggunaan Lorem Ipsum adalah karena ia kurang lebih memiliki penyebaran huruf yang normal, ketimbang menggunakan kalimat seperti &quot;Bagian isi disini, bagian isi disini&quot;, sehingga ia seolah menjadi naskah Inggris yang bisa dibaca. Banyak paket Desktop Publishing dan editor situs web yang kini menggunakan Lorem Ipsum sebagai contoh teks. Karenanya pencarian terhadap kalimat &quot;Lorem Ipsum&quot; akan berujung pada banyak situs web yang masih dalam tahap pengembangan. Berbagai versi juga telah berubah dari tahun ke tahun, kadang karena tidak sengaja, kadang karena disengaja (misalnya karena dimasukkan unsur humor atau semacamnya)</p>\r\n\r\n<p>Ada banyak variasi tulisan Lorem Ipsum yang tersedia, tapi kebanyakan sudah mengalami perubahan bentuk, entah karena unsur humor atau kalimat yang diacak hingga nampak sangat tidak masuk akal. Jika anda ingin menggunakan tulisan Lorem Ipsum, anda harus yakin tidak ada bagian yang memalukan yang tersembunyi di tengah naskah tersebut. Semua generator Lorem Ipsum di internet cenderung untuk mengulang bagian-bagian tertentu. Karena itu inilah generator pertama yang sebenarnya di internet. Ia menggunakan kamus perbendaharaan yang terdiri dari 200 kata Latin, yang digabung dengan banyak contoh struktur kalimat untuk menghasilkan Lorem Ipsun yang nampak masuk akal. Karena itu Lorem Ipsun yang dihasilkan akan selalu bebas dari pengulangan, unsur humor yang sengaja dimasukkan, kata yang tidak sesuai dengan karakteristiknya dan lain sebagainya.</p>\r\n'),(149,10,'id','Dari mana saya bisa mendapatkannya?','<p>Ada banyak variasi tulisan Lorem Ipsum yang tersedia, tapi kebanyakan sudah mengalami perubahan bentuk, entah karena unsur humor atau kalimat yang diacak hingga nampak sangat tidak masuk akal. Jika anda ingin menggunakan tulisan Lorem Ipsum, anda harus yakin tidak ada bagian yang memalukan yang tersembunyi di tengah naskah tersebut. Semua generator Lorem Ipsum di internet cenderung untuk mengulang bagian-bagian tertentu. Karena itu inilah generator pertama yang sebenarnya di internet. Ia menggunakan kamus perbendaharaan yang terdiri dari 200 kata Latin, yang digabung dengan banyak contoh struktur kalimat untuk menghasilkan Lorem Ipsun yang nampak masuk akal. Karena itu Lorem Ipsun yang dihasilkan akan selalu bebas dari pengulangan, unsur humor yang sengaja dimasukkan, kata yang tidak sesuai dengan karakteristiknya dan lain sebagainya.</p>\r\n'),(150,10,'gb','Where can I get some?','<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>\r\n'),(155,11,'id','Lorem Ipsum','<p>Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.</p>\r\n\r\n<blockquote>\r\n<p>&quot;Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...&quot;</p>\r\n\r\n<p>&quot;Tidak ada yang menyukai kepedihan, yang mencarinya dan ingin merasakannya, semata karena pedih rasanya...&quot;</p>\r\n</blockquote>\r\n\r\n<p>Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih dari 2000 tahun. Richard McClintock, seorang professor Bahasa Latin dari Hampden-Sidney College di Virginia, mencoba mencari makna salah satu kata latin yang dianggap paling tidak jelas, yakni consectetur, yang diambil dari salah satu bagian Lorem Ipsum. Setelah ia mencari maknanya di di literatur klasik, ia mendapatkan sebuah sumber yang tidak bisa diragukan. Lorem Ipsum berasal dari bagian 1.10.32 dan 1.10.33 dari naskah &quot;de Finibus Bonorum et Malorum&quot; (Sisi Ekstrim dari Kebaikan dan Kejahatan) karya Cicero, yang ditulis pada tahun 45 sebelum masehi. BUku ini adalah risalah dari teori etika yang sangat terkenal pada masa Renaissance. Baris pertama dari Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, berasal dari sebuah baris di bagian 1.10.32.</p>\r\n\r\n<p>Bagian standar dari teks Lorem Ipsum yang digunakan sejak tahun 1500an kini di reproduksi kembali di bawah ini untuk mereka yang tertarik. Bagian 1.10.32 dan 1.10.33 dari &quot;de Finibus Bonorum et Malorum&quot; karya Cicero juga di reproduksi persis seperti bentuk aslinya, diikuti oleh versi bahasa Inggris yang berasal dari terjemahan tahun 1914 oleh H. Rackham.</p>\r\n'),(156,11,'gb','Lorem Ipsum','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<blockquote>\r\n<p>&quot;Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...&quot;</p>\r\n\r\n<p>&quot;There is no one who loves pain itself, who seeks after it and wants to have it, simply because it is pain...&quot;</p>\r\n</blockquote>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n'),(137,12,'id','Badan Kesatuan Bangsa dan Politik <span>(kesbangpol)</span> Jateng',''),(138,12,'gb','Badan Kesatuan Bangsa dan Politik <span>(kesbangpol)</span> Jateng',''),(139,13,'id','Dinas Ketahanan Pangan (dishanpan) Jateng',''),(140,13,'gb','Dinas Ketahanan Pangan (dishanpan) Jateng',''),(141,14,'id','Rimba CMS','<p>isi</p>\r\n'),(142,14,'gb','Rimba CMS','<p>content</p>\r\n'),(148,9,'gb','Why do we use it?','<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n\r\n<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don&#39;t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn&#39;t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>\r\n');

/*Table structure for table `cms_setting` */

DROP TABLE IF EXISTS `cms_setting`;

CREATE TABLE `cms_setting` (
  `setting_id` int(5) NOT NULL AUTO_INCREMENT,
  `setting_groups` varchar(50) NOT NULL,
  `setting_options` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

/*Data for the table `cms_setting` */

insert  into `cms_setting`(`setting_id`,`setting_groups`,`setting_options`,`setting_value`) values (1,'general','web_name','Rimba CMS'),(2,'general','web_url','https://rimbamedia.com'),(3,'general','address','Ds. Diwak Rt 02/Rw 01, Kec. Bergas, Kab. Semarang 50552'),(4,'general','web_keyword','Rimba Media, RimbaMedia, CMS, Rimba Media CMS'),(5,'general','web_owner','Rimba Media'),(6,'general','email','info@rimbamedia.com'),(7,'general','telephone','(+62) 821 1533 1455'),(8,'general','fax','000-0000-0000'),(9,'general','longitude','110.4267757'),(10,'general','latitude','-7.19834'),(11,'image','favicon','fav.png'),(12,'image','logo','logo.png'),(13,'config','img_thumb','238x206'),(14,'local','country','Indonesia'),(15,'local','region_state','Yogyakarta'),(16,'local','timezone','Asia/Jakarta'),(17,'config','maintenance','N'),(18,'config','member_registration','N'),(19,'config','comment','N'),(20,'config','item_per_page','10'),(21,'config','google_analytics',''),(22,'config','recaptcha_sitekey','6LckEgETAAAAAPdqrQSY_boMDLZRL1vpkAatVqKf'),(23,'config','recaptcha_secretkey','6LckEgETAAAAAHqx4VFD4zNL96P9UEikD8BHfT28'),(24,'mail','mail_protocol','SSL'),(25,'mail','mail_hostname','smtp.gmail.com'),(26,'mail','mail_username','rimbamediasb@gmail.com'),(27,'mail','mail_password','pass'),(28,'mail','mail_port','465'),(29,'config','permalink','slug/post-title'),(30,'config','slug_permalink','detailpost'),(31,'image','img_header','gallery/20180416064158.png'),(32,'general','app_version','1.0.0'),(33,'general','app_name','RimbaCMS'),(34,'general','app_year','2018'),(35,'config','img_size','1024'),(36,'config','img_ext','jpg,png,gif'),(37,'file','compress_size','5120'),(38,'file','compress_ext','zip'),(39,'general','postal_code','50552'),(40,'general','web_description','Penyedia Jasa Teknologi Informasi yang handal dan terpercaya serta berpengalaman untuk selalu siap memberikan solusi terbaik dalam membantu Anda menghadirkan segalanya sesuai kebutuhan. '),(41,'config','fb_first_name','Rimba'),(42,'config','fb_last_name','Media'),(43,'config','fb_username','@RimbaMediaSb'),(44,'config','tw_creator','@RimbaMediaSb'),(45,'config','fb_admins','facebook_ID'),(46,'general','project_done','3'),(47,'general','happy_client','3'),(48,'general','our_product','4'),(49,'image','img_home_service','gallery/img1.jpg'),(50,'image','img_home_testimoni','gallery/20180416064158.png'),(51,'image','img_breadcrumb','gallery/20180623134741.jpg'),(52,'image','img_maintenance','gallery/20180416064158.png'),(53,'config','google_api_key','AIzaSyALoOfqigwuD2OBwCC4sHMdFpbAWaH3PNA'),(54,'general','lang_default','id');

/*Table structure for table `cms_subscribe` */

DROP TABLE IF EXISTS `cms_subscribe`;

CREATE TABLE `cms_subscribe` (
  `subscribe_id` int(5) NOT NULL AUTO_INCREMENT,
  `subscribe_email` varchar(100) NOT NULL,
  `subscribe_name` varchar(255) NOT NULL,
  `subscribe_active` enum('Y','N') DEFAULT 'Y',
  `subscribe_instansi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`subscribe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `cms_subscribe` */

insert  into `cms_subscribe`(`subscribe_id`,`subscribe_email`,`subscribe_name`,`subscribe_active`,`subscribe_instansi`) values (1,'hesti.wahyu.nugroho@gmail.com','hesti','Y','swasta');

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `cms_tag` */

insert  into `cms_tag`(`tag_id`,`tag_title`,`tag_seotitle`,`tag_count`,`update_user`,`update_timestamp`) values (1,'Rimba Media Sb','rimba-media-sb',1,'hesti','2018-06-26 12:11:08'),(2,'WebGIS','webgis',1,'hesti','2018-06-26 12:27:12'),(3,'QGIS','qgis',1,'hesti','2018-06-26 12:27:18'),(4,'Quantum GIS','quantum-gis',1,'hesti','2018-06-26 12:12:05'),(5,'MySQL','mysql',2,'hesti','2018-06-26 12:12:29'),(7,'CodeIgniter','codeigniter',1,'hesti','2018-06-26 12:12:49'),(9,'Google Maps','google-maps',0,'hesti','2018-06-26 12:13:11'),(10,'CMS','cms',2,'hesti','2018-06-26 12:13:38'),(11,'Website','website',5,'hesti','2018-06-26 12:13:47'),(12,'PMapper','pmapper',0,'hesti','2018-06-26 12:14:10'),(13,'SHP','shp',0,'hesti','2018-06-26 12:15:03'),(14,'PHP','php',0,'hesti','2018-06-26 12:15:11'),(15,'Javascript','javascript',0,'hesti','2018-06-26 12:15:24'),(16,'Query','query',0,'hesti','2018-06-26 12:15:37'),(17,'JQuery','jquery',0,'hesti','2018-06-26 12:15:57'),(18,'Android','android',2,'hesti','2018-06-26 12:16:13'),(19,'Ionic','ionic',2,'hesti','2018-06-26 12:16:35'),(20,'Company Profile','company-profile',0,'hesti','2018-06-26 12:33:40'),(21,'Sistem Informasi','sistem-informasi',1,'hesti','2018-06-26 12:56:13'),(22,'E-Office','e-office',0,'hesti','2018-06-26 12:56:24'),(23,'IOS','ios',2,'hesti','2018-06-26 13:00:18'),(24,'e-Commerce','e-commerce',2,'hesti','2018-06-26 13:03:14'),(25,'Online Shop','online-shop',2,'hesti','2018-06-26 13:03:14');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `cms_testimoni` */

insert  into `cms_testimoni`(`testimoni_id`,`testimoni_name`,`testimoni_text`,`testimoni_job`,`testimoni_picture`,`testimoni_active`,`update_user`,`update_timestamp`) values (1,'Hesti Wahyu Nugroho','Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.','Programmer 3','','Y','admin','2018-08-21 09:16:38'),(2,'Hesti Wahyu Nugroho','Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.','Programmer 2','','Y','admin','2018-08-21 09:16:17'),(3,'Hesti Wahyu Nugroho','Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf. Ia tidak hanya bertahan selama 5 abad, tapi juga telah beralih ke penataan huruf elektronik, tanpa ada perubahan apapun. Ia mulai dipopulerkan pada tahun 1960 dengan diluncurkannya lembaran-lembaran Letraset yang menggunakan kalimat-kalimat dari Lorem Ipsum, dan seiring munculnya perangkat lunak Desktop Publishing seperti Aldus PageMaker juga memiliki versi Lorem Ipsum.','Programmer 1','','Y','admin','2018-08-21 09:16:01');

/*Table structure for table `cms_theme` */

DROP TABLE IF EXISTS `cms_theme`;

CREATE TABLE `cms_theme` (
  `theme_id` int(5) NOT NULL AUTO_INCREMENT,
  `theme_title` varchar(50) NOT NULL,
  `theme_author` varchar(50) NOT NULL,
  `theme_folder` varchar(20) NOT NULL,
  `theme_active` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `cms_theme` */

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `cms_user` */

insert  into `cms_user`(`user_id`,`user_real_name`,`user_user_name`,`user_email`,`user_password`,`user_desc`,`user_no_password`,`user_active`,`user_force_logout`,`user_active_lang_code`,`user_last_logged_in`,`user_last_ip`,`insert_user`,`insert_timestamp`,`update_user`,`update_timestamp`,`user_skin`) values (1,'admin','admin','admin@gmail.com','f3f1c26545d2424e5bbc7bf12a8f2dc6','',0,'Y',0,'1','2018-08-21 04:05:03','127.0.0.1','hesti','0000-00-00 00:00:00','admin','2018-08-21 09:35:54','green');

/*Table structure for table `cms_user_group` */

DROP TABLE IF EXISTS `cms_user_group`;

CREATE TABLE `cms_user_group` (
  `usergroup_user_id` bigint(20) NOT NULL,
  `usergroup_group_id` bigint(20) NOT NULL,
  KEY `usergroup_user_id` (`usergroup_user_id`,`usergroup_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `cms_user_group` */

insert  into `cms_user_group`(`usergroup_user_id`,`usergroup_group_id`) values (1,1),(2,1);

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
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Data for the table `cms_visitor` */

insert  into `cms_visitor`(`visitor_id`,`visitor_ip`,`visitor_os`,`visitor_browser`,`visitor_hits`,`visitor_date`) values (1,'120.188.77.46','Linux','Mozilla Firefox v.60.0',30,'2018-07-12'),(2,'66.249.77.24','Unknown','Unknown v.?',8,'2018-07-12'),(3,'180.76.15.137','Unknown','Unknown v.?',1,'2018-07-12'),(4,'66.249.77.23','Unknown','Unknown v.?',24,'2018-07-12'),(5,'66.249.77.25','Unknown','Unknown v.?',9,'2018-07-12'),(6,'66.249.79.135','Unknown','Unknown v.?',6,'2018-07-12'),(7,'66.249.79.139','Unknown','Unknown v.?',2,'2018-07-12'),(8,'66.249.71.73','Unknown','Unknown v.?',6,'2018-07-12'),(9,'66.249.71.75','Unknown','Unknown v.?',6,'2018-07-12'),(10,'66.249.79.137','Unknown','Unknown v.?',1,'2018-07-12'),(11,'120.188.87.116','Linux','Google Chrome v.65.0.3325.109',2,'2018-07-12'),(12,'120.188.87.0','Linux','Google Chrome v.38.0.1025.166',2,'2018-07-12'),(13,'66.249.71.77','Unknown','Unknown v.?',2,'2018-07-12'),(14,'180.248.219.200','Windows 7','Mozilla Firefox v.61.0',8,'2018-07-12'),(15,'120.188.87.83','Linux','Google Chrome v.38.0.1025.166',7,'2018-07-12'),(16,'66.249.77.24','Unknown','Unknown v.?',5,'2018-07-13'),(17,'66.249.77.25','Unknown','Unknown v.?',4,'2018-07-13'),(18,'66.249.71.73','Unknown','Unknown v.?',2,'2018-07-13'),(19,'120.188.85.234','Linux','Mozilla Firefox v.60.0',14,'2018-07-13'),(20,'115.178.253.7','Linux','Google Chrome v.67.0.3396.87',3,'2018-07-13'),(21,'180.76.15.143','Windows XP','Mozilla Firefox v.6.0.2',1,'2018-07-13'),(22,'180.76.15.28','Windows XP','Mozilla Firefox v.6.0.2',1,'2018-07-13'),(23,'173.252.85.205','Unknown','Unknown v.?',1,'2018-07-13'),(24,'173.252.84.56','Unknown','Unknown v.?',1,'2018-07-13'),(25,'66.249.71.77','Unknown','Unknown v.?',2,'2018-07-13'),(26,'66.249.77.23','Unknown','Unknown v.?',2,'2018-07-13'),(27,'66.249.77.23','Unknown','Unknown v.?',19,'2018-07-14'),(28,'66.249.77.25','Unknown','Unknown v.?',17,'2018-07-14'),(29,'66.249.77.24','Unknown','Unknown v.?',9,'2018-07-14'),(30,'66.249.71.77','Unknown','Unknown v.?',8,'2018-07-14'),(31,'66.249.71.73','Unknown','Unknown v.?',6,'2018-07-14'),(32,'66.249.71.75','Unknown','Unknown v.?',8,'2018-07-14'),(33,'114.4.223.77','Linux','Google Chrome v.65.0.3325.109',1,'2018-07-14'),(34,'180.76.15.154','Unknown','Unknown v.?',1,'2018-07-14'),(35,'66.249.79.135','Unknown','Unknown v.?',1,'2018-07-14'),(36,'66.249.79.139','Unknown','Unknown v.?',1,'2018-07-14'),(37,'66.249.71.73','Unknown','Unknown v.?',3,'2018-07-15'),(38,'66.249.77.24','Unknown','Unknown v.?',1,'2018-07-15'),(39,'66.249.73.81','Unknown','Unknown v.?',1,'2018-07-15'),(40,'66.249.77.25','Unknown','Unknown v.?',1,'2018-07-15'),(41,'66.220.156.21','Windows 7','Google Chrome v.67.0.3396.99',1,'2018-07-15'),(42,'66.249.71.75','Unknown','Unknown v.?',3,'2018-07-15'),(43,'120.188.77.27','Linux','Google Chrome v.4.0',1,'2018-07-15'),(44,'120.92.11.224','Windows 10','Google Chrome v.46.0.2486.0',1,'2018-07-15'),(45,'101.226.79.235','Mac OS X','Google Chrome v.55.0.2883.95',1,'2018-07-15'),(46,'180.76.15.140','Unknown','Unknown v.?',1,'2018-07-15'),(47,'120.188.4.234','Linux','Google Chrome v.4.0',1,'2018-07-15'),(48,'114.124.164.112','Linux','Google Chrome v.4.0',1,'2018-07-15'),(49,'66.249.77.23','Unknown','Unknown v.?',1,'2018-07-15'),(50,'120.188.77.168','Linux','Google Chrome v.4.0',12,'2018-07-15'),(51,'120.188.87.127','Linux','Google Chrome v.38.0.1025.166',1,'2018-07-15'),(52,'182.0.197.95','Linux','Google Chrome v.4.0',1,'2018-07-15'),(53,'182.0.198.131','Linux','Google Chrome v.4.0',1,'2018-07-15'),(54,'69.171.225.30','Unknown','Unknown v.1.1',1,'2018-07-15'),(55,'180.76.15.138','Unknown','Unknown v.?',1,'2018-07-15'),(56,'114.142.171.44','Linux','Google Chrome v.4.0',3,'2018-07-15'),(57,'66.249.71.73','Unknown','Unknown v.?',2,'2018-07-16'),(58,'66.249.71.75','Unknown','Unknown v.?',2,'2018-07-16'),(59,'125.163.230.215','Unknown','Google Chrome v.67.0.3396.99',8,'2018-07-16'),(60,'207.241.232.121','Unknown','Unknown v.?',1,'2018-07-16'),(61,'180.76.15.19','Unknown','Unknown v.?',1,'2018-07-16'),(62,'127.0.0.1','Linux','Mozilla Firefox v.60.0',6,'2018-07-17'),(63,'127.0.0.1','Linux','Mozilla Firefox v.60.0',18,'2018-07-25'),(64,'127.0.0.1','Linux','Mozilla Firefox v.60.0',36,'2018-07-26'),(65,'127.0.0.1','Linux','Mozilla Firefox v.60.0',19,'2018-07-27'),(66,'127.0.0.1','Linux','Mozilla Firefox v.60.0',74,'2018-08-21');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `cms_widget` */

insert  into `cms_widget`(`widget_id`,`widget_component_id`,`widget_position`,`widget_title`,`widget_text`,`widget_sort`,`widget_active`) values (1,1,'R','Archives',NULL,1,'Y'),(2,7,'R',NULL,'<div class=\"fb-page\" data-href=\"https://www.facebook.com/RimbaMediaSb/\" data-tabs=\"timeline\" data-small-header=\"false\" data-adapt-container-width=\"true\" data-hide-cover=\"false\" data-show-facepile=\"true\"><blockquote cite=\"https://www.facebook.com/RimbaMediaSb/\" class=\"fb-xfbml-parse-ignore\"><a href=\"https://www.facebook.com/RimbaMediaSb/\">Rimba Media</a></blockquote></div>\r\n<div id=\"fb-root\"></div>\r\n<script>(function(d, s, id) {\r\n  var js, fjs = d.getElementsByTagName(s)[0];\r\n  if (d.getElementById(id)) return;\r\n  js = d.createElement(s); js.id = id;\r\n  js.src = \'https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v3.0&appId=789972341083504&autoLogAppEvents=1\';\r\n  fjs.parentNode.insertBefore(js, fjs);\r\n}(document, \'script\', \'facebook-jssdk\'));</script>',2,'Y'),(3,7,'L','Quote','Hidup ini sangatlah singkat. Kita tidak bisa menjamin masih hidup sampai waktu shalat berikutnya. Maka, mulai sekarang berdoalah, dan berdoalah setiap kali shalat seolah-olah itu adalah shalat terakhirmu.\r\n',1,'Y'),(4,7,'B','About','<p>Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>',1,'Y'),(5,1,'B','Archives',NULL,2,'Y'),(6,2,'R','Search',NULL,0,'Y'),(7,3,'R','Subscribe',NULL,3,'Y'),(8,4,'R','Tag',NULL,4,'Y'),(9,5,'R','Post',NULL,5,'Y'),(10,6,'R','Category',NULL,6,'Y');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
