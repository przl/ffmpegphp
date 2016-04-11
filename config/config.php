<?php 

namespace ffmpegconverter\config;

/**
* Config class
*/
class Config
{
	public static $email_smtp;
	// public static $web_root = 'http://' . $_SERVER['SERVER_ADDR'] . '/ffmpeg/';
// 
	public static function get_web_path(){
		return 'http://' . $_SERVER['SERVER_ADDR'] . '/ffmpeg/';
	}

	// function __construct()
	// {
	// 	static::$email_smtp = 'test.email';
	// 	static::$web_root = 'http://' . $_SERVER['SERVER_ADDR'] . '/ffmpeg/';
	// }
}
