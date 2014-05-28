<?php

Autoloader::add_namespace('Rackspace', __DIR__.'/classes/');

Autoloader::add_classes(array(
	'CF_Authentication' => __DIR__.'/vendor/cloudfiles.php',
));
