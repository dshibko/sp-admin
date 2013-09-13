SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `iso_code` varchar(2) NOT NULL,
  `dial_code` smallint(6) DEFAULT NULL,
  `flag_image` varchar(64) NOT NULL,
  `original_flag_image` varchar(255) NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `language_id` (`language_id`),
  KEY `name` (`name`),
  CONSTRAINT `country_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`),
  CONSTRAINT `country_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8;

INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (1,'United States','US',1,'/img/flags/1.png','http://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/28px-Flag_of_the_United_States.svg.png',4,1),
 (2,'Canada','CA',1,'/img/flags/2.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/cf/Flag_of_Canada.svg/28px-Flag_of_Canada.svg.png',4,1),
 (3,'Bahamas','BS',242,'/img/flags/3.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Flag_of_the_Bahamas.svg/28px-Flag_of_the_Bahamas.svg.png',6,NULL),
 (4,'Barbados','BB',246,'/img/flags/4.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Flag_of_Barbados.svg/28px-Flag_of_Barbados.svg.png',6,NULL),
 (5,'Belize','BZ',501,'/img/flags/5.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Flag_of_Belize.svg/28px-Flag_of_Belize.svg.png',5,NULL),
 (6,'Bermuda','BM',441,'/img/flags/6.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bermuda.svg/28px-Flag_of_Bermuda.svg.png',4,NULL),
 (7,'British Virgin Islands','VG',284,'/img/flags/7.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_British_Virgin_Islands.svg/28px-Flag_of_the_British_Virgin_Islands.svg.png',1,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (8,'Cayman Islands','KY',345,'/img/flags/8.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_the_Cayman_Islands.svg/28px-Flag_of_the_Cayman_Islands.svg.png',6,NULL),
 (9,'Costa Rica','CR',506,'/img/flags/9.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Flag_of_Costa_Rica.svg/28px-Flag_of_Costa_Rica.svg.png',5,NULL),
 (10,'Cuba','CU',53,'/img/flags/10.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Flag_of_Cuba.svg/28px-Flag_of_Cuba.svg.png',6,NULL),
 (11,'Dominica','DM',767,'/img/flags/11.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/c4/Flag_of_Dominica.svg/28px-Flag_of_Dominica.svg.png',6,NULL),
 (12,'Dominican Republic','DO',809,'/img/flags/12.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_the_Dominican_Republic.svg/28px-Flag_of_the_Dominican_Republic.svg.png',6,NULL),
 (13,'El Salvador','SV',503,'/img/flags/13.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_El_Salvador.svg/28px-Flag_of_El_Salvador.svg.png',5,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (14,'Greenland','GL',299,'/img/flags/14.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_Greenland.svg/28px-Flag_of_Greenland.svg.png',4,NULL),
 (15,'Grenada','GD',473,'/img/flags/15.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Grenada.svg/28px-Flag_of_Grenada.svg.png',6,NULL),
 (16,'Guadeloupe','GP',590,'/img/flags/16.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png',6,NULL),
 (17,'Guatemala','GT',502,'/img/flags/17.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/ec/Flag_of_Guatemala.svg/28px-Flag_of_Guatemala.svg.png',5,NULL),
 (18,'Haiti','HT',509,'/img/flags/18.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Haiti.svg/28px-Flag_of_Haiti.svg.png',6,NULL),
 (19,'Honduras','HN',503,'/img/flags/19.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Flag_of_Honduras.svg/28px-Flag_of_Honduras.svg.png',5,NULL),
 (20,'Jamaica','JM',876,'/img/flags/20.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Jamaica.svg/28px-Flag_of_Jamaica.svg.png',6,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (21,'Martinique','MQ',596,'/img/flags/21.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png',6,NULL),
 (22,'Mexico','MX',52,'/img/flags/22.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Mexico.svg/28px-Flag_of_Mexico.svg.png',4,NULL),
 (23,'Montserrat','MS',664,'/img/flags/23.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Montserrat.svg/28px-Flag_of_Montserrat.svg.png',6,NULL),
 (24,'Nicaragua','NI',505,'/img/flags/24.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Nicaragua.svg/28px-Flag_of_Nicaragua.svg.png',5,NULL),
 (25,'Panama','PA',507,'/img/flags/25.png','http://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Flag_of_Panama.svg/28px-Flag_of_Panama.svg.png',5,NULL),
 (26,'Puerto Rico','PR',787,'/img/flags/26.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Flag_of_Puerto_Rico.svg/28px-Flag_of_Puerto_Rico.svg.png',6,NULL),
 (27,'Trinidad and Tobago','TT',868,'/img/flags/27.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Trinidad_and_Tobago.svg/28px-Flag_of_Trinidad_and_Tobago.svg.png',6,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (28,'United States Virgin Islands','VI',340,'/img/flags/28.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_the_United_States_Virgin_Islands.svg/28px-Flag_of_the_United_States_Virgin_Islands.svg.png',6,NULL),
 (29,'Argentina','AR',54,'/img/flags/29.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Flag_of_Argentina.svg/28px-Flag_of_Argentina.svg.png',3,NULL),
 (30,'Bolivia','BO',591,'/img/flags/30.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Bolivia.svg/28px-Flag_of_Bolivia.svg.png',3,NULL),
 (31,'Brazil','BR',55,'/img/flags/31.png','http://upload.wikimedia.org/wikipedia/en/thumb/0/05/Flag_of_Brazil.svg/28px-Flag_of_Brazil.svg.png',3,NULL),
 (32,'Chile','CL',56,'/img/flags/32.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Flag_of_Chile.svg/28px-Flag_of_Chile.svg.png',3,NULL),
 (33,'Colombia','CO',57,'/img/flags/33.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Colombia.svg/28px-Flag_of_Colombia.svg.png',3,NULL),
 (34,'Ecuador','EC',593,'/img/flags/34.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/Flag_of_Ecuador.svg/28px-Flag_of_Ecuador.svg.png',3,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (35,'Falkland Islands','FK',500,'/img/flags/35.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_Falkland_Islands.svg/28px-Flag_of_the_Falkland_Islands.svg.png',3,NULL),
 (36,'French Guiana','GF',594,'/img/flags/36.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png',3,NULL),
 (37,'Guyana','GY',592,'/img/flags/37.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_Guyana.svg/28px-Flag_of_Guyana.svg.png',3,NULL),
 (38,'Paraguay','PY',595,'/img/flags/38.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Paraguay.svg/28px-Flag_of_Paraguay.svg.png',3,NULL),
 (39,'Peru','PE',51,'/img/flags/39.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/cf/Flag_of_Peru.svg/28px-Flag_of_Peru.svg.png',3,NULL),
 (40,'Suriname','SR',597,'/img/flags/40.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/60/Flag_of_Suriname.svg/28px-Flag_of_Suriname.svg.png',3,NULL),
 (41,'Uruguay','UY',598,'/img/flags/41.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Uruguay.svg/28px-Flag_of_Uruguay.svg.png',3,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (42,'Venezuela','VE',58,'/img/flags/42.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Venezuela.svg/28px-Flag_of_Venezuela.svg.png',3,NULL),
 (43,'Albania','AL',355,'/img/flags/43.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Flag_of_Albania.svg/28px-Flag_of_Albania.svg.png',2,NULL),
 (44,'Andorra','AD',376,'/img/flags/44.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Andorra.svg/28px-Flag_of_Andorra.svg.png',2,NULL),
 (45,'Armenia','AM',374,'/img/flags/45.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Flag_of_Armenia.svg/28px-Flag_of_Armenia.svg.png',7,NULL),
 (46,'Austria','AT',43,'/img/flags/46.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/28px-Flag_of_Austria.svg.png',2,NULL),
 (47,'Azerbaijan','AZ',994,'/img/flags/47.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Azerbaijan.svg/28px-Flag_of_Azerbaijan.svg.png',7,NULL),
 (48,'Belarus','BY',375,'/img/flags/48.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Belarus_%281991-1995%29.svg/28px-Flag_of_Belarus_%281991-1995%29.svg.png',2,2);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (49,'Belgium','BE',32,'/img/flags/49.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Belgium_%28civil%29.svg/28px-Flag_of_Belgium_%28civil%29.svg.png',2,NULL),
 (50,'Bosnia and Herzegovina','BA',387,'/img/flags/50.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Flag_of_Bosnia_and_Herzegovina.svg/28px-Flag_of_Bosnia_and_Herzegovina.svg.png',2,NULL),
 (51,'Bulgaria','BG',359,'/img/flags/51.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Bulgaria.svg/28px-Flag_of_Bulgaria.svg.png',2,NULL),
 (52,'Croatia','HR',385,'/img/flags/52.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Croatia.svg/28px-Flag_of_Croatia.svg.png',2,NULL),
 (53,'Cyprus','CY',357,'/img/flags/53.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Cyprus.svg/28px-Flag_of_Cyprus.svg.png',7,NULL),
 (54,'Czech Republic','CZ',420,'/img/flags/54.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/28px-Flag_of_the_Czech_Republic.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (55,'Denmark','DK',45,'/img/flags/55.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Denmark.svg/28px-Flag_of_Denmark.svg.png',2,NULL),
 (56,'Estonia','EE',372,'/img/flags/56.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Flag_of_Estonia.svg/28px-Flag_of_Estonia.svg.png',2,NULL),
 (57,'Finland','FI',358,'/img/flags/57.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Finland.svg/28px-Flag_of_Finland.svg.png',2,NULL),
 (58,'France','FR',33,'/img/flags/58.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png',2,NULL),
 (59,'Georgia','GE',995,'/img/flags/59.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/28px-Flag_of_Georgia.svg.png',7,NULL),
 (60,'Germany','DE',49,'/img/flags/60.png','http://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/28px-Flag_of_Germany.svg.png',2,NULL),
 (61,'Gibraltar','GI',350,'/img/flags/61.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/02/Flag_of_Gibraltar.svg/28px-Flag_of_Gibraltar.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (62,'Greece','GR',30,'/img/flags/62.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Greece.svg/28px-Flag_of_Greece.svg.png',2,NULL),
 (63,'Guernsey','GG',44,'/img/flags/63.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Guernsey.svg/28px-Flag_of_Guernsey.svg.png',1,NULL),
 (64,'Hungary','HU',36,'/img/flags/64.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Flag_of_Hungary.svg/28px-Flag_of_Hungary.svg.png',2,NULL),
 (65,'Iceland','IS',354,'/img/flags/65.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Iceland.svg/28px-Flag_of_Iceland.svg.png',2,NULL),
 (66,'Ireland','IE',353,'/img/flags/66.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Flag_of_Ireland.svg/28px-Flag_of_Ireland.svg.png',1,NULL),
 (67,'Isle of Man','IM',44,'/img/flags/67.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_the_Isle_of_Man.svg/28px-Flag_of_the_Isle_of_Man.svg.png',1,NULL),
 (68,'Italy','IT',39,'/img/flags/68.png','http://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/28px-Flag_of_Italy.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (69,'Jersey','JE',44,'/img/flags/69.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Flag_of_Jersey.svg/28px-Flag_of_Jersey.svg.png',1,NULL),
 (70,'Kosovo','',381,'/img/flags/70.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Flag_of_Kosovo.svg/28px-Flag_of_Kosovo.svg.png',2,NULL),
 (71,'Latvia','LV',371,'/img/flags/71.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Latvia.svg/28px-Flag_of_Latvia.svg.png',2,NULL),
 (72,'Liechtenstein','LI',423,'/img/flags/72.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Flag_of_Liechtenstein.svg/28px-Flag_of_Liechtenstein.svg.png',2,NULL),
 (73,'Lithuania','LT',370,'/img/flags/73.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Lithuania.svg/28px-Flag_of_Lithuania.svg.png',2,NULL),
 (74,'Luxembourg','LU',352,'/img/flags/74.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Flag_of_Luxembourg.svg/28px-Flag_of_Luxembourg.svg.png',2,NULL),
 (75,'Macedonia','MK',389,'/img/flags/75.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Flag_of_Macedonia.svg/28px-Flag_of_Macedonia.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (76,'Malta','MT',356,'/img/flags/76.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Malta.svg/28px-Flag_of_Malta.svg.png',2,NULL),
 (77,'Moldova','MD',373,'/img/flags/77.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/28px-Flag_of_Moldova.svg.png',2,NULL),
 (78,'Monaco','MC',377,'/img/flags/78.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Flag_of_Monaco.svg/28px-Flag_of_Monaco.svg.png',2,NULL),
 (79,'Montenegro','ME',381,'/img/flags/79.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Flag_of_Montenegro.svg/28px-Flag_of_Montenegro.svg.png',2,NULL),
 (80,'Netherlands','NL',31,'/img/flags/80.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/28px-Flag_of_the_Netherlands.svg.png',2,NULL),
 (81,'Norway','NO',47,'/img/flags/81.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Flag_of_Norway.svg/28px-Flag_of_Norway.svg.png',2,NULL),
 (82,'Poland','PL',48,'/img/flags/82.png','http://upload.wikimedia.org/wikipedia/en/thumb/1/12/Flag_of_Poland.svg/28px-Flag_of_Poland.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (83,'Portugal','PT',351,'/img/flags/83.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/28px-Flag_of_Portugal.svg.png',2,NULL),
 (84,'Romania','RO',40,'/img/flags/84.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Flag_of_Romania.svg/28px-Flag_of_Romania.svg.png',2,NULL),
 (85,'Russia','RU',7,'/img/flags/85.png','http://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/28px-Flag_of_Russia.svg.png',2,2),
 (86,'San Marino','SM',378,'/img/flags/86.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Flag_of_San_Marino.svg/28px-Flag_of_San_Marino.svg.png',2,NULL),
 (87,'Serbia','RS',381,'/img/flags/87.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Flag_of_Serbia.svg/28px-Flag_of_Serbia.svg.png',2,NULL),
 (88,'Slovakia','SK',421,'/img/flags/88.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/28px-Flag_of_Slovakia.svg.png',2,NULL),
 (89,'Slovenia','SI',386,'/img/flags/89.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Flag_of_Slovenia.svg/28px-Flag_of_Slovenia.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (90,'Spain','ES',34,'/img/flags/90.png','http://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Flag_of_Spain.svg/28px-Flag_of_Spain.svg.png',2,NULL),
 (91,'Sweden','SE',46,'/img/flags/91.png','http://upload.wikimedia.org/wikipedia/en/thumb/4/4c/Flag_of_Sweden.svg/28px-Flag_of_Sweden.svg.png',2,NULL),
 (92,'Switzerland','CH',41,'/img/flags/92.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/20px-Flag_of_Switzerland.svg.png',2,NULL),
 (93,'Turkey','TR',90,'/img/flags/93.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Flag_of_Turkey.svg/28px-Flag_of_Turkey.svg.png',2,NULL),
 (94,'Ukraine','UA',380,'/img/flags/94.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Ukraine.svg/28px-Flag_of_Ukraine.svg.png',2,2),
 (95,'United Kingdom','GB',44,'/img/flags/95.png','http://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/28px-Flag_of_the_United_Kingdom.svg.png',1,1),
 (96,'Vatican City','VA',39,'/img/flags/96.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_the_Vatican_City.svg/20px-Flag_of_the_Vatican_City.svg.png',2,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (97,'Afghanistan','AF',93,'/img/flags/97.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Afghanistan.svg/28px-Flag_of_Afghanistan.svg.png',7,NULL),
 (98,'Bahrain','BH',973,'/img/flags/98.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Bahrain.svg/28px-Flag_of_Bahrain.svg.png',7,NULL),
 (99,'Bangladesh','BD',880,'/img/flags/99.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Flag_of_Bangladesh.svg/28px-Flag_of_Bangladesh.svg.png',7,NULL),
 (100,'Bhutan','BT',975,'/img/flags/100.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Flag_of_Bhutan.svg/28px-Flag_of_Bhutan.svg.png',7,NULL),
 (101,'Brunei','BN',673,'/img/flags/101.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Flag_of_Brunei.svg/28px-Flag_of_Brunei.svg.png',7,NULL),
 (102,'Cambodia','KH',855,'/img/flags/102.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_Cambodia.svg/28px-Flag_of_Cambodia.svg.png',7,NULL),
 (103,'China','CN',86,'/img/flags/103.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/28px-Flag_of_the_People%27s_Republic_of_China.svg.png',7,3);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (104,'East Timor','TL',670,'/img/flags/104.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_East_Timor.svg/28px-Flag_of_East_Timor.svg.png',7,NULL),
 (105,'Hong Kong','HK',852,'/img/flags/105.png','http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png',7,NULL),
 (106,'India','IN',91,'/img/flags/106.png','http://upload.wikimedia.org/wikipedia/en/thumb/4/41/Flag_of_India.svg/28px-Flag_of_India.svg.png',7,NULL),
 (107,'Indonesia','ID',62,'/img/flags/107.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/28px-Flag_of_Indonesia.svg.png',7,5),
 (108,'Iran','IR',98,'/img/flags/108.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Flag_of_Iran.svg/28px-Flag_of_Iran.svg.png',7,NULL),
 (109,'Iraq','IQ',964,'/img/flags/109.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Iraq.svg/28px-Flag_of_Iraq.svg.png',7,NULL),
 (110,'Israel','IL',972,'/img/flags/110.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Flag_of_Israel.svg/28px-Flag_of_Israel.svg.png',7,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (111,'Japan','JP',81,'/img/flags/111.png','http://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/28px-Flag_of_Japan.svg.png',7,4),
 (112,'Jordan','JO',962,'/img/flags/112.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Flag_of_Jordan.svg/28px-Flag_of_Jordan.svg.png',7,NULL),
 (113,'Kazakhstan','KZ',7,'/img/flags/113.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kazakhstan.svg/28px-Flag_of_Kazakhstan.svg.png',7,NULL),
 (114,'Kuwait','KW',965,'/img/flags/114.png','http://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/Flag_of_Kuwait.svg/28px-Flag_of_Kuwait.svg.png',7,NULL),
 (115,'Kyrgyzstan','KG',996,'/img/flags/115.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Flag_of_Kyrgyzstan.svg/28px-Flag_of_Kyrgyzstan.svg.png',7,NULL),
 (116,'Laos','LA',856,'/img/flags/116.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Flag_of_Laos.svg/28px-Flag_of_Laos.svg.png',7,NULL),
 (117,'Lebanon','LB',961,'/img/flags/117.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/59/Flag_of_Lebanon.svg/28px-Flag_of_Lebanon.svg.png',7,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (118,'Macau','MO',853,'/img/flags/118.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Flag_of_Macau.svg/28px-Flag_of_Macau.svg.png',7,NULL),
 (119,'Malaysia','MY',60,'/img/flags/119.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Flag_of_Malaysia.svg/28px-Flag_of_Malaysia.svg.png',7,NULL),
 (120,'Maldives','MV',960,'/img/flags/120.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Maldives.svg/28px-Flag_of_Maldives.svg.png',7,NULL),
 (121,'Mongolia','MN',976,'/img/flags/121.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/28px-Flag_of_Mongolia.svg.png',7,NULL),
 (122,'Myanmar (Burma)','MM',95,'/img/flags/122.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Flag_of_Myanmar.svg/28px-Flag_of_Myanmar.svg.png',7,NULL),
 (123,'Nepal','NP',977,'/img/flags/123.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Flag_of_Nepal.svg/16px-Flag_of_Nepal.svg.png',7,NULL),
 (124,'North Korea','NP',850,'/img/flags/124.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Flag_of_North_Korea.svg/28px-Flag_of_North_Korea.svg.png',7,6);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (125,'Oman','OM',968,'/img/flags/125.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Flag_of_Oman.svg/28px-Flag_of_Oman.svg.png',7,NULL),
 (126,'Pakistan','PK',92,'/img/flags/126.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/32/Flag_of_Pakistan.svg/28px-Flag_of_Pakistan.svg.png',7,NULL),
 (127,'Philippines','PH',63,'/img/flags/127.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Flag_of_the_Philippines.svg/28px-Flag_of_the_Philippines.svg.png',7,NULL),
 (128,'Qatar','QA',974,'/img/flags/128.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Flag_of_Qatar.svg/28px-Flag_of_Qatar.svg.png',7,NULL),
 (129,'Saudi Arabia','SA',966,'/img/flags/129.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Flag_of_Saudi_Arabia.svg/28px-Flag_of_Saudi_Arabia.svg.png',7,NULL),
 (130,'Singapore','SG',65,'/img/flags/130.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Singapore.svg/28px-Flag_of_Singapore.svg.png',7,NULL),
 (131,'South Korea','KR',82,'/img/flags/131.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/28px-Flag_of_South_Korea.svg.png',7,6);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (132,'Sri Lanka','LK',94,'/img/flags/132.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Sri_Lanka.svg/28px-Flag_of_Sri_Lanka.svg.png',7,NULL),
 (133,'Syria','SY',963,'/img/flags/133.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Flag_of_Syria.svg/28px-Flag_of_Syria.svg.png',7,NULL),
 (134,'Taiwan','TW',886,'/img/flags/134.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Flag_of_the_Republic_of_China.svg/28px-Flag_of_the_Republic_of_China.svg.png',7,NULL),
 (135,'Tajikistan','TJ',992,'/img/flags/135.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Tajikistan.svg/28px-Flag_of_Tajikistan.svg.png',7,NULL),
 (136,'Thailand','TH',66,'/img/flags/136.png','http://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Flag_of_Thailand.svg/28px-Flag_of_Thailand.svg.png',7,7),
 (137,'Turkmenistan','TM',993,'/img/flags/137.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Flag_of_Turkmenistan.svg/28px-Flag_of_Turkmenistan.svg.png',7,NULL),
 (138,'United Arab Emirates','AE',971,'/img/flags/138.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_United_Arab_Emirates.svg/28px-Flag_of_the_United_Arab_Emirates.svg.png',7,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (139,'Uzbekistan','UZ',998,'/img/flags/139.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Flag_of_Uzbekistan.svg/28px-Flag_of_Uzbekistan.svg.png',7,NULL),
 (140,'Vietnam','VN',84,'/img/flags/140.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/21/Flag_of_Vietnam.svg/28px-Flag_of_Vietnam.svg.png',7,NULL),
 (141,'Yemen','YE',967,'/img/flags/141.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Yemen.svg/28px-Flag_of_Yemen.svg.png',7,NULL),
 (142,'Algeria','DZ',213,'/img/flags/142.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Algeria.svg/28px-Flag_of_Algeria.svg.png',8,NULL),
 (143,'Angola','AO',244,'/img/flags/143.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Flag_of_Angola.svg/28px-Flag_of_Angola.svg.png',8,NULL),
 (144,'Benin','BJ',229,'/img/flags/144.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Flag_of_Benin.svg/28px-Flag_of_Benin.svg.png',8,NULL),
 (145,'Botswana','BW',267,'/img/flags/145.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_Botswana.svg/28px-Flag_of_Botswana.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (146,'Burkina Faso','BF',226,'/img/flags/146.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Burkina_Faso.svg/28px-Flag_of_Burkina_Faso.svg.png',8,NULL),
 (147,'Burundi','BI',257,'/img/flags/147.png','http://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Flag_of_Burundi.svg/28px-Flag_of_Burundi.svg.png',8,NULL),
 (148,'Cameroon','CM',237,'/img/flags/148.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Cameroon.svg/28px-Flag_of_Cameroon.svg.png',8,NULL),
 (149,'Cape Verde','CV',238,'/img/flags/149.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Cape_Verde.svg/28px-Flag_of_Cape_Verde.svg.png',8,NULL),
 (150,'Central African Republic','CF',236,'/img/flags/150.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Central_African_Republic.svg/28px-Flag_of_the_Central_African_Republic.svg.png',8,NULL),
 (151,'Chad','TD',235,'/img/flags/151.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Flag_of_Chad.svg/28px-Flag_of_Chad.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (152,'Congo-Brazzaville','CG',242,'/img/flags/152.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_the_Republic_of_the_Congo.svg/28px-Flag_of_the_Republic_of_the_Congo.svg.png',8,NULL),
 (153,'Congo-Kinshasa','CD',242,'/img/flags/153.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Flag_of_the_Democratic_Republic_of_the_Congo.svg/28px-Flag_of_the_Democratic_Republic_of_the_Congo.svg.png',8,NULL),
 (154,'Djibouti','DJ',253,'/img/flags/154.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/34/Flag_of_Djibouti.svg/28px-Flag_of_Djibouti.svg.png',8,NULL),
 (155,'Egypt','EG',20,'/img/flags/155.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Egypt.svg/28px-Flag_of_Egypt.svg.png',8,NULL),
 (156,'Equatorial Guinea','GQ',240,'/img/flags/156.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Equatorial_Guinea.svg/28px-Flag_of_Equatorial_Guinea.svg.png',8,NULL),
 (157,'Eritrea','ER',291,'/img/flags/157.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Flag_of_Eritrea.svg/28px-Flag_of_Eritrea.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (158,'Ethiopia','ET',251,'/img/flags/158.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Flag_of_Ethiopia.svg/28px-Flag_of_Ethiopia.svg.png',8,NULL),
 (159,'Gabon','GA',241,'/img/flags/159.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Flag_of_Gabon.svg/28px-Flag_of_Gabon.svg.png',8,NULL),
 (160,'Gambia','GM',220,'/img/flags/160.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_The_Gambia.svg/28px-Flag_of_The_Gambia.svg.png',8,NULL),
 (161,'Ghana','GH',233,'/img/flags/161.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Flag_of_Ghana.svg/28px-Flag_of_Ghana.svg.png',8,NULL),
 (162,'Guinea','GN',224,'/img/flags/162.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Flag_of_Guinea.svg/28px-Flag_of_Guinea.svg.png',8,NULL),
 (163,'Guinea-Bissau','GW',245,'/img/flags/163.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Guinea-Bissau.svg/28px-Flag_of_Guinea-Bissau.svg.png',8,NULL),
 (164,'Ivory Coast','CI',225,'/img/flags/164.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_C%C3%B4te_d%27Ivoire.svg/28px-Flag_of_C%C3%B4te_d%27Ivoire.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (165,'Kenya','KE',254,'/img/flags/165.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Flag_of_Kenya.svg/28px-Flag_of_Kenya.svg.png',8,NULL),
 (166,'Lesotho','LS',266,'/img/flags/166.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Flag_of_Lesotho.svg/28px-Flag_of_Lesotho.svg.png',8,NULL),
 (167,'Liberia','LR',231,'/img/flags/167.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Flag_of_Liberia.svg/28px-Flag_of_Liberia.svg.png',8,NULL),
 (168,'Libya','LY',218,'/img/flags/168.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Libya.svg/28px-Flag_of_Libya.svg.png',8,NULL),
 (169,'Madagascar','MG',261,'/img/flags/169.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Madagascar.svg/28px-Flag_of_Madagascar.svg.png',8,NULL),
 (170,'Malawi','MW',265,'/img/flags/170.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Flag_of_Malawi.svg/28px-Flag_of_Malawi.svg.png',8,NULL),
 (171,'Mali','ML',223,'/img/flags/171.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Flag_of_Mali.svg/28px-Flag_of_Mali.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (172,'Mauritania','MR',222,'/img/flags/172.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Flag_of_Mauritania.svg/28px-Flag_of_Mauritania.svg.png',8,NULL),
 (173,'Mauritius','MU',230,'/img/flags/173.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Flag_of_Mauritius.svg/28px-Flag_of_Mauritius.svg.png',8,NULL),
 (174,'Morocco','MA',212,'/img/flags/174.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Flag_of_Morocco.svg/28px-Flag_of_Morocco.svg.png',8,NULL),
 (175,'Mozambique','MZ',258,'/img/flags/175.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Flag_of_Mozambique.svg/28px-Flag_of_Mozambique.svg.png',8,NULL),
 (176,'Namibia','NA',264,'/img/flags/176.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/00/Flag_of_Namibia.svg/28px-Flag_of_Namibia.svg.png',8,NULL),
 (177,'Niger','NE',227,'/img/flags/177.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Flag_of_Niger.svg/28px-Flag_of_Niger.svg.png',8,NULL),
 (178,'Nigeria','NG',234,'/img/flags/178.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/28px-Flag_of_Nigeria.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (179,'Reunion','RE',262,'/img/flags/179.png','http://upload.wikimedia.org/wikipedia/en/thumb/c/c3/Flag_of_France.svg/28px-Flag_of_France.svg.png',8,NULL),
 (180,'Rwanda','RW',250,'/img/flags/180.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Rwanda.svg/28px-Flag_of_Rwanda.svg.png',8,NULL),
 (181,'Sao Tome and Principe','ST',239,'/img/flags/181.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Flag_of_Sao_Tome_and_Principe.svg/28px-Flag_of_Sao_Tome_and_Principe.svg.png',8,NULL),
 (182,'Senegal','SN',221,'/img/flags/182.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Flag_of_Senegal.svg/28px-Flag_of_Senegal.svg.png',8,NULL),
 (183,'Seychelles','SC',248,'/img/flags/183.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fc/Flag_of_Seychelles.svg/28px-Flag_of_Seychelles.svg.png',8,NULL),
 (184,'Sierra Leone','SL',232,'/img/flags/184.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Flag_of_Sierra_Leone.svg/28px-Flag_of_Sierra_Leone.svg.png',8,NULL),
 (185,'Somalia','SO',252,'/img/flags/185.png','http://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Flag_of_Somalia.svg/28px-Flag_of_Somalia.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (186,'South Africa','ZA',27,'/img/flags/186.png','http://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Flag_of_South_Africa.svg/28px-Flag_of_South_Africa.svg.png',8,NULL),
 (187,'Sudan','SD',249,'/img/flags/187.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Sudan.svg/28px-Flag_of_Sudan.svg.png',8,NULL),
 (188,'Swaziland','SZ',268,'/img/flags/188.png','http://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Flag_of_Swaziland.svg/28px-Flag_of_Swaziland.svg.png',8,NULL),
 (189,'Tanzania','TZ',255,'/img/flags/189.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tanzania.svg/28px-Flag_of_Tanzania.svg.png',8,NULL),
 (190,'Togo','TG',228,'/img/flags/190.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/68/Flag_of_Togo.svg/28px-Flag_of_Togo.svg.png',8,NULL),
 (191,'Tunisia','TN',216,'/img/flags/191.png','http://upload.wikimedia.org/wikipedia/commons/thumb/c/ce/Flag_of_Tunisia.svg/28px-Flag_of_Tunisia.svg.png',8,NULL),
 (192,'Uganda','UG',256,'/img/flags/192.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Flag_of_Uganda.svg/28px-Flag_of_Uganda.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (193,'Western Sahara','EH',212,'/img/flags/193.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg/28px-Flag_of_the_Sahrawi_Arab_Democratic_Republic.svg.png',8,NULL),
 (194,'Zambia','ZM',260,'/img/flags/194.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Flag_of_Zambia.svg/28px-Flag_of_Zambia.svg.png',8,NULL),
 (195,'Zimbabwe','ZW',263,'/img/flags/195.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Flag_of_Zimbabwe.svg/28px-Flag_of_Zimbabwe.svg.png',8,NULL),
 (196,'Australia','AU',61,'/img/flags/196.png','http://upload.wikimedia.org/wikipedia/en/thumb/b/b9/Flag_of_Australia.svg/28px-Flag_of_Australia.svg.png',9,1),
 (197,'New Zealand','NZ',64,'/img/flags/197.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Flag_of_New_Zealand.svg/28px-Flag_of_New_Zealand.svg.png',9,NULL),
 (198,'Fiji','FJ',679,'/img/flags/198.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Fiji.svg/28px-Flag_of_Fiji.svg.png',9,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (199,'French Polynesia','PF',689,'/img/flags/199.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Flag_of_French_Polynesia.svg/28px-Flag_of_French_Polynesia.svg.png',9,NULL),
 (200,'Guam','GU',671,'/img/flags/200.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Flag_of_Guam.svg/28px-Flag_of_Guam.svg.png',9,NULL),
 (201,'Kiribati','KI',686,'/img/flags/201.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Flag_of_Kiribati.svg/28px-Flag_of_Kiribati.svg.png',9,NULL),
 (202,'Marshall Islands','MH',692,'/img/flags/202.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Flag_of_the_Marshall_Islands.svg/28px-Flag_of_the_Marshall_Islands.svg.png',9,NULL),
 (203,'Micronesia','FM',691,'/img/flags/203.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Flag_of_the_Federated_States_of_Micronesia.svg/28px-Flag_of_the_Federated_States_of_Micronesia.svg.png',9,NULL),
 (204,'Nauru','NR',674,'/img/flags/204.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Flag_of_Nauru.svg/28px-Flag_of_Nauru.svg.png',9,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (205,'New Caledonia','NC',687,'/img/flags/205.png','http://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Flag_of_New_Caledonia.svg/28px-Flag_of_New_Caledonia.svg.png',9,NULL),
 (206,'Papua New Guinea','PG',675,'/img/flags/206.png','http://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Flag_of_Papua_New_Guinea.svg/28px-Flag_of_Papua_New_Guinea.svg.png',9,NULL),
 (207,'Samoa','WS',684,'/img/flags/207.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Flag_of_Samoa.svg/28px-Flag_of_Samoa.svg.png',9,NULL),
 (208,'Solomon Islands','SB',677,'/img/flags/208.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/74/Flag_of_the_Solomon_Islands.svg/28px-Flag_of_the_Solomon_Islands.svg.png',9,NULL),
 (209,'Tonga','TO',676,'/img/flags/209.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Tonga.svg/28px-Flag_of_Tonga.svg.png',9,NULL),
 (210,'Tuvalu','TV',688,'/img/flags/210.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Flag_of_Tuvalu.svg/28px-Flag_of_Tuvalu.svg.png',9,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (211,'Vanuatu','VU',678,'/img/flags/211.png','http://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_Vanuatu.svg/28px-Flag_of_Vanuatu.svg.png',9,NULL),
 (212,'Wallis and Futuna','WF',681,'/img/flags/212.png','http://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Flag_of_Wallis_and_Futuna.svg/28px-Flag_of_Wallis_and_Futuna.svg.png',9,NULL),
 (213,'South Sudan','SS',211,'/img/flags/213.png','http://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Flag_of_South_Sudan.svg/28px-Flag_of_South_Sudan.svg.png',8,NULL),
 (214,'Antigua and Barbuda','AG',16,'/img/flags/214.png','http://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Flag_of_Antigua_and_Barbuda.svg/28px-Flag_of_Antigua_and_Barbuda.svg.png',6,NULL),
 (215,'Aruba','AW',533,'/img/flags/215.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Flag_of_Aruba.svg/28px-Flag_of_Aruba.svg.png',6,NULL),
 (216,'Comoros','KM',174,'/img/flags/216.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Flag_of_the_Comoros.svg/28px-Flag_of_the_Comoros.svg.png',8,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (217,'Cook Islands','CK',184,'/img/flags/217.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/35/Flag_of_the_Cook_Islands.svg/28px-Flag_of_the_Cook_Islands.svg.png',9,NULL),
 (218,'Faroe Islands','FO',234,'/img/flags/218.png','http://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Flag_of_the_Faroe_Islands.svg/28px-Flag_of_the_Faroe_Islands.svg.png',2,NULL),
 (219,'Niue','NU',570,'/img/flags/219.png','http://upload.wikimedia.org/wikipedia/commons/thumb/0/01/Flag_of_Niue.svg/28px-Flag_of_Niue.svg.png',9,NULL),
 (220,'Palau','PW',585,'/img/flags/220.png','http://upload.wikimedia.org/wikipedia/commons/thumb/4/48/Flag_of_Palau.svg/28px-Flag_of_Palau.svg.png',9,NULL),
 (221,'Saint Kitts and Nevis','KN',659,'/img/flags/221.png','http://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Flag_of_Saint_Kitts_and_Nevis.svg/28px-Flag_of_Saint_Kitts_and_Nevis.svg.png',6,NULL),
 (222,'Saint Lucia','LC',662,'/img/flags/222.png','http://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Saint_Lucia.svg/28px-Flag_of_Saint_Lucia.svg.png',6,NULL);
INSERT INTO `country` (`id`,`name`,`iso_code`,`dial_code`,`flag_image`,`original_flag_image`,`region_id`,`language_id`) VALUES 
 (223,'Saint Vincent and the Grenadines','VC',670,'/img/flags/223.png','http://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Flag_of_Saint_Vincent_and_the_Grenadines.svg/28px-Flag_of_Saint_Vincent_and_the_Grenadines.svg.png',6,NULL);

SET FOREIGN_KEY_CHECKS = 1;