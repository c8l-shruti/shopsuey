<?php

class Helper_Images_Profilingchoices extends Helper_Images_Base {

    protected static function _get_image_sizes() {
        return Config::get('cms.profiling_choices_images_sizes');
    }

    protected static function _get_images_path() {
        return Config::get('cms.profiling_choices_images_path');
    }

    public static function prepare_image_for_S3($file, $params) {
        $file_contents = base64_decode($file['content']);
        $file_name_without_extension = uniqid();
        $file_name = $file_name_without_extension . '.' . strtolower($file['extension']);
        
        // Copy the original image
        File::create(static::_get_images_path(), $file_name, $file_contents, 'assets');
        
        // Get the path of the newly created image
        $original_image_path = Config::get('file.areas.assets.basedir') . '/' . static::_get_images_path() . "/$file_name";
        if (isset($file['field'])) {
            self::_crop_image($original_image_path, $params, 0);
        }
        
        // Create the different versions
        foreach(static::_get_image_sizes() as $size_name => $width) {
            Image::load($original_image_path)
            ->config('bgcolor', null)
            ->resize($width, $width, true, true)
            ->save_pa($size_name . '_', null, 'png');
        }
        
        return Config::get('file.areas.assets.basedir') . '/' . static::_get_images_path() . "/large_$file_name_without_extension.png";
    }
        
}