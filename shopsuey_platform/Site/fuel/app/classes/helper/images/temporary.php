<?php

class Helper_Images_Temporary extends Helper_Images_Base {

    protected static function _get_image_sizes() {
        return Config::get('cms.temporary_images_sizes');
    }

    protected static function _get_images_path() {
        return Config::get('cms.temporary_images_path');
    }

}