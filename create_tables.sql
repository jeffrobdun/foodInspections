
CREATE TABLE IF NOT EXISTS `establishmentHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `inspectionDate` varchar(25) DEFAULT NULL,
  `colourImage` varchar(60) DEFAULT NULL,
  `reinspectionDate` varchar(25) DEFAULT NULL,
  `pdfPath` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `establishments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `inspectionDate` varchar(25) DEFAULT NULL,
  `colourImage` varchar(60) DEFAULT NULL,
  `reinspectionDate` varchar(25) DEFAULT NULL,
  `pdfPath` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `currentTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);