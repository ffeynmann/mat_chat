<?php
class DB{
	private static $instance;
	private $base;
	
	private function __construct( array $conn){
		$this->base = @ new mysqli( $conn["host"], $conn["user"], $conn["pass"], $conn["name"]);
		if( mysqli_connect_errno()){
			throw new Exception("DB error");
			}
		$this->base->set_charset("utf8");
		}
		
	public static function init( array $conn){
		if( self::$instance instanceof self){
			return false;
			}
		self::$instance = new self( $conn);
		}
		
	public static function esc( $str){
		return self::$instance->base->real_escape_string( htmlspecialchars( $str));
		}
		
	public static function query( $str){
		$result = self::$instance->base->query( $str);
		return $result;
		}
	
	public static function SQLiObject(){
		return self::$instance->base;
		}
	
	}
	
