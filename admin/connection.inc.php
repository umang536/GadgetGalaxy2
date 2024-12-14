<?php
session_start();
$con=mysqli_connect("localhost","root","","gadgetgalaxy");
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'/GadgetGalaxy2/');
define('SITE_PATH','http://localhost/GadgetGalaxy2/');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'media/product/');

define('PRODUCT_MULTIPLE_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
define('PRODUCT_MULTIPLE_IMAGE_SITE_PATH',SITE_PATH.'media/product/');

define('BANNER_SERVER_PATH',SERVER_PATH.'media/banner/');
define('BANNER_SITE_PATH',SITE_PATH.'media/banner/');

define('SHIPROCKET_TOKEN_EMAIL',SERVER_PATH.'ugajjar35@gmail.com');
define('SHIPROCKET_TOKEN_PASSWORD',SITE_PATH.'umang1');

define('STORE_PDF',SITE_PATH.'admin/pdfs');
?>