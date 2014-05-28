<?php

class Helper_Images_Specialevents_Logos extends Helper_Images_Base {

    protected static function _get_image_sizes() {
        return Config::get('cms.logo_images_sizes');
    }

    protected static function _get_images_path() {
        return Config::get('cms.event_images_path');
    }

}