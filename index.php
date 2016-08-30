<?php

require 'vendor/autoload.php';


$config = new Genie\Config\Config('tests/config/configs');

$table = $config->get('app.session');
echo "<pre>";
echo $table->length;
