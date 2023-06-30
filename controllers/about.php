<?php
require('../vendor/autoload.php');

use eftec\bladeone\BladeOne;

$views = '../views';
$cache = '../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

echo $blade->run("about", []);