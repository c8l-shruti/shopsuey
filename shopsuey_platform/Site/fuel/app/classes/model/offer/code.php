<?php

class Model_Offer_Code extends \Orm\Model
{
	const QR_CODE_TYPE = 'qr_code';
	const EAN13_TYPE 	 = 'ean13';
	const CODE128_TYPE = 'code_128';

	protected static $_belongs_to = array('offer');

	protected static $_has_many = array('offer_redeems');

	protected static $_properties = array(
			'id',
			'type' => array(
					'validation' => array('required'),
			),
			'code' => array(
					'validation' => array('required'),
			),
			'offer_id',
			'auto_generated',
			'status',
			'created_at',
			'updated_at' => array('data_type' => 'int'),
	);

	protected static $_observers = array(
            'Orm\\Observer_Typing' => array(
                'events' => array('before_save', 'after_save', 'after_load')
            ),
			'Orm\Observer_CreatedAt' => array(
					'events' => array('before_insert'),
					'mysql_timestamp' => false,
			),
			'Orm\Observer_UpdatedAt' => array(
					'events' => array('before_save'),
					'mysql_timestamp' => false,
			),
			'Orm\Observer_Validation' => array(
					'events' => array('before_save'),
					'mysql_timestamp' => false,
			),
	);
	
	public static function get_random_code($type = self::EAN13_TYPE) {
		if ($type == self::EAN13_TYPE) {
			$charset = '0123456789';
			$length = 13;
		} elseif ($type == self::QR_CODE_TYPE) {
			$charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ $%*+-./:';
			$length = 20;
		} else {
			$charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$length = 20;
		}
		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$random_string .= $charset[rand(0, strlen($charset) - 1)];
		}
		return $random_string;
	}
	
	public function is_valid_code() {
		switch($this->type) {
			case self::EAN13_TYPE:
				return preg_match('/^\d{13}$/', $this->code);
				break;
			case self::QR_CODE_TYPE:
				return preg_match('/^[0-9A-Z\s\$\%\*\+\-\.\/\:]+$/', $this->code);
				break;
			case self::CODE128_TYPE:
				return preg_match('/^[[:ascii:]]+$/', $this->code);
				break;
			default:
				return FALSE;
		}
	} 
}
