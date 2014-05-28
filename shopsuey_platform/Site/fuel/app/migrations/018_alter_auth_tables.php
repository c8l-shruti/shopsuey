<?php

namespace Fuel\Migrations;

class Alter_auth_tables
{
	public function up()
	{
		\DBUtil::rename_table('apps', 'applications');
		\DBUtil::drop_fields('applications', array('user_id'));
		\DBUtil::modify_fields('applications', array(
				'created' => array('name' =>'created_at', 'type' => 'int', 'constraint' => 11),
				'edited' => array('name' =>'updated_at', 'type' => 'int', 'constraint' => 11),
				'appid' => array('name' =>'token', 'type' => 'varchar', 'constraint' => 100),
				'secret' => array('type' => 'varchar', 'constraint' => 100),
		));
		\DBUtil::create_index('applications', 'token', 'applications_token_unique_index', 'unique');
		
		\DBUtil::rename_table('keys', 'user_logins');
		\DBUtil::modify_fields('user_logins', array(
				'created' => array('name' =>'created_at', 'type' => 'int', 'constraint' => 11),
				'key' => array('name' =>'login_hash', 'type' => 'varchar', 'constraint' => 50),
		));
		\DBUtil::drop_fields('user_logins', array('appid'));
		\DBUtil::add_fields('user_logins', array(
				'updated_at' => array('type' => 'int', 'constraint' => 11),
				'user_id' => array('type' => 'int', 'constraint' => 11),
				'application_id' => array('type' => 'int', 'constraint' => 11),
				'expiracy' => array('type' => 'datetime', 'null' => true),
		));

		\DBUtil::drop_fields('users', array('access_key', 'username', 'login_hash', 'profile_fields', 'last_login'));
		\DBUtil::create_index('users', 'email', 'users_email_unique_index', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_index('users', 'users_email_unique_index');
		\DBUtil::add_fields('users', array(
				'access_key' => array('type' => 'text'),
				'username' => array('type' => 'varchar', 'constraint' => 50),
				'login_hash' => array('type' => 'text'),
				'profile_fields' => array('type' => 'text'),
				'last_login' => array('type' => 'varchar', 'constraint' => 255),
		));
		
		\DBUtil::drop_fields('user_logins', array('updated_at', 'user_id', 'application_id', 'expiracy'));
		\DBUtil::add_fields('user_logins', array(
				'appid' => array('type' => 'text')
		));
		\DBUtil::modify_fields('user_logins', array(
				'created_at' => array('name' =>'created', 'type' => 'int', 'constraint' => 11),
				'login_hash' => array('name' =>'key', 'type' => 'varchar', 'constraint' => 50),
		));
		\DBUtil::rename_table('user_logins', 'keys');
		
		\DBUtil::drop_index('applications', 'applications_token_unique_index');
		\DBUtil::modify_fields('applications', array(
				'created_at' => array('name' =>'created', 'type' => 'int', 'constraint' => 11),
				'updated_at' => array('name' =>'edited', 'type' => 'int', 'constraint' => 11),
				'token' => array('name' =>'appid', 'type' => 'text'),
				'secret' => array('type' => 'text'),
		));
		\DBUtil::add_fields('applications', array(
				'user_id' => array('type' => 'int', 'constrain' => 11, 'default' => 0)
		));
		\DBUtil::rename_table('applications', 'apps');
	}
}