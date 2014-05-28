<?php

/**
 * Geo Library
 *
 * Functions related to the calculation of distances between coordinates.
 * Most of the stuff here is taken from http://www.scribd.com/doc/2569355/Geo-Distance-Search-with-MySQL 
 *
 * @package app
 * @extends Controller
 */

class Geo {

	const MILES_PER_DEGREE_OF_LATITUDE = 69;
	const EARTH_RADIUS = 3956;

	public static function calculate_distance($point_1, $point_2) {
		$R = self::EARTH_RADIUS;
		$d_lat = deg2rad($point_2->latitude - $point_1->latitude);
		$d_lon = deg2rad($point_2->longitude - $point_1->longitude);
		$lat_1 = deg2rad($point_1->latitude);
		$lat_2 = deg2rad($point_2->latitude);
	
		$a = pow(sin($d_lat / 2), 2) + pow(sin($d_lon / 2), 2) * cos($lat_1) * cos($lat_2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return $R * $c;
	}
	
	// Given a coordinates and a distance, calculates the coordinates of the rectangle formed by
	// moving the origin point to each direction
	public static function get_rectangle_coordinates($center, $distance) {
		$lon1 = $center->longitude - $distance / abs(cos(deg2rad($center->latitude)) * self::MILES_PER_DEGREE_OF_LATITUDE);
		$lon2 = $center->longitude + $distance / abs(cos(deg2rad($center->latitude)) * self::MILES_PER_DEGREE_OF_LATITUDE);
		$lat1 = $center->latitude - ($distance / self::MILES_PER_DEGREE_OF_LATITUDE);
		$lat2 = $center->latitude + ($distance / self::MILES_PER_DEGREE_OF_LATITUDE);
		$upper_left_point = self::build_coordinates($lat1, $lon1);
		$lower_right_point = self::build_coordinates($lat2, $lon2);
		return array($upper_left_point, $lower_right_point);
	}

	public static function build_coordinates($latitude, $longitude) {
		$coordinates = new stdClass();
		$coordinates->latitude = $latitude;
		$coordinates->longitude = $longitude;
		return $coordinates;
	}
}