-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 18, 2016 at 05:18 PM
-- Server version: 5.5.30
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grubbler_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`grubbler_dba`@`%` PROCEDURE `Search` (IN `keyword` VARCHAR(50), IN `model` VARCHAR(50), IN `per_page` INT, IN `page_offset` INT)  BEGIN
	DECLARE joinField varchar(50) default 'restaurant_id';
	
	
		set @model =  IFNULL(@model,'restaurants');
		set @per_page =  IFNULL(@per_page,25);
		set @page_offset =  IFNULL(@page_offset,0);
	
   SET @keyword = keyword;
   PREPARE s1 FROM 'SELECT MAX(keyword_id) INTO @k FROM keywords
      WHERE keyword LIKE CONCAT(''%'', ?, ''%'')
		      OR keyword LIKE CONCAT(?, ''%'')
				OR keyword LIKE CONCAT(''%'', ?)';
   EXECUTE s1 USING @keyword,@keyword,@keyword;
   DEALLOCATE PREPARE s1;
   
   
   
   IF (@k IS NULL) THEN

      PREPARE s2 FROM 'INSERT INTO keywords (keyword) VALUES (?)';
      EXECUTE s2 USING @keyword;
      DEALLOCATE PREPARE s2;

      SELECT LAST_INSERT_ID() INTO @k;
      
		if(@model = 'users') then
	      PREPARE s3 FROM 'INSERT INTO users_keywords (user_id, keyword_id)
	         SELECT id, ? FROM users
	         WHERE email REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
	            OR fname REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
					OR lname REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')';
			EXECUTE s3 USING @k, @keyword, @keyword, @keyword;
      	DEALLOCATE PREPARE s3;	
			set @joinField='user_id';			
	   elseif (@model = 'menus') then
	   
	      PREPARE s3 FROM 'INSERT INTO menus_keywords (menu_id, keyword_id)
	         SELECT id, ? FROM menus
	         WHERE item REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
	            OR description REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')';
			EXECUTE s3 USING @k, @keyword, @keyword;
      	DEALLOCATE PREPARE s3;		   
	   	set @joinField='menu_id';
	   else
	   	   
	   	   
	      PREPARE s3 FROM 'INSERT INTO restaurants_keywords (restaurant_id, keyword_id)
	         SELECT id, ? FROM restaurants
	         WHERE restaurant REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
	            OR phone REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
					OR fax REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
					OR address REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
				   OR zipcode REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')
				   OR full_address REGEXP CONCAT(''[[:<:]]'', ?, ''[[:>:]]'')';
			EXECUTE s3 USING @k, @keyword, @keyword, @keyword, @keyword, @keyword, @keyword;      	
			DEALLOCATE PREPARE s3;
	   end IF;


   END IF;
	if(@model = 'users') then
	
	   PREPARE s4 FROM 'SELECT count(*) into @total FROM users  b
	      JOIN users_keywords k on b.id = k.user_id
	      WHERE k.keyword_id = ?';
	   EXECUTE s4 USING @k;
	   DEALLOCATE PREPARE s4;
	
		PREPARE s5 FROM 'SELECT b.*, ? as total FROM users  b
	  	JOIN users_keywords k on b.id = k.user_id
	   WHERE k.keyword_id = ?
	   limit ? offset ?';
	   EXECUTE s5 USING @total, @k, @per_page, @page_offset;
	   DEALLOCATE PREPARE s5;   
	   
   elseif(@model = 'menus') then
   
   	PREPARE s4 FROM 'SELECT count(*) into @total FROM menus  b
      JOIN menus_keywords k on b.id = k.menu_id
      WHERE k.keyword_id = ?';
   	EXECUTE s4 USING @k;
  		DEALLOCATE PREPARE s4;
  		 
    	PREPARE s5 FROM 'SELECT b.*, ? as total  FROM menus  b
      JOIN menus_keywords k on b.id = k.menu_id
      WHERE k.keyword_id = ?
      limit ? offset ?';
   	EXECUTE s5 USING @total, @k, @per_page, @page_offset;
  		DEALLOCATE PREPARE s5; 		 
   else
		PREPARE s4 FROM 'SELECT count(*) into @total FROM restaurants  b
      JOIN restaurants_keywords k on b.id = k.restaurant_id
      WHERE k.keyword_id = ?';
   	EXECUTE s4 USING @k;
   	DEALLOCATE PREPARE s4;
   	
		PREPARE s5 FROM 'SELECT b.*, ? as total FROM restaurants  b
      JOIN restaurants_keywords k on b.id = k.restaurant_id
      WHERE k.keyword_id = ?
		limit ? offset ?';
   	EXECUTE s5 USING @total, @k, @per_page, @page_offset;
   	DEALLOCATE PREPARE s5;   	
   end IF;
   
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `address_1` varchar(250) NOT NULL,
  `address_2` varchar(250) DEFAULT NULL,
  `apt_number` varchar(25) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `sms_enabled` int(11) NOT NULL DEFAULT '0',
  `instructions` varchar(250) DEFAULT NULL,
  `city_id` bigint(20) DEFAULT NULL,
  `state_id` bigint(20) DEFAULT NULL,
  `zip_code` varchar(10) NOT NULL,
  `last_used` datetime DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_edited` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `fname`, `lname`, `address_1`, `address_2`, `apt_number`, `phone`, `sms_enabled`, `instructions`, `city_id`, `state_id`, `zip_code`, `last_used`, `created_on`, `last_edited`) VALUES
(7, 1, 'Erwin', 'Bredy', '43 Davenport Avenue', NULL, NULL, '2034449245', 0, 'come around back!', 2, 1, '10805', NULL, '2015-09-27 16:25:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` bigint(20) NOT NULL,
  `source_id` varchar(250) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `brand` varchar(20) NOT NULL,
  `last_4` int(4) NOT NULL,
  `funding` varchar(10) NOT NULL DEFAULT 'credit',
  `country_code` varchar(2) NOT NULL DEFAULT 'us',
  `exp_month` int(2) NOT NULL,
  `exp_year` int(4) NOT NULL,
  `holder_name` varchar(250) DEFAULT NULL,
  `address_line1` varchar(250) DEFAULT NULL,
  `address_line2` varchar(250) DEFAULT NULL,
  `address_city` varchar(250) DEFAULT NULL,
  `address_state` varchar(250) DEFAULT NULL,
  `address_zip` varchar(250) DEFAULT NULL,
  `address_country` varchar(250) DEFAULT NULL,
  `last_used` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `source_id`, `user_id`, `brand`, `last_4`, `funding`, `country_code`, `exp_month`, `exp_year`, `holder_name`, `address_line1`, `address_line2`, `address_city`, `address_state`, `address_zip`, `address_country`, `last_used`) VALUES
