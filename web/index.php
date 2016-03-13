<?php

define( 'DS', DIRECTORY_SEPARATOR );
define( 'BASE_PATH', realpath( dirname( __DIR__ ) ) . DS );
define( 'ROOT_PATH', BASE_PATH . 'web' . DS );
define( 'VENDOR_PATH', BASE_PATH . 'vendor' . DS );
define( 'CONFIG_PATH', BASE_PATH . 'config' . DS );
define( 'CHARSET', 'UTF-8' );
define( 'TIMEZONE', 'America/New_York' );

require_once BASE_PATH . 'tinypress' . DS . 'bootstrap.php';