<?php
$response = array();
$json = json_decode( stripslashes( $_POST["json"]));

$conn = array(
	"host" => "localhost",
	"user" => "mat_chat1",
	"pass" => "87654321",
	"name" => "mat_chat"
	);
require "DB.class.php";
require "CHAT_TPLS.class.php";
require "CHAT.class.php";	
	
session_name("mat_chat");
session_start();

try{
	$chat = new CHAT( $conn);
	if( !$chat->check_logged()){
		if( $_POST["action"] != "login") throw new Exception("You are not logged in!");
		}
	if( !$chat->check_admin()){
		if( in_array( $_POST["action"], array( "delete_message", "delete_channel", "add_channel"))) throw new Exception("User can't use administrator functions!");
		}
	
	switch( stripslashes( $_POST["action"])){
		case "add_message":
			$chat->add_message( $json->message);
			break;
		case "add_channel":
			$chat->add_channel( $json);
			break;
		case "login":
			$response = $chat->login( $json->user);
			break;
		case "logout":
			$chat->logout();
			break;
		case "delete_message":
			$chat->delete_message( $json->ID);
			break;
		case "delete_channel":
			$chat->delete_channel( $json->ID);
			break;
		case "update_activity":
			$chat->update_activity();
			break;
		case "update":
			$response["users"] = $chat->get_users( $json->user);
			$response["channels"] = $chat->get_channels( $json->channel);
			$response["messages"] = $chat->get_messages( $json->channel);
			break;
		default: throw new Exception("Action Error");
		}
	echo json_encode( $response);
	}
catch( Exception $e){
	die( json_encode( array( "error" => $e->getMessage())));
	}
