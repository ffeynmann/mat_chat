<?php
class CHAT_TPLS{
	protected static function tpl_channel( $ch, $active = "", $is_admin = false){
		$r = "";
		$r .= "<a href='#' class='list-group-item " . (($ch["ID"] == $active) ? "active" :"") . "' data-id='" . $ch["ID"] . "'>";
			$r .= $ch["name"];
			if( $is_admin === true) $r .= "<button type='button' class='delete_channel btn btn-xs btn-danger pull-right'>DELETE</button>";
		$r .= "</a>";				
		return $r;
		}
	protected static function tpl_user( $us, $active){
		$r = "";
		$r .= "<a href='#' class='list-group-item " . (($us["ID"] == $active) ? "active" :"") . "' data-id='" . $us["ID"] . "'>";
			$r .= $us["name"];
		$r .= "</a>";
		return $r;
		}
	protected function tpl_message( $mes, $is_admin = false){
		if( $mes["receiver_name"] === null) $mes["receiver_name"] = "To All";
		$r = "";
		$r .= "<div style='padding-right: 75px; position: relative; margin-bottom: 15px;'>";
				if( $is_admin) $r .= "<button type='button' data-id='" . $mes["ID"] . "' class='delete_message btn btn-danger btn-xs ' >Delete</button>";
				$r .= " <small>" . $mes["ts"] . " </small><strong>" . $mes["sender_name"] . "</strong> - <strong>" . $mes["receiver_name"] . " </strong>";
				$r .= "<span>" . $mes["text"] . "</span>";
				$r .= "</div>";
		return $r;
		}
	}
