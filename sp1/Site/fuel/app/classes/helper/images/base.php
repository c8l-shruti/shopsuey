<?php

class Helper_Images_Base {
    
    public static function copy_one_image_from_params($file, $params = array()) {
        $file_contents = base64_decode($file['content']);
        $file_name = uniqid() . '.' . strtolower($file['extension']);
        
        // Copy the original image
        File::create(static::_get_images_path(), $file_name, $file_contents, 'assets');
        // Get the path of the newly created image
        $original_image_path = Config::get('file.areas.assets.basedir') . '/' . static::_get_images_path() . "/$file_name";
        // Check if the image must be cropped
        if (isset($file['field'])) {
            self::_crop_image($original_image_path, $params, $file['field']);
        }
        // Create the different versions
        foreach(static::_get_image_sizes() as $size_name => $width) {
            Image::load($original_image_path)
            ->resize($width)
            ->save_pa($size_name . '_');
        }
        return $file_name;
	}
    
	public static function copy_images_from_params($params) {
		$copied = array();
		if (isset($params['gallery_add']) && is_array($params['gallery_add'])) {
			foreach($params['gallery_add'] as $index => $file) {
				$file_contents = base64_decode($file['content']);
				$file_name = uniqid() . '.' . strtolower($file['extension']);
				// Copy the original image
				File::create(static::_get_images_path(), $file_name, $file_contents, 'assets');
				// Get the path of the newly created image
				$original_image_path = Config::get('file.areas.assets.basedir') . '/' . static::_get_images_path() . "/$file_name";
				// Check if the image must be cropped
				self::_crop_image($original_image_path, $params, $index);
				// Create the different versions
				foreach(static::_get_image_sizes() as $size_name => $width) {
					Image::load($original_image_path)
					->resize($width)
					->save_pa($size_name . '_');
				}
				$copied[] = $file_name;
			}
		}
		return $copied;
	}
    
    // $destination_helper is a string representing the name of the helper class
    // where the image is to be copied to (for example Helper_Images_Events)
    public static function copy_image($image, $destination_helper) {
        $image_file = File::get(static::_get_images_path() . "/$image", array(), 'assets');
        $image_name_parts = explode('.', $image);
        $file = array(
            'content' => base64_encode($image_file->read(true)),
            'extension' => strtolower(array_pop($image_name_parts))
        );
        return $destination_helper::copy_one_image_from_params($file);
    }
	
	public static function delete_images($images) {
		foreach($images as $file_to_remove) {
			File::delete(static::_get_images_path() . "/$file_to_remove", 'assets');
			foreach(static::_get_image_sizes() as $size_name => $width) {
				File::delete(static::_get_images_path() . "/{$size_name}_{$file_to_remove}", 'assets');
			}
		}
	}
	
	public static function get_gallery_urls($gallery) {
            if (!$gallery) {
                return array();
            }
        
            // Build the urls for the images
            $gallery_urls = array();
            
            foreach($gallery as $image) {
                
                if(filter_var($image, FILTER_VALIDATE_URL)){ 
                    
                    $gallery_urls[] = array("small" => $image, "original" => $image, "large" => $image);
                    
                }else{
                
                    $path = Asset::get_file($image, 'img', static::_get_images_path());

                    if (!$path) {
                        continue;
                    }

                    $image_urls = array(
                        'original' => $path,
                    );

                    foreach(static::_get_image_sizes() as $size_name => $width) {
                        $path = Asset::get_file("{$size_name}_{$image}", 'img', static::_get_images_path());
                        $image_urls[$size_name] = $path ? $path : null;
                    }

                    $gallery_urls[] = $image_urls;

                }
            }
            
            return $gallery_urls;
            
	}
    
    public static function copy_image_from_path($path_to_image) {
        $file_contents = file_get_contents($path_to_image);
        $file_info     = Fuel\Core\File::file_info($path_to_image);
        $file_name     = uniqid() . '.' . strtolower($file_info['extension']);

        // Copy the original image
        File::create(static::_get_images_path(), $file_name, $file_contents, 'assets');
        // Get the path of the newly created image
        $original_image_path = Config::get('file.areas.assets.basedir') . '/' . static::_get_images_path() . "/$file_name";
        
        // Create the different versions
        foreach(static::_get_image_sizes() as $size_name => $width) {
            Image::load($original_image_path)
            ->resize($width)
            ->save_pa($size_name . '_');
        }
        return $file_name;
    }
    
    protected static function _get_images_path() {
        throw new BadMethodCallException('The get_images_path method should be redefined in the child class');
    }
    
    protected static function _get_image_sizes() {
        throw new BadMethodCallException('The get_image_sizes method should be redefined in the child class');
    }
    
    protected static function _crop_image($path, $params, $index) {
        if (isset($params["x1_$index"]) && isset($params["y1_$index"])
            && isset($params["x2_$index"]) && isset($params["y2_$index"])
            && $params["x2_$index"] > 0 && $params["y2_$index"] > 0
            && isset($params["preview_width_$index"]) && isset($params["preview_height_$index"])) {

            // Crop the image and overwrite the original file
            $image = Image::load($path);
            
            $sizes = $image->sizes();
            
            $x1 = $params["x1_$index"];
            $y1 = $params["y1_$index"];
            $x2 = $params["x2_$index"];
            $y2 = $params["y2_$index"];
            $preview_width = $params["preview_width_$index"];
            $preview_height = $params["preview_height_$index"];
            
            $x_ratio = $sizes->width / $preview_width;
            $y_ratio = $sizes->height / $preview_height;
            
            $image->crop(round($x1 * $x_ratio), round($y1 * $y_ratio), round($x2 * $x_ratio), round($y2 * $y_ratio))
                ->save($path);
        }
    }
}
