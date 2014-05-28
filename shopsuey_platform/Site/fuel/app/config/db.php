<?php
/**
 * Base Database Config.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
	'active' => 'development',

	/**
	 * Base config, just need to set the DSN, username and password in env. config.
	 */

	'development' => array(
		'type'           => 'mysqli',
		'table_prefix'   => 'suey_',
		'charset'        => 'utf8',
		'enable_cache'   => true,
		'profiling'      => false,
	),
);