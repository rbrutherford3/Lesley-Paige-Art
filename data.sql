-- MySQL dump 10.17  Distrib 10.3.23-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: sql184.main-hosting.eu    Database: u456574594_lesleypaige
-- ------------------------------------------------------
-- Server version	10.4.13-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `biography`
--

DROP TABLE IF EXISTS `biography`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biography` (
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biography`
--

LOCK TABLES `biography` WRITE;
/*!40000 ALTER TABLE `biography` DISABLE KEYS */;
INSERT INTO `biography` VALUES ('Lesley Paige Rutherford grew up in McLean, Virginia, a suburb of Washington DC. Her mother exposed her to art at a young age. Lesley took several art classes as a child, including courses at the Corcoran Gallery of Art. Once in college, at Denison University in Ohio, Lesley decided to major in Studio Art with an emphasis in photography. She graduated from Denison in 1997.\r\n\r\nIn 1998, Lesley moved to Los Angeles. Although she created a series of paintings during this time period, she decided to pursue a career in education. She earned her teaching credential and a Master of Arts in special education from California State University, Los Angeles. Lesley was hired as an elementary school teacher in Los Angeles.\r\n\r\nIn 2007, Lesley returned to art by creating a series of mixed media drawings. She drew these pieces with black ink and watercolor pencil. This art was displayed in Kaldi Coffee Shop in South Pasadena, California, where it was spotted by set designers for NBC\'s hit drama series, \"Parenthood.\" \"Parenthood\" purchased Lesley\'s entire portfolio and displayed many of the pieces on the show.\r\n\r\nLesley now works in colored pencil. For inspiration, she takes photographs throughout the city of Los Angeles. Lesley is particularly drawn to flea markets and farmers\' markets. She attempts to capture vibrant colors and dynamic displays. She also created a series of drawings that focus on the architecture of Chinatown.\r\n\r\nMost of Lesley\'s drawings contain black lines and a bold outline framing the picture. Some of the objects in her drawings are shaded, while other objects are areas of solid color. Her background in photography enables her to create well-balanced compositions. She is acutely aware of compositional elements such as negative shapes. This is evident throughout her portfolio.\r\n\r\nLesley\'s door series is a compilation of colored pencil drawings of doors throughout the world.  Specifically, Lesley drew doors from her travels to Rio de Janeiro, Panama, and Ireland.  She also has a collection of doors from Los Angeles.  Her latest doors contain graffiti and other urban markings. It is her believe that these markings reflect urban culture and have a rich historical context. \r\n\r\nIn the future, Lesley hopes to travel to urban areas throughout the world to collect more photographs for her drawings. She wishes to highlight the similarities in cultures, such as the abundance of farmers\' markets. Her art is ultimately apolitical and created in order to be harmonious and aesthetically pleasing.');
/*!40000 ALTER TABLE `biography` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fontfamilies`
--

DROP TABLE IF EXISTS `fontfamilies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fontfamilies` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fontfamilies`
--

LOCK TABLES `fontfamilies` WRITE;
/*!40000 ALTER TABLE `fontfamilies` DISABLE KEYS */;
INSERT INTO `fontfamilies` VALUES (4,'fantasy'),(3,'monospaced'),(1,'sans-serif'),(5,'script'),(2,'serif');
/*!40000 ALTER TABLE `fontfamilies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fonts`
--

DROP TABLE IF EXISTS `fonts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fonts` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `family` tinyint(3) unsigned NOT NULL,
  `backup` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fonts`
--

LOCK TABLES `fonts` WRITE;
/*!40000 ALTER TABLE `fonts` DISABLE KEYS */;
INSERT INTO `fonts` VALUES (1,'Lesley',1,2),(2,'Arial',1,NULL),(3,'Arial Black',1,2),(4,'Arial Narrow',1,2),(5,'Tahoma',1,2),(6,'Trebuchet MS',1,2),(7,'Verdana',1,2),(8,'Georgia',2,11),(9,'Lucida Bright',2,11),(10,'Palatino',2,11),(11,'Times New Roman',2,NULL),(12,'Courier New',3,2),(13,'Lucida Sans Typewriter',3,2),(14,'Papyrus',4,2);
/*!40000 ALTER TABLE `fonts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info`
--

DROP TABLE IF EXISTS `info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `width` decimal(4,2) unsigned DEFAULT NULL,
  `height` decimal(4,2) unsigned DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sold` bit(2) DEFAULT NULL,
  `price` smallint(5) unsigned DEFAULT NULL,
  `fineartamerica` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etsy` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sequence` tinyint(3) unsigned DEFAULT NULL,
  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `md5` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `rotation` smallint(5) unsigned NOT NULL DEFAULT 0,
  `leftcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `rightcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `topcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `bottomcrop` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `filename` (`filename`),
  UNIQUE KEY `md5` (`md5`),
  UNIQUE KEY `sequence` (`sequence`),
  UNIQUE KEY `fineartamerica` (`fineartamerica`),
  UNIQUE KEY `etsy` (`etsy`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info`
--

LOCK TABLES `info` WRITE;
/*!40000 ALTER TABLE `info` DISABLE KEYS */;
INSERT INTO `info` VALUES (1,'California Boutique',8.25,10.00,2012,'I drew \"California Boutique\" from a photograph that I took of an alley boutique in South Pasadena, California. Although many of the items are similar to those in the photograph, I changed the entire color scheme. I gravitate towards purple and I drew this piece around the same time that I created \"Purple Artichokes.\"  Both \"Purple Artichokes\" and \"California Boutique\" contain fine lines although I typically draw bolder black lines throughout my compositions.',NULL,NULL,'https://fineartamerica.com/featured/california-boutique-lesley-rutherford.html','https://www.etsy.com/listing/119546518/california-boutique',36,'californiaboutique','ac0ec83b07e209c82d19b9d6194663d0',0,400,310,380,380),(2,'Onions',8.25,12.25,2013,'I drew this picture from a photograph taken in a farmers market in South Pasadena, California. I chose the rich purple background to complement the colors of the vegetables. This is one of my first drawings that contain bold black lines. I felt that the simplicity of the composition necessitated a bolder look.',NULL,NULL,'https://fineartamerica.com/featured/onions-lesley-rutherford.html',NULL,33,'onions','a47db92e798d9cdd03b30c5dc6b05b96',0,310,300,300,340),(3,'Chinatown Garden',8.50,12.25,2013,'I drew \"Chinatown Garden\" from a photograph I took of a garden in Chinatown, Los Angeles. I created \"Chinatown Garden\" after \"Hemingway\'s Garden.\" The two pictures have a similar color scheme and similar patterns. One of my friends once described my art as a juxtaposition of patterns. I think this description is most evident in this particular piece.','\0',NULL,'https://fineartamerica.com/featured/chinatown-garden-lesley-rutherford.html',NULL,24,'chinatowngarden','219d59bd72e94ce0dcf8349f22e9852b',0,320,280,280,340),(4,'Retro Colors',8.50,12.25,2013,'The inspiration for \"Retro Colors\" was a photograph that I took at a farmer\'s market in McLean, Virginia. The photograph contained the two plants but the background was a gingham blue sheet. I decided to change the pattern and color scheme to a 1970\'s retro look. I frequently use photographs for the basis of my drawings but alter the patterns and color schemes to create a new design.',NULL,NULL,'https://fineartamerica.com/featured/retro-colors-lesley-rutherford.html','https://www.etsy.com/listing/457255418/retro-colors',26,'retrocolors','06b4f272da84edd509e5a33408660df6',0,310,290,370,310),(5,'Peaches',8.25,12.25,2013,'\"Peaches\" is one drawing in my series of farmer\'s market pieces. I used purple for the shadows, a practice that is evident in many of my other drawings. I also chose to use purple to create the variances in the wood foreground. Initially, I wasn\'t very enthusiastic about this artwork. However, once I scanned it into digital form, I decided that it was one of my favorite pieces.',NULL,NULL,'https://fineartamerica.com/featured/peaches-lesley-rutherford.html',NULL,28,'peaches','609ac2e03f915e17ceaadad89ecb6cf7',0,320,320,400,380),(6,'Radishes',8.25,12.25,2013,'\"Radishes\" is one of my few wallpaper drawings. I like that I can change the format of the piece from landscape to portrait and vice versa. It can also be cropped to any size and the content of the picture remains intact. The dimensions of \"Radishes\" are the same as the dimensions of \"Peaches\" and \"Mushrooms.\" I was attempting to create a series with a consistent aesthetic.',NULL,NULL,'https://fineartamerica.com/featured/radishes-lesley-rutherford.html',NULL,31,'radishes','d5990b1a525a329cdce3b4f59dee1567',0,270,310,310,380),(7,'Aprons',9.25,14.00,2011,'\"Aprons\" has become one of my favorite drawings. I drew this piece from a photograph that I took at the Rose Bowl flea market in Pasadena, California. I changed the colors to create a limited pallet. In 2014, I touched up the drawing and created thicker black outlines. I did this with several of my pieces in order to develop a bolder aesthetic.',NULL,NULL,'https://fineartamerica.com/featured/aprons-lesley-rutherford.html','https://www.etsy.com/listing/457254540/aprons',38,'aprons','2f6e7d4d6db6cd6641241dfdac283a9f',0,300,265,295,310),(8,'Hemingway\'s Garden',8.00,14.00,2012,'I took a vacation to Miami, Florida during the Christmas break of 2011. My friends and I drove down to Key West and visited Ernest Hemingway\'s estate there. I took several photographs that day and one of them turned out to be the inspiration for this drawing. This piece is meant to complement \"Chinatown Garden\" because of the theme, the colors, and the patterns.\r\n\r\nThis is a test','',400,'https://fineartamerica.com/featured/hemingways-garden-lesley-rutherford.html',NULL,25,'hemingwaysgarden','d09fd1d76b2208cf2e67d2e60148c462',0,450,530,440,520),(9,'Fruit',8.25,12.00,2012,'I created \"Fruit\" from a photograph that I took at South Pasadena farmer\'s market. I changed the arrangement of the fruit and the design of the containers. I was pleased with the graphic effect that the final drawing has. It\'s similar to my patterns drawings in that it can be viewed either landscape or portrait.',NULL,NULL,'https://fineartamerica.com/featured/fruit-lesley-rutherford.html',NULL,30,'fruit','bcd19afce822b4a864810755e7d63278',90,440,370,410,320),(10,'Mushrooms',8.25,12.25,2012,'I created \"Mushrooms\" around the same time that I created \"Fruit.\" This is when I began to use thicker black lines in my drawings. I like to contrast the bold lines with shading and I think that is evident in this piece. I also like to use rich violet colors for the background or foreground. One thing that I like about \"Mushrooms\" is that it can be cropped many different ways to make interesting compositions.',NULL,NULL,'https://fineartamerica.com/featured/mushrooms-lesley-rutherford.html',NULL,29,'mushrooms','7808a299bffba12141c913471874e49c',0,300,270,300,340),(11,'Boots',12.50,15.50,2011,'Like \"Boutique\", \"Boots\" was drawn from a photograph that I took at a flea market.  I changed the color scheme and background in order to create a complementary aesthetic.  Although there are handbags in the drawing, I chose to title it \"Boots\" because the boots are the focal point of the image.  I touched this piece up in 2014 to make the colors and lines richer and bolder.',NULL,NULL,'https://fineartamerica.com/featured/boots-lesley-rutherford.html',NULL,39,'boots','2c35506b67829283d159ed764a8e123c',0,430,450,410,420),(12,'Boutique',10.00,14.00,2011,'I drew \"Boutique\" from a photograph I had of a stand at the flea market.  I changed the color scheme and the background.  I chose wood panels instead of the original metal grid walls because I thought it gave the picture a richer look.  I created \"Boutique\" and \"Boots\" around the same period and they are meant to complement one another.',NULL,NULL,'https://fineartamerica.com/featured/boutique-lesley-rutherford.html',NULL,NULL,'boutique','dc93513008ec31695247dfdf2b34cf37',0,370,370,400,370),(13,'Chinatown Facade',15.50,20.00,2011,'I created a Chinatown series in 2011.  All of the drawings were created from photographs that I took in Chinatown, Los Angeles.  I\'m presently in the process of touching these pieces up and \"Chinatown Facade\"` was the first one that I completed.  I was pleased with the graphic effect that I was able to create by drawing the Chinatown architecture.  This is currently the largest piece in my portfolio.',NULL,NULL,'https://fineartamerica.com/featured/chinatown-facade-lesley-rutherford.html',NULL,22,'chinatownfacade','49918e274d466746c09115990b05ea9d',0,360,350,390,390),(14,'Cupcakes',11.75,13.25,2013,'\"Cupcakes\" is a departure from my usual process and style.  I came across a cupcake shop in Glendale, California that had beautiful pastries and displays.  I didn\'t feel comfortable taking photographs in the store so I went home and looked through some images on the Internet.  I then created this piece from imagination.  Most of my artwork prior to 2011 was created by imagination so this was in some sense a throwback to my old style.',NULL,NULL,'https://fineartamerica.com/featured/cupcakes-lesley-rutherford.html',NULL,40,'cupcakes','c8808b1498bbab5381c22dcd09a4cb90',0,430,420,410,420),(15,'Jewelry',11.75,13.00,2011,'I drew this picture from a photograph that I took at the Rose Bowl flea market in Pasadena, California.  I loved the jewelry display and knew that I had to draw it.  I didn\'t change the content or color scheme dramatically with this picture.  I tried to pay close attention to the negative shapes that I was creating between the forms and the edge of the composition.',NULL,NULL,'https://fineartamerica.com/featured/jewelry-lesley-rutherford.html',NULL,37,'jewelry','983c89dfce1aff7f2f16155c48bfb218',0,410,400,410,410),(16,'Mystery Vegetables',16.75,9.25,2014,'I titled this piece \"Mystery Vegetables\" because I have no idea what kind of vegetables they are.  I took the photograph that was the inspiration for this piece at a farmer\'s market.  I forgot to ask the farmer what kind of vegetables they are.  Aesthetically, this drawing is very similar to \"Summer Harvest.\"  They both have shading combined with bold black lines in addition to being similar types of produce.',NULL,NULL,'https://fineartamerica.com/featured/mystery-vegetables-lesley-rutherford.html',NULL,32,'mysteryvegetables','ed0c947a3cd812c72d3f7a2ef2873778',0,290,300,230,250),(17,'Purple Artichokes',12.25,12.50,2012,'\"Purple Artichokes\" was a drawing that took me several months to complete.  The vegetables are composed of several layers of color with various shades.  When I was creating this drawing, I was thinking about how universal this type of artwork can be.  My aim was to create art that would be appealing to a wide audience spanning many cultures.',NULL,NULL,'https://fineartamerica.com/featured/purple-artichokes-lesley-rutherford.html',NULL,35,'purpleartichokes','25d1bf3b792e2dc1ff3d9d1942062d6c',0,400,370,400,400),(18,'Summer Harvest',15.00,12.00,2012,'I spent the summer of 2012 creating \"Summer Harvest.\"  It took me longer than many of my other drawings because of the shading and layers of color.  Initially, \"Summer Harvest\" had fine black lines outlining the vegetables.  However, in 2014 I decided to touch the picture up with bolder lines to give it more of a graphic effect.  I also wanted it to complement \"Mystery Vegetables\" which has similar lines.',NULL,NULL,'https://fineartamerica.com/featured/summer-harvest-lesley-rutherford.html',NULL,34,'summerharvest','d3d48380f549a6144e25076ab2c84aab',0,350,350,380,390),(19,'Sunflowers',7.25,10.25,2011,'\"Sunflowers\" was one of the first colored pencil drawings that I created.  All of my previous work was black ink with watercolor.  It\'s drawn from the perspective of looking down on a table with sunflowers and produce.  I recently redid \"Sunflowers\" in order to change the background from blue to violet and to create bolder black lines, the signature elements in my drawings.',NULL,NULL,'https://fineartamerica.com/featured/sunflowers-lesley-rutherford.html',NULL,27,'sunflowers','b6397d0287dcb76dfd4567e91213d826',0,400,400,400,400),(20,'Door #1',11.00,14.00,2016,'Door #1 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',350,'https://fineartamerica.com/featured/door-1-lesley-rutherford.html',NULL,14,'door1','c7ebc352e8cc333d26be29f2897a35cd',0,320,340,310,1240),(21,'Door #2',11.00,14.00,2016,'Door #2 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',350,'https://fineartamerica.com/featured/door-2-lesley-rutherford.html',NULL,15,'door2','bde15672da78972f19e8ee9d7573f128',0,210,220,210,240),(22,'Door #3',11.00,14.00,2016,'Door #3 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',350,'https://fineartamerica.com/featured/door-3-lesley-rutherford.html',NULL,16,'door3','517dd1c71de95d9d4ce933e64186a534',0,340,330,310,1230),(23,'Door #4',11.00,14.00,2016,'Door #4 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',350,'https://fineartamerica.com/featured/door-4-lesley-rutherford.html',NULL,17,'door4','73586c6438b955a5af31bfc35c508450',0,330,320,330,1220),(24,'Door #5',11.00,14.00,2016,'Door #5 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',NULL,'https://fineartamerica.com/featured/door-5-lesley-rutherford.html',NULL,18,'door5','039a3884e3423ac80c32d74a036a029e',0,330,335,330,1225),(25,'Door #6',11.00,14.00,2016,'Door #6 is one in a series of drawings that I created after visiting Panama City, Panama.  I spent a lot of time in the neighborhood of Casco Viejo.  I was struck by the distinction between the old and the newly renovated buildings.  I attempted to capture this contrast in the doors.  This is a series that I continue to work on and aim complete 12 pieces for.','',350,'https://fineartamerica.com/featured/door-6-lesley-rutherford.html',NULL,19,'door6','a63fab87e7e75deb861c26669c24b1e3',0,335,325,315,1245),(26,'Mangos and Melons',NULL,NULL,2016,'I drew \"Mangoes and Melons\" after taking a picture of the image at the grocery store.  I often find beautiful images in unexpected places at unexpected times.  This piece is similar to a drawing I did years ago of eggplants.  I gave it as a gift to a friend and it is not for sale.','',NULL,'https://fineartamerica.com/featured/market-display-lesley-rutherford.html',NULL,23,'mangosandmelons','75110aa282026f3a3f045fd7f1fae042',0,330,540,340,2000),(27,'Trepidation',11.00,14.00,2017,'This is a mixed media painting of a door in Rio de Janeiro.','',NULL,'https://fineartamerica.com/featured/trepidation-lesley-rutherford.html',NULL,8,'trepidation','e5c483cad8a6df94bf3340950297cb7d',0,240,240,260,220),(28,'Disintegration',12.00,12.00,2017,'This is a colored pencil drawing of a garage in Dublin, Ireland.  It is custom framed in a black wood frame with a two-inch silver mat.','',400,'https://fineartamerica.com/featured/disintegration-lesley-rutherford.html',NULL,7,'disintegration','56c5d4a94c0cc1e0d657badf7cde527c',0,330,310,310,310),(29,'Graffiti Door in Rio',11.00,14.00,2016,'This is a mixed media painting of a door in Rio de Janeiro.','',NULL,'https://fineartamerica.com/featured/graffiti-door-in-rio-lesley-rutherford.html',NULL,9,'graffitidoorinrio','ba9e8055f51b386db4df1a9108549f07',0,310,345,310,340),(30,'Door in Rural Ireland',11.00,14.00,2017,'This is a colored pencil drawing of a door in rural Ireland.  It is custom framed in a 13.5 x 16.5 black wooden frame.','',350,'https://fineartamerica.com/featured/door-in-rural-ireland-lesley-rutherford.html',NULL,10,'doorinruralireland','df032c2e6f425326e48c5eafab629806',0,310,330,310,330),(31,'Liberation',14.00,20.00,2018,'This is a colored pencil drawing of a wall in Highland Park, a neighborhood in North East Los Angeles.  I took the photograph that was inspiration for this piece while shopping on Highland Park\'s York Blvd. I was drawn to the graffiti and street art on this wall.  In addition to these unique markings, I found myself intrigued by the plant that was growing from a crack in the sidewalk. That plant and some of the graffiti text were the inspiration for the title, \"Liberation.\"','',NULL,'https://fineartamerica.com/featured/liberation-lesley-rutherford.html',NULL,5,'liberation','cd6001ec502392da2735a754a25eb069',0,380,440,410,390),(32,'Red Gate in Rio',11.00,14.00,2016,'This is a mixed media painting of an entrance gate in Rio de Janeiro. It is custom framed in a 13.5 x 16.5 black wooden frame.','',350,'https://fineartamerica.com/featured/red-gate-in-rio-lesley-rutherford.html',NULL,12,'redgateinrio','7617983cf433f86c50b72a50a8b260f3',0,200,216,207,200),(33,'Door #8',11.00,14.00,2015,'This is a colored pencil drawing of a door in Casco Viejo, a neighborhood in Panama City, Panama.  I took the photograph that was the inspiration for this piece while vacationing in Panama.  I was drawn to the textures in the original image and tried to replicate them in this work of art.  The image has a limited palette with predominantly earth tones.','',NULL,'https://fineartamerica.com/featured/door-8-lesley-rutherford.html',NULL,21,'door8','e91b31cdc41c921973ac5cd111fc0a26',90,830,210,200,220),(34,'Yellow Door in Panama',11.00,14.00,2015,'This is a colored pencil drawing a a door in Casco Viejo, a neighborhood in Panama City, Panama. It is custom framed in a 13.5 x 16.5 black wooden frame.','',350,'https://fineartamerica.com/featured/yellow-door-in-panama-lesley-rutherford.html',NULL,11,'yellowdoorinpanama','b62eca2d881aa767fdaa21fe59751327',90,1220,320,310,340),(35,'Door #7',11.00,14.00,2015,'This is a colored pencil drawing of a door in Casco Viejo, a neighborhood in Panama City, Panama.','',350,'https://fineartamerica.com/featured/door-7-lesley-rutherford.html',NULL,20,'door7','76be8ef8e8ef10dffcf896c91eab60e6',90,1240,320,320,340),(36,'Disorientation',12.00,12.00,2018,'This is a colored pencil drawing of a hardware store in Glendale, CA.  It is custom framed in a black wood frame with a two-inch mat.','',400,'https://fineartamerica.com/featured/disorientation-lesley-rutherford.html',NULL,6,'disorientation','df3e303d310b89d71ab5af89bf9d0bfa',0,330,310,315,340),(37,'Door on Selaron Steps',11.00,14.00,2017,'This is a colored pencil drawing of a door on the Selaron Steps in Rio de Janeiro.  It is custom framed in a 13.5 x 16.5 black wooden frame.','',350,'https://fineartamerica.com/featured/door-on-selaron-steps-in-rio-lesley-rutherford.html',NULL,13,'dooronselaronsteps','ea4aa62b9140b4c43b7f88c79c4e6255',0,310,325,305,1245),(38,'Incarceration',11.00,14.00,2020,'This is a mixed media painting of a door in El Sereno, a neighborhood of Los Angeles.  It is created from a photograph that I took with my iPhone while exploring this neighborhood with a friend.  I\'m drawn to doors and urban graffiti.  I was particularly interested in the cardboard figure in the screen door because I\'d never seen anything like that before.  It created a spooky but somewhat edgy image, something that I tried to convey in this piece.','',350,'https://fineartamerica.com/featured/incarceration-lesley-rutherford.html',NULL,1,'incarceration','bc1bb361c1fa6be2a7671e33fc3c0adc',0,190,210,210,210),(39,'Melrose Chic',11.00,11.00,2020,'This is a mixed media painting of a retail display on Melrose Avenue in Los Angeles.  I created this piece from a photograph that I took with my Canon Rebel camera shortly after receiving it as a gift.  I very much enjoy photographing urban scenes and it was fun and challenging to use an SLR camera for the first time in years.  I photographed these dresses because in my opinion, they capture the attitude of Melrose style perfectly.  I included the graffiti markings and stickers because they are common in this area of the city.  I added the potted plant and sign into my composition to create a balance that was lacking in the original photograph.','',300,'https://fineartamerica.com/featured/melrose-chic-lesley-rutherford.html',NULL,2,'melrosechic','d7e4d732aae72f0d09b9161f363f3ca7',0,220,230,200,225),(40,'LA Station',11.00,14.00,2019,'This is a mixed media painting of a gas station in Silver Lake, a neighborhood of Los Angeles. I took the photograph that was inspiration for this piece while exploring a section of the city near the intersection of Sunset and Alvarado.  I was drawn to the stickers and graffiti on the vacuum machine.  In order to accurately depict the markings, I printed the photograph on copy paper and added it to the piece using collage techniques. I attempted to create contrast between the collage elements and the graphic nature of the ivy in the background.','',350,'https://fineartamerica.com/featured/la-station-lesley-rutherford.html',NULL,3,'lastation','03789b5653a3049ee61aa65ee4b5f25e',0,340,280,310,320),(41,'LA Escape',10.00,10.00,2020,'This is a mixed media painting of a wall in the Arts District of Downtown Los Angeles. I took the photograph that was the inspiration for this piece while exploring this area of the city.  I was drawn to the graffiti and the street art in the neighborhood.  I attempted to accurately recreate the artists\' markings in this piece while simultaneously simplifying some of the visual elements that were evident in the original image.','',300,'https://fineartamerica.com/featured/la-escape-lesley-rutherford.html',NULL,4,'laescape','f8af4e47c4ce91fd6c4f80bbb9bd3c36',0,220,250,220,235);
/*!40000 ALTER TABLE `info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `style`
--

DROP TABLE IF EXISTS `style`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `style` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hue` smallint(5) unsigned NOT NULL,
  `saturation` tinyint(3) unsigned NOT NULL,
  `primarylightness` tinyint(3) unsigned NOT NULL,
  `secondarylightness` tinyint(3) unsigned DEFAULT NULL,
  `backgroundlightness` tinyint(3) unsigned NOT NULL,
  `primaryfont` tinyint(3) unsigned NOT NULL,
  `secondaryfont` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `style`
--

LOCK TABLES `style` WRITE;
/*!40000 ALTER TABLE `style` DISABLE KEYS */;
INSERT INTO `style` VALUES (1,254,100,88,96,0,1,2),(2,254,100,88,94,0,1,14),(3,281,68,89,91,25,1,1);
/*!40000 ALTER TABLE `style` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-25 21:12:28
