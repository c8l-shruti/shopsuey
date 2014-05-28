<?php

Package::load('aws');

use Fuel\Core\Config;
use Aws\S3\S3Client;

/**
 * Helper Class to connect with Amazon Web Services
 */
class Helper_Aws {
    
    public static function upload_image_to_S3($bucket, $destination_name, $path_to_file, $metadata = array(), $visibility = 'public-read') {
        // Instantiate the S3 client with your AWS credentials and desired AWS region
        $client = S3Client::factory(array(
            'key'    => Config::get('aws.access_key'),
            'secret' => Config::get('aws.secret_key'),
        ));
        
        $result = $client->putObject(array(
            'Bucket'     => $bucket,
            'Key'        => $destination_name,
            'SourceFile' => $path_to_file,
            'Metadata'   => $metadata,
            'ACL'        => $visibility
        ));
        
        if ($result) {
            return $client->getObjectUrl($bucket, $destination_name);
        }
    }
    
}
    