(2, 'card_16qqAYDhDw8iUu59jvOCcBaX', 1, 'Visa', 1881, 'credit', 'US', 4, 2018, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-04-04 13:47:33'),
(3, 'card_16qqJGDhDw8iUu598txg8riU', 1, 'Visa', 4242, 'credit', 'US', 4, 2017, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2015-09-30 23:15:52'),
(4, 'card_16qqRbDhDw8iUu59QNKGq5et', 1, 'MasterCard', 5100, 'prepaid', 'US', 4, 2016, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2016-04-04 13:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(14, 'African'),
(11, 'American'),
(3, 'Chinese'),
(1, 'French'),
(9, 'Greek'),
(16, 'Haitian'),
(4, 'Indian'),
(2, 'Italian'),
(15, 'Jamaican'),
(7, 'Japanese'),
(10, 'Lebanese'),
(13, 'Meditarian'),
(6, 'Mexican'),
(12, 'Soul Food'),
(8, 'Spanish'),
(5, 'Thai');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(2, 'New Rochelle'),
(1, 'New York');

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `keyword_id` bigint(20) NOT NULL,
  `keyword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`keyword_id`, `keyword`) VALUES
(22, '+13474262330'),
(29, '10011'),
(24, '10019'),
(23, '152 W 49th St'),
(25, '152 W 49th St, New York, NY 10019'),
(21, '2034449245'),
(26, '?'),
(14, 'Atsu-giri'),
(5, 'Bredy'),
(6, 'ebredy@gmail.com'),
(13, 'Edamame'),
(4, 'Erwin'),
(15, 'Kimchi'),
(18, 'Miso Soup'),
(16, 'Na-no-hana Oyster'),
(28, 'Sappora'),
(20, 'Sapporo'),
(30, 'Sop'),
(27, 'Soparo'),
(7, 'test2@mailinator.com'),
(8, 'test@mailinator.com'),
(9, 'tester111@mailinator.com'),
(10, 'testing1@mailinator.com'),
(17, 'Yude-tamago');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) NOT NULL,
  `restaurant_id` bigint(20) NOT NULL,
  `source` varchar(250) DEFAULT NULL,
  `image` varchar(250) NOT NULL DEFAULT '/img/food/default.jpg',
  `item` varchar(100) NOT NULL,
  `description` text,
  `price` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `restaurant_id`, `source`, `image`, `item`, `description`, `price`) VALUES
(1, 1, NULL, '/img/food/a.jpg', 'Edamame', NULL, '4.00'),
(2, 1, '', '/img/food/b.jpg', 'Atsu-giri', NULL, '5.00'),
(3, 1, NULL, '/img/food/c.jpg', 'Kimchi', NULL, '3.00'),
(4, 1, NULL, '/img/food/d.jpg', 'Na-no-hana Oyster', NULL, '5.00'),
(5, 1, NULL, '/img/food/e.jpg', 'Yude-tamago', NULL, '1.00'),
(6, 1, NULL, '/img/food/f.jpg', 'Miso Soup', NULL, '1.50');

--
-- Triggers `menus`
--
DELIMITER $$
CREATE TRIGGER `menus_insert` AFTER INSERT ON `menus` FOR EACH ROW BEGIN
		INSERT INTO menus_keywords (menu_id, keyword_id)
	         SELECT new.id,k.keyword_id FROM keywords k
	         WHERE NEW.item REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]')
	            OR NEW.description REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menus_keywords`
--

CREATE TABLE `menus_keywords` (
  `keyword_id` bigint(20) NOT NULL DEFAULT '0',
  `menu_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `menu_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`menu_id`, `category_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `menu_ratings`
--

CREATE TABLE `menu_ratings` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `menu_id` bigint(20) NOT NULL,
  `rating` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ordernotifications`
--

CREATE TABLE `ordernotifications` (
  `orderNotification_ID` int(50) NOT NULL,
  `user_id` int(20) DEFAULT NULL,
  `order_id` int(20) DEFAULT NULL,
  `customer_from_phone` varchar(50) DEFAULT NULL,
  `customer_address` varchar(150) DEFAULT NULL,
  `customer_apt_number` varchar(10) DEFAULT NULL,
  `customer_address_2` varchar(150) DEFAULT NULL,
  `customer_city` varchar(50) DEFAULT NULL,
  `customer_state` varchar(50) DEFAULT NULL,
  `customer_zipcode` varchar(10) DEFAULT NULL,
  `customer_instructions` text,
  `customer_first_name` varchar(50) DEFAULT NULL,
  `customer_last_name` varchar(50) DEFAULT NULL,
  `customer_to_phone` varchar(50) DEFAULT NULL,
  `restaurant_name` varchar(100) DEFAULT NULL,
  `customer_to_phone_type` int(2) DEFAULT NULL,
  `restaurant_from_fax_phone` varchar(50) DEFAULT NULL,
  `restaurant_to_fax_phone` varchar(50) DEFAULT NULL,
  `restaurant_from_phone` varchar(50) DEFAULT NULL,
  `restaurant_to_phone` varchar(50) DEFAULT NULL,
  `fax_message` text,
  `sms_message` varchar(140) DEFAULT NULL,
  `phone_message` varchar(140) DEFAULT NULL,
  `order_confirmation_number` varchar(4) DEFAULT NULL,
  `process_status` varchar(100) DEFAULT NULL,
  `sleep_until` datetime DEFAULT NULL,
  `early_arrival` varchar(50) DEFAULT '1 hour',
  `late_arrival` varchar(50) DEFAULT '1 hour and 10 minutes',
  `createdDatetime` datetime DEFAULT NULL,
  `processDateTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordernotifications`
--

INSERT INTO `ordernotifications` (`orderNotification_ID`, `user_id`, `order_id`, `customer_from_phone`, `customer_address`, `customer_apt_number`, `customer_address_2`, `customer_city`, `customer_state`, `customer_zipcode`, `customer_instructions`, `customer_first_name`, `customer_last_name`, `customer_to_phone`, `restaurant_name`, `customer_to_phone_type`, `restaurant_from_fax_phone`, `restaurant_to_fax_phone`, `restaurant_from_phone`, `restaurant_to_phone`, `fax_message`, `sms_message`, `phone_message`, `order_confirmation_number`, `process_status`, `sleep_until`, `early_arrival`, `late_arrival`, `createdDatetime`, `processDateTime`) VALUES
(1, 1, 1, '2034449245', '43 Davenport Ave', '5F', NULL, 'New Rochelle', 'NY', '10805', 'come around back', 'Erwin', 'Bredy', '2034449245', 'Pronto', NULL, '2034449245', '2034449245', '2034449245', '+13474262330', '', 'send this sms message', 'send this phone message', '1234', '1', NULL, '1 hour', '1 hour 10 minutes', NULL, NULL),
(2, 1, 2, '2034449245', '43 Davenport Ave', '5F', NULL, 'New Rochelle', 'NY', '10805', 'come around back', 'Erwin', 'Bredy', '2034449245', 'Pronto', NULL, '2034449245', '2034449245', '2034449245', '+13474262330', NULL, 'send this sms message', 'send this phone message', '4567', '1', NULL, '1 hour', '1 hour 10 minutes', NULL, NULL),
(3, 1, 3, '2034449245', '43 Davenport Ave', '5F', NULL, 'New Rochelle', 'NY', '10805', 'come around back', 'Erwin', 'Bredy', '2034449245', 'Pronto', NULL, '2034449245', '2034449245', '2034449245', '+13474262330', NULL, 'send this sms message', 'send this phone message', '7890', '1', NULL, '1 hour ', '1 hour 10 minutes', NULL, NULL),
(4, 1, 12, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"560ca64d14934":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca64d14934","quantity":"1","total_price":"3.00","instructions":null},"560ca6528ca29":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca6528ca29","quantity":"1","total_price":"4.00","instructions":null},"560ca657cecc1":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"560ca657cecc1","quantity":"1","total_price":"5.00","instructions":null},"560ca65ab44c1":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca65ab44c1","quantity":"1","total_price":"3.00","instructions":null},"560ca65ed8771":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca65ed8771","quantity":"1","total_price":"4.00","instructions":null},"560ca661e116b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560ca661e116b","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.64","item_total":20.5,"grand_total":"22.14","created_on":1443669581},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '3718', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(5, 1, 13, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"560eaaaddb659":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eaaaddb659","quantity":"1","total_price":"3.00","instructions":null},"560eaab06d769":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eaab06d769","quantity":"1","total_price":"3.00","instructions":null},"560eaab354b46":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560eaab354b46","quantity":"1","total_price":"4.00","instructions":null},"560eaab5ce3c8":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560eaab5ce3c8","quantity":"1","total_price":"1.50","instructions":null},"560eb8021a483":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eb8021a483","quantity":"1","total_price":"3.00","instructions":null},"561072ef35a32":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561072ef35a32","quantity":"1","total_price":"5.00","instructions":"testing one two three"},"561073099fbc3":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561073099fbc3","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"2.9","sales_tax":"1.80","item_total":22.5,"grand_total":"27.20","created_on":1443801773},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '4287', '1', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(6, 1, 14, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"561077c128bae":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561077c128bae","quantity":"1","total_price":"5.00","instructions":null},"561077c456ec0":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561077c456ec0","quantity":"1","total_price":"3.00","instructions":null},"561077c75bd62":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561077c75bd62","quantity":"1","total_price":"4.00","instructions":null},"561077c9e87ae":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561077c9e87ae","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.04","item_total":13,"grand_total":"14.04","created_on":1443919809},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '1565', '1', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(7, 1, 15, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"56107ccdea40e":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107ccdea40e","quantity":"1","total_price":"1.50","instructions":null},"56107cd19a453":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107cd19a453","quantity":"1","total_price":"1.00","instructions":null},"56107cd3be4fc":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107cd3be4fc","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.44","item_total":5.5,"grand_total":"5.94","created_on":1443921101},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '5680', '1', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(8, 1, 16, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"56107f8240f07":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56107f8240f07","quantity":"1","total_price":"4.00","instructions":null},"56107f853684b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107f853684b","quantity":"1","total_price":"3.00","instructions":null},"56107f87b6a11":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107f87b6a11","quantity":"1","total_price":"1.50","instructions":null},"56107f8ada768":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107f8ada768","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.76","item_total":9.5,"grand_total":"10.26","created_on":1443921794},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7768', '1', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(9, 1, 17, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '2034449245', '', '+13474262330', '{"items":{"56107fa89e1a5":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56107fa89e1a5","quantity":"1","total_price":"5.00","instructions":null},"56107fab32354":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107fab32354","quantity":"1","total_price":"3.00","instructions":null},"56107fae05902":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107fae05902","quantity":"1","total_price":"1.00","instructions":null},"56107fb09eb39":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107fb09eb39","quantity":"1","total_price":"1.50","instructions":null},"56107fb398640":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56107fb398640","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"3.1","sales_tax":"1.24","item_total":15.5,"grand_total":"19.84","created_on":1443921832},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '4100', '1', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(10, 1, 18, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '', '', '123-456-7891', '{"items":{"5613eb9fd5300":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5613eb9fd5300","quantity":"1","total_price":"3.00","instructions":null},"5613ebb24b649":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613ebb24b649","quantity":"1","total_price":"5.00","instructions":null},"5613ec20b5e0b":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613ec20b5e0b","quantity":"1","total_price":"5.00","instructions":null},"5613f4a2d9181":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613f4a2d9181","quantity":"1","total_price":"5.00","instructions":null},"56146ba629540":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56146ba629540","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.68","item_total":21,"grand_total":"22.68","created_on":1444146079},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '2485', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(11, 1, 19, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '', '', '123-456-7891', '{"items":{"56146be805673":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56146be805673","quantity":"1","total_price":"5.00","instructions":null},"56146bebe515d":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56146bebe515d","quantity":"1","total_price":"1.50","instructions":null},"56146bef6bfbf":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56146bef6bfbf","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.92","item_total":11.5,"grand_total":"12.42","created_on":1444178920},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '1964', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(12, 1, 20, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '', '', '123-456-7891', '{"items":{"56147fcdc718d":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56147fcdc718d","quantity":"1","total_price":"1.50","instructions":null},"56147fd1902cc":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56147fd1902cc","quantity":"1","total_price":"4.00","instructions":null},"56147fd75e8fa":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56147fd75e8fa","quantity":"1","total_price":"1.00","instructions":null},"56147fda7cf5c":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56147fda7cf5c","quantity":"1","total_price":"1.50","instructions":null},"56147ff40dd19":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56147ff40dd19","quantity":"1","total_price":"5.00","instructions":null},"56147ff71a980":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56147ff71a980","quantity":"1","total_price":"3.00","instructions":null},"5614800d42146":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"5614800d42146","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.68","item_total":21,"grand_total":"22.68","created_on":1444184013},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '6904', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(13, 1, 21, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '', '', '123-456-7891', '{"items":{"56148035b9d2b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56148035b9d2b","quantity":"1","total_price":"1.50","instructions":null},"561480394ccc7":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561480394ccc7","quantity":"1","total_price":"5.00","instructions":null},"5614803c4a1bc":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"5614803c4a1bc","quantity":"1","total_price":"5.00","instructions":null},"5614804001e8b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5614804001e8b","quantity":"1","total_price":"1.50","instructions":null},"56148042aa334":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56148042aa334","quantity":"1","total_price":"1.00","instructions":null},"56148044c32e4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56148044c32e4","quantity":"1","total_price":"3.00","instructions":null},"56148048cb232":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56148048cb232","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.76","item_total":22,"grand_total":"23.76","created_on":1444184117},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '8187', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(14, 1, 22, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '', '', '+13474262330', '{"items":{"5615c71a3e372":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71a3e372","quantity":"1","total_price":"3.00","instructions":null},"5615c71c3a3b4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71c3a3b4","quantity":"1","total_price":"3.00","instructions":null},"5615c71f0c761":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71f0c761","quantity":"1","total_price":"3.00","instructions":null},"5615c72117bab":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5615c72117bab","quantity":"1","total_price":"5.00","instructions":null},"5615c72388d95":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"5615c72388d95","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.44","item_total":18,"grand_total":"19.44","created_on":1444267802},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '3046', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(15, 1, 23, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"5615cec690a0e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cec690a0e","quantity":"1","total_price":"3.00","instructions":null},"5615cec931c54":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cec931c54","quantity":"1","total_price":"3.00","instructions":null},"5615cecb1b7db":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cecb1b7db","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1444269766},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '1307', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(16, 1, 24, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561813107f212":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561813107f212","quantity":"1","total_price":"1.50","instructions":null},"56181312b37d0":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56181312b37d0","quantity":"1","total_price":"1.00","instructions":null},"561813184a142":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561813184a142","quantity":"1","total_price":"5.00","instructions":null},"5618131b37a0e":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5618131b37a0e","quantity":"1","total_price":"1.50","instructions":null},"5618131d6af0b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5618131d6af0b","quantity":"1","total_price":"1.50","instructions":null},"56181320125e3":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56181320125e3","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"2.3","sales_tax":"0.92","item_total":11.5,"grand_total":"14.72","created_on":1444418320},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7461', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(17, 1, 25, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561813e98603a":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561813e98603a","quantity":"1","total_price":"1.50","instructions":null},"561813ed1c191":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561813ed1c191","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.36","item_total":4.5,"grand_total":"4.86","created_on":1444418537},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '4340', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(18, 1, 26, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561ad2d88eabf":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2d88eabf","quantity":"1","total_price":"3.00","instructions":null},"561ad2db295f3":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2db295f3","quantity":"1","total_price":"3.00","instructions":null},"561ad2ddb60fd":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ad2ddb60fd","quantity":"1","total_price":"1.50","instructions":null},"561ad2e153ca6":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ad2e153ca6","quantity":"1","total_price":"5.00","instructions":null},"561ad2e43477b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2e43477b","quantity":"1","total_price":"3.00","instructions":null},"561ad2e749591":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad2e749591","quantity":"1","total_price":"4.00","instructions":null},"561ad2ea06ef4":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ad2ea06ef4","quantity":"1","total_price":"5.00","instructions":null},"561ad2ed3c4ee":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad2ed3c4ee","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"2.28","item_total":28.5,"grand_total":"30.78","created_on":1444598488},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '6166', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(19, 1, 27, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561ad31f49f2c":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ad31f49f2c","quantity":"1","total_price":"5.00","instructions":null},"561ad34aba4a2":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad34aba4a2","quantity":"1","total_price":"3.00","instructions":null},"561ad34d9f2cb":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ad34d9f2cb","quantity":"1","total_price":"1.50","instructions":null},"561ad3528b16a":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad3528b16a","quantity":"1","total_price":"4.00","instructions":null},"561ad356c7df0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ad356c7df0","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.48","item_total":18.5,"grand_total":"19.98","created_on":1444598559},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '5153', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(20, 1, 28, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561adf56b870e":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561adf56b870e","quantity":"1","total_price":"5.00","instructions":null},"561adf59819e8":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561adf59819e8","quantity":"1","total_price":"1.50","instructions":null},"561adf5c7c3c3":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561adf5c7c3c3","quantity":"1","total_price":"4.00","instructions":null},"561ae0128d02a":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ae0128d02a","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1444601686},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '3009', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(21, 1, 29, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561ae62b299f9":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ae62b299f9","quantity":"1","total_price":"5.00","instructions":null},"561ae62e40080":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ae62e40080","quantity":"1","total_price":"4.00","instructions":null},"561ae630961cf":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ae630961cf","quantity":"1","total_price":"5.00","instructions":null},"561ae6348718b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ae6348718b","quantity":"1","total_price":"3.00","instructions":null},"561ae636cb622":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ae636cb622","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.48","item_total":18.5,"grand_total":"19.98","created_on":1444603435},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '3504', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(22, 1, 30, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561afba7171c4":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561afba7171c4","quantity":"1","total_price":"5.00","instructions":null},"561afba9e63aa":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afba9e63aa","quantity":"1","total_price":"3.00","instructions":null},"561afbb0c5997":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbb0c5997","quantity":"1","total_price":"4.00","instructions":null},"561afbb4203d9":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561afbb4203d9","quantity":"1","total_price":"1.50","instructions":null},"561afbb7254d0":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561afbb7254d0","quantity":"1","total_price":"1.00","instructions":null},"561afbba1822e":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbba1822e","quantity":"1","total_price":"4.00","instructions":null},"561afbbcd6807":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561afbbcd6807","quantity":"1","total_price":"5.00","instructions":null},"561afbc0ccd85":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbc0ccd85","quantity":"1","total_price":"4.00","instructions":null},"561afbc484953":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561afbc484953","quantity":"1","total_price":"5.00","instructions":null},"561afbc72aeaf":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afbc72aeaf","quantity":"1","total_price":"3.00","instructions":null},"561afbc9d8949":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afbc9d8949","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"3.08","item_total":38.5,"grand_total":"41.58","created_on":1444608935},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '5738', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(23, 1, 31, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561b07c8f3407":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b07c8f3407","quantity":"1","total_price":"4.00","instructions":null},"561b07cd22ff2":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561b07cd22ff2","quantity":"1","total_price":"1.50","instructions":null},"561b07d0b174f":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561b07d0b174f","quantity":"1","total_price":"1.00","instructions":null},"561b07d3938eb":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b07d3938eb","quantity":"1","total_price":"4.00","instructions":null},"561b07d6a3b9d":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561b07d6a3b9d","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.92","item_total":11.5,"grand_total":"12.42","created_on":1444612040},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7805', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(24, 1, 32, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561b12459be0e":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561b12459be0e","quantity":"1","total_price":"5.00","instructions":null},"561b1248d90db":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b1248d90db","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1444614725},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '6401', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL);
INSERT INTO `ordernotifications` (`orderNotification_ID`, `user_id`, `order_id`, `customer_from_phone`, `customer_address`, `customer_apt_number`, `customer_address_2`, `customer_city`, `customer_state`, `customer_zipcode`, `customer_instructions`, `customer_first_name`, `customer_last_name`, `customer_to_phone`, `restaurant_name`, `customer_to_phone_type`, `restaurant_from_fax_phone`, `restaurant_to_fax_phone`, `restaurant_from_phone`, `restaurant_to_phone`, `fax_message`, `sms_message`, `phone_message`, `order_confirmation_number`, `process_status`, `sleep_until`, `early_arrival`, `late_arrival`, `createdDatetime`, `processDateTime`) VALUES
(25, 1, 33, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561b365e6ef82":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561b365e6ef82","quantity":"1","total_price":"5.00","instructions":null},"561b366259daa":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561b366259daa","quantity":"1","total_price":"1.50","instructions":null},"561b366608e80":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561b366608e80","quantity":"1","total_price":"5.00","instructions":null},"561b366d65d1d":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b366d65d1d","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.24","item_total":15.5,"grand_total":"16.74","created_on":1444623966},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '8276', '3', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(26, 1, 34, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561bc95c382ff":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561bc95c382ff","quantity":"1","total_price":"3.00","instructions":null},"561bc96139ce0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc96139ce0","quantity":"1","total_price":"5.00","instructions":null},"561bc964057dc":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc964057dc","quantity":"1","total_price":"4.00","instructions":null},"561bc96680c36":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561bc96680c36","quantity":"1","total_price":"1.00","instructions":null},"561bc969e0a74":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc969e0a74","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.36","item_total":17,"grand_total":"18.36","created_on":1444661596},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '9445', '1', '2015-10-27 17:03:42', '1 hour', '1 hour and 10 minutes', NULL, NULL),
(27, 1, 35, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"561bc9a13fbc4":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc9a13fbc4","quantity":"1","total_price":"4.00","instructions":null},"561bc9a30dd7e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561bc9a30dd7e","quantity":"1","total_price":"3.00","instructions":null},"561bc9a4e5603":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc9a4e5603","quantity":"1","total_price":"5.00","instructions":null},"561bc9a6ab957":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc9a6ab957","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.36","item_total":17,"grand_total":"18.36","created_on":1444661665},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '9082', '1', '2015-10-27 17:03:42', '1 hour', '1 hour and 10 minutes', NULL, NULL),
(28, 1, 36, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"562fe4482d598":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe4482d598","quantity":"1","total_price":"5.00","instructions":null},"562fe44a25d4f":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562fe44a25d4f","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.64","item_total":8,"grand_total":"8.64","created_on":1445979208},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '6293', '1', '2015-10-27 17:03:42', '1 hour', '1 hour and 10 minutes', NULL, NULL),
(29, 1, 37, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"562fe59175042":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe59175042","quantity":"1","total_price":"5.00","instructions":null},"562fe593d63c0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe593d63c0","quantity":"1","total_price":"5.00","instructions":null},"562fe595a6cf5":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562fe595a6cf5","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.04","item_total":13,"grand_total":"14.04","created_on":1445979537},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '8878', '1', '2015-10-27 17:03:42', '1 hour', '1 hour and 10 minutes', NULL, NULL),
(30, 1, 38, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"562ff0f079a52":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562ff0f079a52","quantity":"1","total_price":"5.00","instructions":null},"562ff0f40ce2a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562ff0f40ce2a","quantity":"1","total_price":"3.00","instructions":null},"562ff0f63fe6a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562ff0f63fe6a","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.88","item_total":11,"grand_total":"11.88","created_on":1445982448},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7691', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(31, 1, 39, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"5630067c34b15":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5630067c34b15","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.40","item_total":5,"grand_total":"5.40","created_on":1445987964},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '9907', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(32, 1, 40, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"563102ac48f79":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102ac48f79","quantity":"1","total_price":"3.00","instructions":null},"563102af4562d":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102af4562d","quantity":"1","total_price":"3.00","instructions":null},"563102b161256":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102b161256","quantity":"1","total_price":"3.00","instructions":null},"563102b32e656":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102b32e656","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1446052524},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '1198', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(33, 1, 41, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"563102c1c3342":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c1c3342","quantity":"1","total_price":"3.00","instructions":null},"563102c3a2719":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c3a2719","quantity":"1","total_price":"3.00","instructions":null},"563102c562e8a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c562e8a","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1446052545},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '2679', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(34, 1, 42, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"5642cbb723c6e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5642cbb723c6e","quantity":"1","total_price":"3.00","instructions":null},"5642cbb9e94ab":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5642cbb9e94ab","quantity":"1","total_price":"5.00","instructions":null},"5642cbbc6a35b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5642cbbc6a35b","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.76","item_total":9.5,"grand_total":"10.26","created_on":1447218103},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '6021', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(35, 1, 43, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"56435cda0a321":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56435cda0a321","quantity":"1","total_price":"5.00","instructions":null},"56435cdd00e14":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56435cdd00e14","quantity":"1","total_price":"3.00","instructions":null},"56435ce99dbfa":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56435ce99dbfa","quantity":"1","total_price":"1.00","instructions":null},"56435ceca50a5":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56435ceca50a5","quantity":"1","total_price":"1.50","instructions":null},"56435cefa34f1":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56435cefa34f1","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.16","item_total":14.5,"grand_total":"15.66","created_on":1447255258},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7654', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(36, 1, 44, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"56d08ebdc31b4":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56d08ebdc31b4","quantity":"1","total_price":"5.00","instructions":null},"56d08ec0c8fdb":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56d08ec0c8fdb","quantity":"1","total_price":"4.00","instructions":null},"56d08ec32da46":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56d08ec32da46","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1456508605},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '8803', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(37, 1, 45, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"5702a8a4d4a10":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5702a8a4d4a10","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.40","item_total":5,"grand_total":"5.40","created_on":1459792036},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '2828', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL),
(38, 1, 46, '', '43 Davenport Avenue', NULL, NULL, 'New Rochelle', 'NY', '10805', 'come around back!', 'Erwin', 'Bredy', '2034449245', NULL, NULL, '', '+13474262330', '', '2034449245', '{"items":{"5702a8de6614f":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5702a8de6614f","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.24","item_total":3,"grand_total":"3.24","created_on":1459792094},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}', '', '', '7371', '0', NULL, '1 hour', '1 hour and 10 minutes', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL,
  `source_id` varchar(250) NOT NULL,
  `receipt_number` varchar(250) NOT NULL,
  `is_charged` int(1) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL,
  `card_id` bigint(20) DEFAULT NULL,
  `amount` bigint(20) NOT NULL,
  `refund` bigint(20) NOT NULL DEFAULT '0',
  `restaurant_id` bigint(20) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `refund_on` datetime DEFAULT NULL,
  `details` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `source_id`, `receipt_number`, `is_charged`, `user_id`, `card_id`, `amount`, `refund`, `restaurant_id`, `created_on`, `refund_on`, `details`) VALUES
