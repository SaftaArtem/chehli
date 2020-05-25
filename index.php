<?php

require  '_lantera/utils.php';
require 'Service\Main.php';
require 'Service\DB.php';
require 'Service\Parse.php';

$db = new \Service\DB('localhost', 'admin', 'chehli', '12qwaszx');
$connection = $db->getConnection();

$parser = new Service\Parse('test.html');
$categories = $parser->parse();

$main = new \Service\Main($connection);

// preprare to import all categories
$termSql = $main->getTermSql($categories);
//$connection->query($termSql);

$termRelationSql = $main->getTermRelationSql($categories);
$connection->query($termRelationSql);
d($termRelationSql);








