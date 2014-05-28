<?php

Autoloader::add_namespace('Yelp', __DIR__.'/classes/');

Autoloader::add_classes(array(
    'OAuthToken' => __DIR__.'/vendor/OAuth.php',
));