(1, 'ch_16nfKBCn0NbhNg20dWqaQw67', '5600aae36537d', 1, 1, NULL, 1512, 0, 1, '2015-09-22 01:12:03', NULL, '{"items":{"55ff75b302ab6":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"55ff75b302ab6","quantity":"1","total_price":"4.00","instructions":null},"55ff75b524d77":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"55ff75b524d77","quantity":"1","total_price":"5.00","instructions":null},"55ff75b825dc0":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"55ff75b825dc0","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.12","item_total":14,"grand_total":"15.12","created_on":1442805171},"delivered_to":{"id":"6","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Ave","address_2":null,"apt_number":"5F","phone":"2034449245","instructions":"Come around back!!!!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-21 21:08:08","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(2, 'ch_16qqAZDhDw8iUu59GE7JNkzT', '560c36a424350', 1, 1, 2, 12803, 0, 1, '2015-09-30 19:23:16', NULL, '{"items":{"56084a4167110":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56084a4167110","quantity":"1","total_price":"5.00","instructions":null},"56084a43da956":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56084a43da956","quantity":"1","total_price":"3.00","instructions":null},"56084a462e4e7":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56084a462e4e7","quantity":"1","total_price":"4.00","instructions":null},"56084a492e340":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56084a492e340","quantity":"1","total_price":"5.00","instructions":null},"56084a4b7a1e2":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56084a4b7a1e2","quantity":"1","total_price":"1.50","instructions":null},"56084a4d6f879":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56084a4d6f879","quantity":"1","total_price":"1.00","instructions":null},"56084a4fa59bc":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56084a4fa59bc","quantity":"1","total_price":"3.00","instructions":null},"560857cdadf4a":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560857cdadf4a","quantity":"1","total_price":"5.00","instructions":null},"560857d416931":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560857d416931","quantity":"1","total_price":"3.00","instructions":null},"5608948aeefce":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5608948aeefce","quantity":"1","total_price":"3.00","instructions":null},"560894936d871":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560894936d871","quantity":"1","total_price":"1.00","instructions":null},"56090e979bc1a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56090e979bc1a","quantity":"1","total_price":"3.00","instructions":null},"56090e9983346":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56090e9983346","quantity":"1","total_price":"3.00","instructions":null},"56090e9b4cf57":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56090e9b4cf57","quantity":"1","total_price":"3.00","instructions":null},"56090ea2691eb":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56090ea2691eb","quantity":"1","total_price":"3.00","instructions":null},"56090ea8a3493":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56090ea8a3493","quantity":"1","total_price":"5.00","instructions":null},"56090eafd2888":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56090eafd2888","quantity":"1","total_price":"3.00","instructions":null},"5609cb30c05fe":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5609cb30c05fe","quantity":"1","total_price":"5.00","instructions":null},"5609cb32cb038":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5609cb32cb038","quantity":"1","total_price":"3.00","instructions":null},"5609cb3516ae8":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5609cb3516ae8","quantity":"1","total_price":"5.00","instructions":null},"5609cb3794f38":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"5609cb3794f38","quantity":"1","total_price":"4.00","instructions":null},"5609cb3a49ded":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"5609cb3a49ded","quantity":"1","total_price":"4.00","instructions":null},"5609fa3e0d8a1":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"5609fa3e0d8a1","quantity":"1","total_price":"5.00","instructions":null},"5609fa4897656":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5609fa4897656","quantity":"1","total_price":"5.00","instructions":null},"5609fa557dab0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5609fa557dab0","quantity":"1","total_price":"5.00","instructions":null},"560a00658be3c":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560a00658be3c","quantity":"1","total_price":"3.00","instructions":null},"560a8caff38ce":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560a8caff38ce","quantity":"1","total_price":"5.00","instructions":null},"560a8cb26359b":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560a8cb26359b","quantity":"1","total_price":"5.00","instructions":null},"560a8cb476450":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560a8cb476450","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"10.85","sales_tax":"8.68","item_total":108.5,"grand_total":"128.03","created_on":1443383873},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(3, 'ch_16qqJIDhDw8iUu59WqOPj1ut', '560c38c0ce379', 1, 1, 3, 3304, 0, 1, '2015-09-30 19:32:16', NULL, '{"items":{"560c3855e0c01":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560c3855e0c01","quantity":"1","total_price":"4.00","instructions":null},"560c38583f99e":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560c38583f99e","quantity":"1","total_price":"5.00","instructions":null},"560c385a02c46":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c385a02c46","quantity":"1","total_price":"3.00","instructions":null},"560c385d30e48":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560c385d30e48","quantity":"1","total_price":"1.50","instructions":null},"560c385f022d6":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c385f022d6","quantity":"1","total_price":"3.00","instructions":null},"560c3864372c2":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560c3864372c2","quantity":"1","total_price":"1.00","instructions":null},"560c38683896d":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"560c38683896d","quantity":"1","total_price":"5.00","instructions":null},"560c386a68a8d":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560c386a68a8d","quantity":"1","total_price":"1.00","instructions":null},"560c386c9a460":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560c386c9a460","quantity":"1","total_price":"1.50","instructions":null},"560c386f8ce40":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560c386f8ce40","quantity":"1","total_price":"1.50","instructions":null},"560c3871434f2":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560c3871434f2","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"2.8","sales_tax":"2.24","item_total":28,"grand_total":"33.04","created_on":1443641429},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(4, 'ch_16qqRdDhDw8iUu59XFYZmtxR', '560c3ac56b4aa', 1, 1, 4, 2432, 0, 1, '2015-09-30 19:40:53', NULL, '{"items":{"560c3a69b9d81":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c3a69b9d81","quantity":"1","total_price":"3.00","instructions":null},"560c3a6bc7ef3":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560c3a6bc7ef3","quantity":"1","total_price":"5.00","instructions":null},"560c3a6e09197":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560c3a6e09197","quantity":"1","total_price":"4.00","instructions":null},"560c3a6fade85":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560c3a6fade85","quantity":"1","total_price":"1.00","instructions":null},"560c3a7377449":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560c3a7377449","quantity":"1","total_price":"1.00","instructions":null},"560c3a754bb63":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"560c3a754bb63","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"3.8","sales_tax":"1.52","item_total":19,"grand_total":"24.32","created_on":1443641961},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(5, 'ch_16qwUkDhDw8iUu590ErHQZ9W', '560c959ee702a', 1, 1, 4, 3968, 0, 1, '2015-10-01 02:08:30', NULL, '{"items":{"560c95746750d":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560c95746750d","quantity":"1","total_price":"5.00","instructions":null},"560c9576a8079":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c9576a8079","quantity":"1","total_price":"3.00","instructions":null},"560c957a1d11a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c957a1d11a","quantity":"1","total_price":"3.00","instructions":null},"560c957c67ee4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c957c67ee4","quantity":"1","total_price":"3.00","instructions":null},"560c957ec20ca":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c957ec20ca","quantity":"1","total_price":"3.00","instructions":null},"560c95809b1d2":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c95809b1d2","quantity":"1","total_price":"3.00","instructions":null},"560c95828d55d":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c95828d55d","quantity":"1","total_price":"3.00","instructions":null},"560c95848c8d0":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c95848c8d0","quantity":"1","total_price":"3.00","instructions":null},"560c95871545e":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560c95871545e","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"6.2","sales_tax":"2.48","item_total":31,"grand_total":"39.68","created_on":1443665268},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(6, 'ch_16qx2jDhDw8iUu59ECQlIoMh', '560c9dd951594', 1, 1, 4, 2214, 0, 1, '2015-10-01 02:43:37', NULL, '{"items":{"560c9db585a84":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560c9db585a84","quantity":"1","total_price":"5.00","instructions":null},"560c9db767294":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c9db767294","quantity":"1","total_price":"3.00","instructions":null},"560c9dba9bdb5":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560c9dba9bdb5","quantity":"1","total_price":"4.00","instructions":null},"560c9dbd5c4c4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560c9dbd5c4c4","quantity":"1","total_price":"3.00","instructions":null},"560c9dc046cf4":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560c9dc046cf4","quantity":"1","total_price":"1.50","instructions":null},"560c9dc46d554":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560c9dc46d554","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.64","item_total":20.5,"grand_total":"22.14","created_on":1443667381},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(7, 'ch_16qxGYDhDw8iUu59Dsp6ShSF', '560ca13239b50', 1, 1, 4, 864, 0, 1, '2015-10-01 02:57:54', NULL, '{"items":{"560ca07b7080e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca07b7080e","quantity":"1","total_price":"3.00","instructions":null},"560ca08fc4e15":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca08fc4e15","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.64","item_total":8,"grand_total":"8.64","created_on":1443668091},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(8, 'ch_16qxKfDhDw8iUu595j45gxap', '560ca231c2f64', 1, 1, 4, 2862, 0, 1, '2015-10-01 03:02:09', NULL, '{"items":{"560ca20bcfba8":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca20bcfba8","quantity":"1","total_price":"3.00","instructions":null},"560ca20e78102":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca20e78102","quantity":"1","total_price":"3.00","instructions":null},"560ca2111ed1c":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca2111ed1c","quantity":"1","total_price":"4.00","instructions":null},"560ca21327439":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca21327439","quantity":"1","total_price":"3.00","instructions":null},"560ca21533949":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca21533949","quantity":"1","total_price":"5.00","instructions":null},"560ca217986cf":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca217986cf","quantity":"1","total_price":"4.00","instructions":null},"560ca2198b005":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca2198b005","quantity":"1","total_price":"3.00","instructions":null},"560ca21be6447":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560ca21be6447","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"2.12","item_total":26.5,"grand_total":"28.62","created_on":1443668491},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(9, 'ch_16qxP2DhDw8iUu59jtVnhMyh', '560ca340b26a8', 1, 1, 4, 1566, 0, 1, '2015-10-01 03:06:40', NULL, '{"items":{"560ca30b79f3c":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560ca30b79f3c","quantity":"1","total_price":"1.50","instructions":null},"560ca30fb69c8":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca30fb69c8","quantity":"1","total_price":"3.00","instructions":null},"560ca3128dff4":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca3128dff4","quantity":"1","total_price":"5.00","instructions":null},"560ca31550a57":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"560ca31550a57","quantity":"1","total_price":"1.00","instructions":null},"560ca3208199a":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca3208199a","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.16","item_total":14.5,"grand_total":"15.66","created_on":1443668747},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(10, 'ch_16qxXwDhDw8iUu59KQqblNYw', '560ca56854f86', 1, 1, 3, 3294, 0, 1, '2015-10-01 03:15:52', NULL, '{"items":{"560ca54c4b2ff":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca54c4b2ff","quantity":"1","total_price":"3.00","instructions":null},"560ca54e4851d":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca54e4851d","quantity":"1","total_price":"5.00","instructions":null},"560ca550eae78":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca550eae78","quantity":"1","total_price":"4.00","instructions":null},"560ca552d7b8b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca552d7b8b","quantity":"1","total_price":"3.00","instructions":null},"560ca5550c1c1":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca5550c1c1","quantity":"1","total_price":"5.00","instructions":null},"560ca557ac527":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca557ac527","quantity":"1","total_price":"4.00","instructions":null},"560ca55c576bc":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560ca55c576bc","quantity":"1","total_price":"1.50","instructions":null},"560ca55ed3e25":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"560ca55ed3e25","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"2.44","item_total":30.5,"grand_total":"32.94","created_on":1443669324},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(11, 'ch_16qxZzDhDw8iUu59eCZmEy7O', '560ca5e787e6b', 1, 1, 2, 3240, 0, 1, '2015-10-01 03:17:59', NULL, '{"items":{"560ca5cd90d04":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca5cd90d04","quantity":"1","total_price":"3.00","instructions":null},"560ca5cfde448":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca5cfde448","quantity":"1","total_price":"5.00","instructions":null},"560ca5d1e64ad":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca5d1e64ad","quantity":"1","total_price":"4.00","instructions":null},"560ca5d3b8a66":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca5d3b8a66","quantity":"1","total_price":"3.00","instructions":null},"560ca5d57917a":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"560ca5d57917a","quantity":"1","total_price":"5.00","instructions":null},"560ca5d78c260":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca5d78c260","quantity":"1","total_price":"4.00","instructions":null},"560ca5d95206a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca5d95206a","quantity":"1","total_price":"3.00","instructions":null},"560ca5dc968b3":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca5dc968b3","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"2.40","item_total":30,"grand_total":"32.40","created_on":1443669453},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(12, 'ch_16qxc5DhDw8iUu59JQq6vCft', '560ca669a526f', 1, 1, 2, 2214, 0, 1, '2015-10-01 03:20:09', NULL, '{"items":{"560ca64d14934":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca64d14934","quantity":"1","total_price":"3.00","instructions":null},"560ca6528ca29":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca6528ca29","quantity":"1","total_price":"4.00","instructions":null},"560ca657cecc1":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"560ca657cecc1","quantity":"1","total_price":"5.00","instructions":null},"560ca65ab44c1":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560ca65ab44c1","quantity":"1","total_price":"3.00","instructions":null},"560ca65ed8771":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560ca65ed8771","quantity":"1","total_price":"4.00","instructions":null},"560ca661e116b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560ca661e116b","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.64","item_total":20.5,"grand_total":"22.14","created_on":1443669581},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(13, 'ch_16s0PdDhDw8iUu59W6Zir0r4', '561073697375b', 1, 1, 2, 2720, 0, 1, '2015-10-04 00:31:37', NULL, '{"items":{"560eaaaddb659":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eaaaddb659","quantity":"1","total_price":"3.00","instructions":null},"560eaab06d769":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eaab06d769","quantity":"1","total_price":"3.00","instructions":null},"560eaab354b46":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"560eaab354b46","quantity":"1","total_price":"4.00","instructions":null},"560eaab5ce3c8":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"560eaab5ce3c8","quantity":"1","total_price":"1.50","instructions":null},"560eb8021a483":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"560eb8021a483","quantity":"1","total_price":"3.00","instructions":null},"561072ef35a32":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561072ef35a32","quantity":"1","total_price":"5.00","instructions":"testing one two three"},"561073099fbc3":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561073099fbc3","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"2.9","sales_tax":"1.80","item_total":22.5,"grand_total":"27.20","created_on":1443801773},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(14, 'ch_16s0p9DhDw8iUu59u8MOyGzj', '56107997a058b', 1, 1, 2, 1404, 0, 1, '2015-10-04 00:57:59', NULL, '{"items":{"561077c128bae":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561077c128bae","quantity":"1","total_price":"5.00","instructions":null},"561077c456ec0":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561077c456ec0","quantity":"1","total_price":"3.00","instructions":null},"561077c75bd62":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561077c75bd62","quantity":"1","total_price":"4.00","instructions":null},"561077c9e87ae":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561077c9e87ae","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.04","item_total":13,"grand_total":"14.04","created_on":1443919809},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(15, 'ch_16s1DADhDw8iUu59fjj0QFcA', '56107f68ba714', 1, 1, 2, 594, 0, 1, '2015-10-04 01:22:48', NULL, '{"items":{"56107ccdea40e":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107ccdea40e","quantity":"1","total_price":"1.50","instructions":null},"56107cd19a453":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107cd19a453","quantity":"1","total_price":"1.00","instructions":null},"56107cd3be4fc":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107cd3be4fc","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.44","item_total":5.5,"grand_total":"5.94","created_on":1443921101},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(16, 'ch_16s1DoDhDw8iUu596jwcVfL3', '56107f90ab8c4', 1, 1, 2, 1026, 0, 1, '2015-10-04 01:23:28', NULL, '{"items":{"56107f8240f07":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56107f8240f07","quantity":"1","total_price":"4.00","instructions":null},"56107f853684b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107f853684b","quantity":"1","total_price":"3.00","instructions":null},"56107f87b6a11":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107f87b6a11","quantity":"1","total_price":"1.50","instructions":null},"56107f8ada768":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107f8ada768","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.76","item_total":9.5,"grand_total":"10.26","created_on":1443921794},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(17, 'ch_16s1EWDhDw8iUu59KhwnODFT', '56107fbd022b2', 1, 1, 2, 1984, 0, 1, '2015-10-04 01:24:13', NULL, '{"items":{"56107fa89e1a5":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56107fa89e1a5","quantity":"1","total_price":"5.00","instructions":null},"56107fab32354":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56107fab32354","quantity":"1","total_price":"3.00","instructions":null},"56107fae05902":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56107fae05902","quantity":"1","total_price":"1.00","instructions":null},"56107fb09eb39":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56107fb09eb39","quantity":"1","total_price":"1.50","instructions":null},"56107fb398640":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56107fb398640","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"3.1","sales_tax":"1.24","item_total":15.5,"grand_total":"19.84","created_on":1443921832},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(18, 'ch_16t65xDhDw8iUu59458TvqaH', '56146bb5769d7', 1, 1, 2, 2268, 0, 1, '2015-10-07 00:47:49', NULL, '{"items":{"5613eb9fd5300":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5613eb9fd5300","quantity":"1","total_price":"3.00","instructions":null},"5613ebb24b649":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613ebb24b649","quantity":"1","total_price":"5.00","instructions":null},"5613ec20b5e0b":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613ec20b5e0b","quantity":"1","total_price":"5.00","instructions":null},"5613f4a2d9181":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5613f4a2d9181","quantity":"1","total_price":"5.00","instructions":null},"56146ba629540":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56146ba629540","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.68","item_total":21,"grand_total":"22.68","created_on":1444146079},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(19, 'ch_16t66zDhDw8iUu59xTVRR38l', '56146bf5632b2', 1, 1, 2, 1242, 0, 1, '2015-10-07 00:48:53', NULL, '{"items":{"56146be805673":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56146be805673","quantity":"1","total_price":"5.00","instructions":null},"56146bebe515d":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56146bebe515d","quantity":"1","total_price":"1.50","instructions":null},"56146bef6bfbf":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56146bef6bfbf","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.92","item_total":11.5,"grand_total":"12.42","created_on":1444178920},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(20, 'ch_16t7SBDhDw8iUu59EVcf77P4', '5614801bb96c5', 1, 1, 2, 2268, 0, 1, '2015-10-07 02:14:51', NULL, '{"items":{"56147fcdc718d":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56147fcdc718d","quantity":"1","total_price":"1.50","instructions":null},"56147fd1902cc":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56147fd1902cc","quantity":"1","total_price":"4.00","instructions":null},"56147fd75e8fa":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56147fd75e8fa","quantity":"1","total_price":"1.00","instructions":null},"56147fda7cf5c":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56147fda7cf5c","quantity":"1","total_price":"1.50","instructions":null},"56147ff40dd19":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56147ff40dd19","quantity":"1","total_price":"5.00","instructions":null},"56147ff71a980":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56147ff71a980","quantity":"1","total_price":"3.00","instructions":null},"5614800d42146":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"5614800d42146","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.68","item_total":21,"grand_total":"22.68","created_on":1444184013},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}');
INSERT INTO `orders` (`id`, `source_id`, `receipt_number`, `is_charged`, `user_id`, `card_id`, `amount`, `refund`, `restaurant_id`, `created_on`, `refund_on`, `details`) VALUES
(21, 'ch_16t7TgDhDw8iUu59mJJk0Svq', '56148078c064a', 1, 1, 2, 2376, 0, 1, '2015-10-07 02:16:24', NULL, '{"items":{"56148035b9d2b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56148035b9d2b","quantity":"1","total_price":"1.50","instructions":null},"561480394ccc7":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561480394ccc7","quantity":"1","total_price":"5.00","instructions":null},"5614803c4a1bc":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"5614803c4a1bc","quantity":"1","total_price":"5.00","instructions":null},"5614804001e8b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5614804001e8b","quantity":"1","total_price":"1.50","instructions":null},"56148042aa334":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56148042aa334","quantity":"1","total_price":"1.00","instructions":null},"56148044c32e4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56148044c32e4","quantity":"1","total_price":"3.00","instructions":null},"56148048cb232":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"56148048cb232","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.76","item_total":22,"grand_total":"23.76","created_on":1444184117},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(22, 'ch_16tTG0DhDw8iUu59c1I8uQYm', '5615c784e6f2b', 1, 1, 2, 1944, 0, 1, '2015-10-08 01:31:48', NULL, '{"items":{"5615c71a3e372":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71a3e372","quantity":"1","total_price":"3.00","instructions":null},"5615c71c3a3b4":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71c3a3b4","quantity":"1","total_price":"3.00","instructions":null},"5615c71f0c761":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615c71f0c761","quantity":"1","total_price":"3.00","instructions":null},"5615c72117bab":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5615c72117bab","quantity":"1","total_price":"5.00","instructions":null},"5615c72388d95":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"5615c72388d95","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"123-456-7891","fax":"","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.44","item_total":18,"grand_total":"19.44","created_on":1444267802},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(23, 'ch_16tTkCDhDw8iUu596z38MdTd', '5615ced080adc', 1, 1, 2, 972, 0, 1, '2015-10-08 02:02:56', NULL, '{"items":{"5615cec690a0e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cec690a0e","quantity":"1","total_price":"3.00","instructions":null},"5615cec931c54":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cec931c54","quantity":"1","total_price":"3.00","instructions":null},"5615cecb1b7db":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5615cecb1b7db","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1444269766},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(24, 'ch_16u6OfDhDw8iUu59le2809LA', '56181335ea983', 1, 1, 2, 1472, 0, 1, '2015-10-09 19:19:17', NULL, '{"items":{"561813107f212":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561813107f212","quantity":"1","total_price":"1.50","instructions":null},"56181312b37d0":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56181312b37d0","quantity":"1","total_price":"1.00","instructions":null},"561813184a142":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561813184a142","quantity":"1","total_price":"5.00","instructions":null},"5618131b37a0e":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5618131b37a0e","quantity":"1","total_price":"1.50","instructions":null},"5618131d6af0b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5618131d6af0b","quantity":"1","total_price":"1.50","instructions":null},"56181320125e3":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56181320125e3","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":"2.3","sales_tax":"0.92","item_total":11.5,"grand_total":"14.72","created_on":1444418320},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(25, 'ch_16u6moDhDw8iUu59xet6Eqtr', '5618190f22981', 1, 1, 2, 486, 0, 1, '2015-10-09 19:44:15', NULL, '{"items":{"561813e98603a":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561813e98603a","quantity":"1","total_price":"1.50","instructions":null},"561813ed1c191":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561813ed1c191","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.36","item_total":4.5,"grand_total":"4.86","created_on":1444418537},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(26, 'ch_16urGXDhDw8iUu59G4JELz6E', '561ad2f9db32f', 1, 1, 2, 3078, 0, 1, '2015-10-11 21:22:01', NULL, '{"items":{"561ad2d88eabf":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2d88eabf","quantity":"1","total_price":"3.00","instructions":null},"561ad2db295f3":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2db295f3","quantity":"1","total_price":"3.00","instructions":null},"561ad2ddb60fd":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ad2ddb60fd","quantity":"1","total_price":"1.50","instructions":null},"561ad2e153ca6":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ad2e153ca6","quantity":"1","total_price":"5.00","instructions":null},"561ad2e43477b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad2e43477b","quantity":"1","total_price":"3.00","instructions":null},"561ad2e749591":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad2e749591","quantity":"1","total_price":"4.00","instructions":null},"561ad2ea06ef4":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ad2ea06ef4","quantity":"1","total_price":"5.00","instructions":null},"561ad2ed3c4ee":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad2ed3c4ee","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"2.28","item_total":28.5,"grand_total":"30.78","created_on":1444598488},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(27, 'ch_16urIBDhDw8iUu59E7qafuMI', '561ad36023a9f', 1, 1, 2, 1998, 0, 1, '2015-10-11 21:23:44', NULL, '{"items":{"561ad31f49f2c":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ad31f49f2c","quantity":"1","total_price":"5.00","instructions":null},"561ad34aba4a2":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ad34aba4a2","quantity":"1","total_price":"3.00","instructions":null},"561ad34d9f2cb":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ad34d9f2cb","quantity":"1","total_price":"1.50","instructions":null},"561ad3528b16a":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ad3528b16a","quantity":"1","total_price":"4.00","instructions":null},"561ad356c7df0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ad356c7df0","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.48","item_total":18.5,"grand_total":"19.98","created_on":1444598559},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(28, 'ch_16us8iDhDw8iUu59G9mMe2Ng', '561ae018ebeb3', 1, 1, 2, 1296, 0, 1, '2015-10-11 22:18:00', NULL, '{"items":{"561adf56b870e":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561adf56b870e","quantity":"1","total_price":"5.00","instructions":null},"561adf59819e8":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561adf59819e8","quantity":"1","total_price":"1.50","instructions":null},"561adf5c7c3c3":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561adf5c7c3c3","quantity":"1","total_price":"4.00","instructions":null},"561ae0128d02a":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ae0128d02a","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1444601686},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(29, 'ch_16usY5DhDw8iUu59nVE8OEwy', '561ae63d428c4', 1, 1, 2, 1998, 0, 1, '2015-10-11 22:44:13', NULL, '{"items":{"561ae62b299f9":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561ae62b299f9","quantity":"1","total_price":"5.00","instructions":null},"561ae62e40080":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561ae62e40080","quantity":"1","total_price":"4.00","instructions":null},"561ae630961cf":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561ae630961cf","quantity":"1","total_price":"5.00","instructions":null},"561ae6348718b":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561ae6348718b","quantity":"1","total_price":"3.00","instructions":null},"561ae636cb622":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561ae636cb622","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.48","item_total":18.5,"grand_total":"19.98","created_on":1444603435},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(30, 'ch_16utzNDhDw8iUu59wYXEEffr', '561afbdda1e80', 1, 1, 2, 4158, 0, 1, '2015-10-12 00:16:29', NULL, '{"items":{"561afba7171c4":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561afba7171c4","quantity":"1","total_price":"5.00","instructions":null},"561afba9e63aa":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afba9e63aa","quantity":"1","total_price":"3.00","instructions":null},"561afbb0c5997":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbb0c5997","quantity":"1","total_price":"4.00","instructions":null},"561afbb4203d9":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561afbb4203d9","quantity":"1","total_price":"1.50","instructions":null},"561afbb7254d0":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561afbb7254d0","quantity":"1","total_price":"1.00","instructions":null},"561afbba1822e":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbba1822e","quantity":"1","total_price":"4.00","instructions":null},"561afbbcd6807":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561afbbcd6807","quantity":"1","total_price":"5.00","instructions":null},"561afbc0ccd85":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561afbc0ccd85","quantity":"1","total_price":"4.00","instructions":null},"561afbc484953":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561afbc484953","quantity":"1","total_price":"5.00","instructions":null},"561afbc72aeaf":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afbc72aeaf","quantity":"1","total_price":"3.00","instructions":null},"561afbc9d8949":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561afbc9d8949","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"3.08","item_total":38.5,"grand_total":"41.58","created_on":1444608935},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(31, 'ch_16uupEDhDw8iUu59KmbEn2cn', '561b086cd2aad', 1, 1, 2, 1242, 0, 1, '2015-10-12 01:10:04', NULL, '{"items":{"561b07c8f3407":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b07c8f3407","quantity":"1","total_price":"4.00","instructions":null},"561b07cd22ff2":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561b07cd22ff2","quantity":"1","total_price":"1.50","instructions":null},"561b07d0b174f":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561b07d0b174f","quantity":"1","total_price":"1.00","instructions":null},"561b07d3938eb":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b07d3938eb","quantity":"1","total_price":"4.00","instructions":null},"561b07d6a3b9d":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561b07d6a3b9d","quantity":"1","total_price":"1.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.92","item_total":11.5,"grand_total":"12.42","created_on":1444612040},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(32, 'ch_16uvUQDhDw8iUu59tOR7cTiz', '561b126669c4d', 1, 1, 2, 972, 0, 1, '2015-10-12 01:52:38', NULL, '{"items":{"561b12459be0e":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561b12459be0e","quantity":"1","total_price":"5.00","instructions":null},"561b1248d90db":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b1248d90db","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1444614725},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(33, 'ch_16uxtHDhDw8iUu59gYX35wHR', '561b3673a1ce3', 1, 1, 2, 1674, 0, 1, '2015-10-12 04:26:27', NULL, '{"items":{"561b365e6ef82":{"id":"4","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/d.jpg","item":"Na-no-hana Oyster","description":null,"price":"5.00","cart_id":"561b365e6ef82","quantity":"1","total_price":"5.00","instructions":null},"561b366259daa":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"561b366259daa","quantity":"1","total_price":"1.50","instructions":null},"561b366608e80":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561b366608e80","quantity":"1","total_price":"5.00","instructions":null},"561b366d65d1d":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561b366d65d1d","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.24","item_total":15.5,"grand_total":"16.74","created_on":1444623966},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(34, 'ch_16v7ghDhDw8iUu59T04xQWgM', '561bc98fdad16', 1, 1, 2, 1836, 0, 1, '2015-10-12 14:54:07', NULL, '{"items":{"561bc95c382ff":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561bc95c382ff","quantity":"1","total_price":"3.00","instructions":null},"561bc96139ce0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc96139ce0","quantity":"1","total_price":"5.00","instructions":null},"561bc964057dc":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc964057dc","quantity":"1","total_price":"4.00","instructions":null},"561bc96680c36":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"561bc96680c36","quantity":"1","total_price":"1.00","instructions":null},"561bc969e0a74":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc969e0a74","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.36","item_total":17,"grand_total":"18.36","created_on":1444661596},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(35, 'ch_16v7hBDhDw8iUu59ilZU3L9s', '561bc9ae0fb4a', 1, 1, 2, 1836, 0, 1, '2015-10-12 14:54:38', NULL, '{"items":{"561bc9a13fbc4":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"561bc9a13fbc4","quantity":"1","total_price":"4.00","instructions":null},"561bc9a30dd7e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"561bc9a30dd7e","quantity":"1","total_price":"3.00","instructions":null},"561bc9a4e5603":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc9a4e5603","quantity":"1","total_price":"5.00","instructions":null},"561bc9a6ab957":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"561bc9a6ab957","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.36","item_total":17,"grand_total":"18.36","created_on":1444661665},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(36, 'ch_170eS9DhDw8iUu59hIBjWKai', '562fe465c5f15', 1, 1, 2, 864, 0, 1, '2015-10-27 20:53:57', NULL, '{"items":{"562fe4482d598":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe4482d598","quantity":"1","total_price":"5.00","instructions":null},"562fe44a25d4f":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562fe44a25d4f","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.64","item_total":8,"grand_total":"8.64","created_on":1445979208},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(37, 'ch_170eXMDhDw8iUu59wwWxapVF', '562fe5a9119f6', 1, 1, 2, 1404, 0, 1, '2015-10-27 20:59:21', NULL, '{"items":{"562fe59175042":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe59175042","quantity":"1","total_price":"5.00","instructions":null},"562fe593d63c0":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562fe593d63c0","quantity":"1","total_price":"5.00","instructions":null},"562fe595a6cf5":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562fe595a6cf5","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.04","item_total":13,"grand_total":"14.04","created_on":1445979537},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(38, 'ch_170fIVDhDw8iUu59etiCwGxx', '562ff113bb601', 1, 1, 2, 1188, 0, 1, '2015-10-27 21:48:03', NULL, '{"items":{"562ff0f079a52":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"562ff0f079a52","quantity":"1","total_price":"5.00","instructions":null},"562ff0f40ce2a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562ff0f40ce2a","quantity":"1","total_price":"3.00","instructions":null},"562ff0f63fe6a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"562ff0f63fe6a","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.88","item_total":11,"grand_total":"11.88","created_on":1445982448},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(39, 'ch_170gjYDhDw8iUu59S4zSylUn', '563006a4b07df', 1, 1, 2, 540, 0, 1, '2015-10-27 23:20:04', NULL, '{"items":{"5630067c34b15":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5630067c34b15","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.40","item_total":5,"grand_total":"5.40","created_on":1445987964},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(40, 'ch_170xWNDhDw8iUu59yhF35x2a', '563102b80d697', 1, 1, 2, 1296, 0, 1, '2015-10-28 17:15:36', NULL, '{"items":{"563102ac48f79":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102ac48f79","quantity":"1","total_price":"3.00","instructions":null},"563102af4562d":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102af4562d","quantity":"1","total_price":"3.00","instructions":null},"563102b161256":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102b161256","quantity":"1","total_price":"3.00","instructions":null},"563102b32e656":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102b32e656","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1446052524},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(41, 'ch_170xWfDhDw8iUu59d2Ef9SQ3', '563102ca3acfc', 1, 1, 2, 972, 0, 1, '2015-10-28 17:15:54', NULL, '{"items":{"563102c1c3342":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c1c3342","quantity":"1","total_price":"3.00","instructions":null},"563102c3a2719":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c3a2719","quantity":"1","total_price":"3.00","instructions":null},"563102c562e8a":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"563102c562e8a","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.72","item_total":9,"grand_total":"9.72","created_on":1446052545},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(42, 'ch_175qk5DhDw8iUu59N90TUZfx', '5642cbc603816', 1, 1, 2, 1026, 0, 1, '2015-11-11 05:01:58', NULL, '{"items":{"5642cbb723c6e":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5642cbb723c6e","quantity":"1","total_price":"3.00","instructions":null},"5642cbb9e94ab":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5642cbb9e94ab","quantity":"1","total_price":"5.00","instructions":null},"5642cbbc6a35b":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"5642cbbc6a35b","quantity":"1","total_price":"1.50","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.76","item_total":9.5,"grand_total":"10.26","created_on":1447218103},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(43, 'ch_17689KDhDw8iUu59qfG89QDj', '5643d126e14b0', 1, 1, 2, 1566, 0, 1, '2015-11-11 23:37:10', NULL, '{"items":{"56435cda0a321":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56435cda0a321","quantity":"1","total_price":"5.00","instructions":null},"56435cdd00e14":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56435cdd00e14","quantity":"1","total_price":"3.00","instructions":null},"56435ce99dbfa":{"id":"5","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/e.jpg","item":"Yude-tamago","description":null,"price":"1.00","cart_id":"56435ce99dbfa","quantity":"1","total_price":"1.00","instructions":null},"56435ceca50a5":{"id":"6","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/f.jpg","item":"Miso Soup","description":null,"price":"1.50","cart_id":"56435ceca50a5","quantity":"1","total_price":"1.50","instructions":null},"56435cefa34f1":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56435cefa34f1","quantity":"1","total_price":"4.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"1.16","item_total":14.5,"grand_total":"15.66","created_on":1447255258},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(44, 'ch_17ipdADhDw8iUu59H4QpoaPf', '56d08edcbb991', 1, 1, 2, 1296, 0, 1, '2016-02-26 17:43:56', NULL, '{"items":{"56d08ebdc31b4":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"56d08ebdc31b4","quantity":"1","total_price":"5.00","instructions":null},"56d08ec0c8fdb":{"id":"1","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/a.jpg","item":"Edamame","description":null,"price":"4.00","cart_id":"56d08ec0c8fdb","quantity":"1","total_price":"4.00","instructions":null},"56d08ec32da46":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"56d08ec32da46","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.96","item_total":12,"grand_total":"12.96","created_on":1456508605},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(45, 'ch_17wbnVDhDw8iUu59eUvhsSV7', '5702a8b5a9a05', 1, 1, 2, 540, 0, 1, '2016-04-04 17:47:33', NULL, '{"items":{"5702a8a4d4a10":{"id":"2","restaurant_id":"1","source":"","image":"\\/img\\/food\\/b.jpg","item":"Atsu-giri","description":null,"price":"5.00","cart_id":"5702a8a4d4a10","quantity":"1","total_price":"5.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.40","item_total":5,"grand_total":"5.40","created_on":1459792036},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}'),
(46, 'ch_17wboFDhDw8iUu592IypXnnL', '5702a8e3adeb3', 1, 1, 4, 324, 0, 1, '2016-04-04 17:48:19', NULL, '{"items":{"5702a8de6614f":{"id":"3","restaurant_id":"1","source":null,"image":"\\/img\\/food\\/c.jpg","item":"Kimchi","description":null,"price":"3.00","cart_id":"5702a8de6614f","quantity":"1","total_price":"3.00","instructions":null}},"restaurant":{"id":"1","restaurant":"Sapporo","phone":"2034449245","fax":"+13474262330","address":"152 W 49th St","city_id":"1","state_id":"1","zipcode":"10019","opens":"800","closes":"1300","delivery_radius":"5","latitude":"40.760052","longitue":"-73.983307","rating":"3.0","price":"$$","full_address":"152 W 49th St, New York, NY 10019"},"summary":{"tip":0,"sales_tax":"0.24","item_total":3,"grand_total":"3.24","created_on":1459792094},"delivered_to":{"id":"7","user_id":"1","fname":"Erwin","lname":"Bredy","address_1":"43 Davenport Avenue","address_2":null,"apt_number":null,"phone":"2034449245","sms_enabled":"0","instructions":"come around back!","city_id":"2","state_id":"1","zip_code":"10805","last_used":null,"created_on":"2015-09-27 12:25:28","last_edited":null,"city":"New Rochelle","state":"NY"}}');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `permission` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint(20) NOT NULL,
  `restaurant` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city_id` bigint(20) DEFAULT NULL,
  `state_id` bigint(20) DEFAULT NULL,
  `zipcode` varchar(100) NOT NULL,
  `opens` bigint(20) NOT NULL,
  `closes` bigint(20) NOT NULL,
  `delivery_radius` int(6) NOT NULL DEFAULT '0',
  `latitude` float(10,6) DEFAULT NULL,
  `longitue` float(10,6) DEFAULT NULL,
  `rating` varchar(6) DEFAULT NULL,
  `price` varchar(6) DEFAULT NULL,
  `full_address` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `restaurant`, `phone`, `fax`, `address`, `city_id`, `state_id`, `zipcode`, `opens`, `closes`, `delivery_radius`, `latitude`, `longitue`, `rating`, `price`, `full_address`) VALUES
(1, 'Sapporo', '2034449245', '+13474262330', '152 W 49th St', 1, 1, '10019', 800, 1300, 5, 40.760052, -73.983307, '3.0', '$$', '152 W 49th St, New York, NY 10019'),
(6, 'Sapporo', '2034449245', '+13474262330', '152 W 49th St', 1, 1, '10019', 800, 1300, 5, 40.760052, -73.983307, '3.0', '$$', '152 W 49th St, New York, NY 10019');

--
-- Triggers `restaurants`
--
DELIMITER $$
CREATE TRIGGER `restaurants_insert` AFTER INSERT ON `restaurants` FOR EACH ROW BEGIN
   INSERT INTO restaurants_keywords (restaurant_id, keyword_id)
      SELECT NEW.id, k.keyword_id FROM keywords k
      WHERE  NEW.phone REGEXP CONCAT('[[:<:]]',k.keyword, '[[:>:]]')
			OR NEW.fax REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]')
			OR NEW.address REGEXP CONCAT('[[:<:]]',k.keyword, '[[:>:]]')
			OR NEW.zipcode REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]')
			OR NEW.restaurant REGEXP CONCAT('[[:<:]]',k.keyword, '[[:>:]]')
			OR NEW.full_address REGEXP CONCAT('[[:<:]]',k.keyword, '[[:>:]]');         
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants_keywords`
--

CREATE TABLE `restaurants_keywords` (
  `keyword_id` bigint(20) NOT NULL DEFAULT '0',
  `restaurant_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurants_keywords`
--

INSERT INTO `restaurants_keywords` (`keyword_id`, `restaurant_id`) VALUES
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(25, 6),
(26, 6);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_categories`
--

CREATE TABLE `restaurant_categories` (
  `restaurant_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`) VALUES
(1, 'NY');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `facebook_id` varchar(250) DEFAULT NULL,
  `customer_id` varchar(250) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `token` varchar(250) DEFAULT NULL,
  `password` varchar(60) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_edited` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `facebook_id`, `customer_id`, `email`, `fname`, `lname`, `token`, `password`, `created_on`, `last_edited`, `status`) VALUES
(1, NULL, 'cus_750ApG3xHGfn0q', 'ebredy@gmail.com', 'Erwin', 'Bredy', '1441656429.$2a$08$EGzk.RYPU8BfwhwFazYhMe/xMuewv.MdDYThJcetiXUP4lqi3IGvG.55ec9ced70bed', '$2a$08$EGzk.RYPU8BfwhwFazYhMe/xMuewv.MdDYThJcetiXUP4lqi3IGvG', '2015-09-04 02:54:56', NULL, 0),
(2, NULL, NULL, 'test@mailinator.com', 'Erwin', 'Bredy', NULL, '$2a$08$QWfk7UYI88z1uR9GBr.0UevsmAjBXGwCNkWKGxwvkMSIJWwWNQKU.', '2015-09-04 02:58:34', NULL, 0),
(3, NULL, NULL, 'test2@mailinator.com', 'Erwin', 'Bredy', NULL, '$2a$08$fPXywot.wPwKItpBHUP5zes5Zy0WhhlntavRLdhGFDC9HgNEYTS9W', '2015-09-04 02:59:37', NULL, 0),
(4, NULL, NULL, 'testing1@mailinator.com', 'Erwin', 'Bredy', '1443881810.$2a$08$Y84QAqTYpQKW6Qj5l6YTa.K3dxqgMBZg592XiGa/U9vgfAR4E4eg6.560e91d256d70', '$2a$08$Y84QAqTYpQKW6Qj5l6YTa.K3dxqgMBZg592XiGa/U9vgfAR4E4eg6', '2015-10-02 14:16:09', NULL, 0),
(5, NULL, NULL, 'tester111@mailinator.com', 'Erwin', 'Bredy', NULL, '$2a$08$gWwKEKE.IKJw.wWcL2xZdOvSSx.0mhNLmgxipxdoWfiJ9PbMysSIm', '2015-10-02 14:33:32', NULL, 0);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `users_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
INSERT INTO users_keywords (user_id, keyword_id)
	         SELECT new.id, k.keyword_id FROM keywords k
	         WHERE NEW.email REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]')
	            OR NEW.fname REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]')
					OR NEW.lname REGEXP CONCAT('[[:<:]]', k.keyword, '[[:>:]]');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users_keywords`
--

CREATE TABLE `users_keywords` (
  `keyword_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `route` varchar(512) DEFAULT NULL,
  `route_var` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`keyword_id`),
  ADD UNIQUE KEY `keyword` (`keyword`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `menus_keywords`
--
ALTER TABLE `menus_keywords`
  ADD PRIMARY KEY (`keyword_id`,`menu_id`),
  ADD KEY `FK_menus_keywords_menus` (`menu_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`menu_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `menu_ratings`
--
ALTER TABLE `menu_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `ordernotifications`
--
ALTER TABLE `ordernotifications`
  ADD PRIMARY KEY (`orderNotification_ID`),
  ADD KEY `order_confirmation_number` (`order_confirmation_number`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_id` (`card_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `restaurants_keywords`
--
ALTER TABLE `restaurants_keywords`
  ADD PRIMARY KEY (`keyword_id`,`restaurant_id`),
  ADD KEY `restaurants_keywords_ibfk_2` (`restaurant_id`);

--
-- Indexes for table `restaurant_categories`
--
ALTER TABLE `restaurant_categories`
  ADD PRIMARY KEY (`restaurant_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `facebook_id` (`facebook_id`),
  ADD UNIQUE KEY `account_id` (`customer_id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `users_keywords`
--
ALTER TABLE `users_keywords`
  ADD PRIMARY KEY (`keyword_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `keyword_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `menu_ratings`
--
ALTER TABLE `menu_ratings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ordernotifications`
--
ALTER TABLE `ordernotifications`
  MODIFY `orderNotification_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `addresses_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `addresses_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus_keywords`
--
ALTER TABLE `menus_keywords`
  ADD CONSTRAINT `FK_menus_keywords_keywords` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`keyword_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_menus_keywords_menus` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD CONSTRAINT `menu_categories_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_ratings`
--
ALTER TABLE `menu_ratings`
  ADD CONSTRAINT `menu_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `menu_ratings_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `restaurants_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurants_keywords`
--
ALTER TABLE `restaurants_keywords`
  ADD CONSTRAINT `restaurants_keywords_ibfk_1` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`keyword_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `restaurants_keywords_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurant_categories`
--
ALTER TABLE `restaurant_categories`
  ADD CONSTRAINT `restaurant_categories_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `restaurant_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_keywords`
--
ALTER TABLE `users_keywords`
  ADD CONSTRAINT `users_keywords_ibfk_1` FOREIGN KEY (`keyword_id`) REFERENCES `Keywords` (`keyword_id`),
  ADD CONSTRAINT `users_keywords_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
