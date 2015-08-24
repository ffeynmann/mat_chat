<!DOCTYPE html>
<?php
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
	$chat = new CHAT( $conn);
	?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CHAT</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-fixed-top navbar-inverse">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="">Chat Online</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<?php
					if( $chat->check_logged()): 
					?>
						<h5 class="pull-right" style="color: #AAA;"><small>Logged as:</small><?php echo $_SESSION["name"];?> </h5>
						<button id="logout" type="submit" class="btn btn-danger pull-right" style="margin-top: 8px;margin-right: 4px;">LOGOUT</button>
					<?php else: ?>
						<form method="post" class="navbar-form navbar-right" id="login_form">
							<div class="form-group">
								<input type="text" placeholder="Login" name="name" class="form-control">
							</div>
							<div class="form-group">
								<input type="password" placeholder="Password"  name="password" class="form-control">
							</div>
							<button id="login" type="submit" class="btn btn-success">LOGIN</button>
						</form>
					<?php endif;?>
				</div>
			</div>
			
		</nav>
		<?php if( $chat->check_logged()): ?>
			<div class="row-fluid" style="margin: 65px 0;">
				<div id="messages" class="col-md-9" style="padding-bottom: 65px;"><?php echo $chat->get_messages(); ?></div>
				<div class="col-md-3 fixed" style="padding-bottom: 65px;">
					<div class="panel panel-info rty collapsed">
						<div class="panel-heading">
							Channels
							<?php if( $chat->check_admin()):?>
								<button id="addchannel" type="button" class="btn btn-info btn-xs pull-right">ADD</button>
							<?php endif;?>
						</div>
						<div class="panel-body">
							<div id="channels" class="list-group list-special"><?php echo $chat->get_channels(); ?></div>
						</div>
					</div>
					<div class="panel panel-success">
						<div class="panel-heading">Select User</div>
						<div class="panel-body">
							<div id="users" class="list-group list-special"><?php echo $chat->get_users(0); ?>	</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid navbar-fixed-bottom" style="padding: 10px 5px; background-color: rgba( 255, 255, 255, .85);">
				<div class="col-sm-12">
					<div class="input-group">
						<input id="message" type="text" class="form-control" placeholder="Type here...">
						<span class="input-group-btn">
							<button id="send" class="btn btn-success" type="button">Send</button>
						</span>
					</div>
				</div>
			</div>
		<?php endif;?>
		
		
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/validate.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/chat.js"></script>
	</body>
</html>
