<?php

$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `os_brands` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `brand_code` varchar(50) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

$installer->run("
CREATE TABLE IF NOT EXISTS `os_brands_match` (
  `brands_match_id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) DEFAULT NULL,
  `brand_id` varchar(45) DEFAULT NULL,
  `brand_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`brands_match_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
");

$installer->endSetup();