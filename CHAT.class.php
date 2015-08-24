<?php
class CHAT extends CHAT_TPLS{
	private $is_admin;
	function __construct( $connection){
		DB::init( $connection);
				
		$users_db = "CREATE TABLE IF NOT EXISTS `users`(
			`ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(16) NOT NULL,
			`pass` varchar(32) NOT NULL,
			`last_activity` timestamp NOT NULL default CURRENT_TIMESTAMP,
			`admin` TINYINT(1) NOT NULL,
			PRIMARY KEY (`ID`),
			KEY `last_activity` (`last_activity`),
			UNIQUE KEY `name`(`name`)
			) DEFAULT CHARSET=utf8;";
		$channels_db = "CREATE TABLE IF NOT EXISTS `channels`(
			`ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(16) NOT NULL,
			PRIMARY KEY (`ID`)
			)";	
		$messages_db = "CREATE TABLE IF NOT EXISTS `messages`(
			`ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`sender_id` int(5) NOT NULL,
			`receiver_id` int(5) NOT NULL,
			`channel` int(5) NOT NULL,
			`text` varchar(32) NOT NULL,
			`ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
			PRIMARY KEY  (`ID`),
			KEY `ts` (`ts`)
			)";	
		//DB::query( $users_db);
		//DB::query( $channels_db);
		//DB::query( $messages_db);
		$this->is_admin = ( is_array( $_SESSION) && array_key_exists( "admin", $_SESSION) && $_SESSION["admin"] == 1) ? true : false;
		}
	public function login( $user){
		$query = "SELECT * FROM `users` where `name`='" . DB::esc( $user->name) . "' and `pass` = '" . DB::esc( $user->pass) . "';";
		$result = DB::query( $query)->fetch_array( MYSQLI_ASSOC);
		if( $result !== null){
			$_SESSION["ID"] = $result["ID"];
			$_SESSION["name"] = $result["name"];
			$_SESSION["admin"] = $result["admin"];
			DB::query( "UPDATE `users` SET last_activity = NOW() where `ID` = '" . $_SESSION["ID"] . "';");
			return true;
		}else{
			return false;
			}
		}
	public function check_logged(){
		if( !$_SESSION["ID"]) return false;
		return true;
		}
	public function check_admin(){
		if( $this->is_admin) return true;
		return false;
		}
	public function logout(){
		$_SESSION = array();
		unset( $_SESSION);
		}
	public function add_channel( $name){
		DB::query( "INSERT INTO `channels`(`name`) values('" . DB::esc( $name) . "')");
		}
	public function add_message( $message){
		$query = "INSERT INTO `messages`(`sender_id`, `receiver_id`, `channel`, `text`) VALUES(
			'" . $_SESSION["ID"] . "',
			'" . DB::esc( $message->user) . "',
			'" . DB::esc( $message->channel) . "',
			'" . DB::esc( $message->message) . "');";
		DB::query( $query);
		return true;
		}
	public function get_channels( $active){
		$r = "";
		if( $result = DB::query( "SELECT * FROM `channels`")){
			while( $row = $result->fetch_assoc()){ $r .= self::tpl_channel( $row, $active, $this->is_admin); }
			}
		return $r;
		}
	public function get_users( $active){
		$r .= self::tpl_user( array( "ID" => 0, "name" => "To All"), $active);
		if( $result = DB::query( "SELECT * FROM `users` where `last_activity` > SUBTIME(NOW(),'0:0:10') and `ID` != '" . $_SESSION["ID"] . "'")){
			while( $row = $result->fetch_assoc()){ $r .= self::tpl_user( $row, $active); }
			}
		return $r;
		}
	public function get_messages( $channel){
		if( $channel === null) return "";
		$r = "";		
		$query = "SELECT `messages`.`ID`, `messages`.`text`,`messages`.`ts`, 
			`users1`.`name` as `sender_name`,
			`users2`.`name` as `receiver_name`
			FROM `messages` 
			LEFT JOIN `users` `users1` ON `messages`.`sender_id` = `users1`.`ID`  
			LEFT JOIN `users` `users2` ON `messages`.`receiver_id` = `users2`.`ID`
			where 1=1 and `messages`.`channel` = '" . DB::esc( $channel) . "'";
		
		if( !$this->is_admin){
			$query .= " and (`sender_id` = '" . $_SESSION["ID"] . "'";
			$query .= " or `receiver_id` = '" . $_SESSION["ID"] . "'";
			$query .= " or `receiver_id` = '0')";
			}
		if( $result = DB::query( $query)){
			while( $row = $result->fetch_assoc()){ $r .= self::tpl_message( $row, $this->is_admin);}
			}
		
		return $r;
		}
	public function delete_message( $ID){
		DB::query( "DELETE FROM `messages` where `ID` = '" . DB::esc( $ID) . "'");
		return true;
		}
	public function delete_channel( $ID){
		DB::query( "DELETE FROM `channels` where `ID` = '" . DB::esc( $ID) . "'");
		DB::query( "DELETE FROM `messages` where `channel` = '" . DB::esc( $ID) . "'");
		return true;
		}
	public function update_activity(){
		DB::query( "UPDATE `users` SET `last_activity` = NOW() where `ID` = '" . $_SESSION["ID"] . "';");
		}
	}

