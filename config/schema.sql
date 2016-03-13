create table users(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  facebook_id varchar(250) UNIQUE,
  customer_id varchar(250) UNIQUE,
  email varchar(100) UNIQUE NOT NULL,
  fname varchar(100) NOT NULL,
  lname varchar(100) NOT NULL,
  token varchar(250) UNIQUE,
  password varchar(60) NOT NULL,
  created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edited DATETIME DEFAULT NULL,
  status int(1) NOT NULL DEFAULT 0
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table cities(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  name varchar(100) UNIQUE NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table states(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  name varchar(100) UNIQUE NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table addresses(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  fname varchar(25) NOT NULL,
  lname varchar(25) NOT NULL,
  address_1 varchar(250) NOT NULL,
  address_2 varchar(250),
  apt_number varchar(25),
  phone varchar(20) NOT NULL,
  instructions varchar(250),
  city_id bigint(20),
  state_id bigint(20),
  zip_code varchar(10) NOT NULL,
  last_used DATETIME DEFAULT NULL,
  created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_edited DATETIME DEFAULT NULL,
  FOREIGN KEY ( city_id ) REFERENCES cities( id ) ON DELETE SET NULL,
  FOREIGN KEY ( state_id ) REFERENCES states( id ) ON DELETE SET NULL,
  FOREIGN KEY ( user_id ) REFERENCES users( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table restaurants(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  restaurant varchar(100) NOT NULL,
  phone varchar(100) NOT NULL,
  address varchar(100) NOT NULL,
  city_id bigint(20),
  state_id bigint(20),
  zipcode varchar(100) NOT NULL,
  opens bigint(20) NOT NULL,
  closes bigint(20) NOT NULL,
  delivery_radius int(1) NOT NULL DEFAULT 0,
  latitude float(10,6),
  longitue float(10,6),
  rating varchar(6),
  pricing varchar(6),
  FOREIGN KEY ( city_id ) REFERENCES cities( id ) ON DELETE SET NULL,
  FOREIGN KEY ( state_id ) REFERENCES states( id ) ON DELETE SET NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table categories(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  name varchar(100) UNIQUE NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table menus(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  restaurant_id bigint(20) NOT NULL,
  source varchar(250),
  image varchar(250) NOT NULL DEFAULT '/img/food/default.jpg',
  item varchar(100) NOT NULL,
  description longtext,
  price varchar(6),
  FOREIGN KEY ( restaurant_id ) REFERENCES restaurants( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table restaurant_categories(
  restaurant_id bigint(20) NOT NULL,
  category_id bigint(20) NOT NULL,
  PRIMARY KEY ( restaurant_id, category_id ),
  FOREIGN KEY ( restaurant_id ) REFERENCES restaurants( id ) ON DELETE CASCADE,
  FOREIGN KEY ( category_id ) REFERENCES categories( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table menu_categories(
  menu_id bigint(20) NOT NULL,
  category_id bigint(20) NOT NULL,
  PRIMARY KEY ( menu_id, category_id ),
  FOREIGN KEY ( menu_id ) REFERENCES menus( id ) ON DELETE CASCADE,
  FOREIGN KEY ( category_id ) REFERENCES categories( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table menu_ratings(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  user_id bigint(20),
  menu_id bigint(20) NOT NULL,
  rating varchar(100) NOT NULL,
  FOREIGN KEY ( user_id ) REFERENCES users( id ) ON DELETE SET NULL,
  FOREIGN KEY ( menu_id ) REFERENCES menus( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table cards(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  source_id VARCHAR(250) NOT NULL,
  user_id bigint(20) NOT NULL,
  brand VARCHAR(20) NOT NULL,
  last_4 int(4) NOT NULL,
  funding VARCHAR(10) NOT NULL DEFAULT 'credit',
  country_code VARCHAR(2) NOT NULL DEFAULT 'us',
  exp_month int(2) NOT NULL,
  exp_year int(4) NOT NULL,
  holder_name VARCHAR(250),
  address_line1 VARCHAR(250),
  address_line2 VARCHAR(250),
  address_city VARCHAR(250),
  address_state VARCHAR(250),
  address_zip VARCHAR(250),
  address_country VARCHAR(250),
  last_used DATETIME DEFAULT NULL,
  FOREIGN KEY ( user_id ) REFERENCES users( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table orders(
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  source_id VARCHAR(250) NOT NULL,
  receipt_number VARCHAR(250) NOT NULL,
  is_charged int(1) NOT NULL DEFAULT 0,
  user_id bigint(20) NOT NULL,
  card_id bigint(20),
  amount bigint(20) NOT NULL,
  refund bigint(20) NOT NULL DEFAULT 0,
  restaurant_id bigint(20),
  created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  refund_on DATETIME DEFAULT NULL,
  details longtext NOT NULL,
  FOREIGN KEY ( card_id ) REFERENCES cards( id ) ON DELETE SET NULL,
  FOREIGN KEY ( restaurant_id ) REFERENCES restaurants( id ) ON DELETE SET NULL,
  FOREIGN KEY ( user_id ) REFERENCES users( id ) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;