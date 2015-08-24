

(function( $){
	
	var app = {}
	app.v = {}
	
	app.init = function(){
		
		$( document).on("click", "#addchannel", app.addchannel);
		$( document).on("click", "#send", app.send);
		
		$( document).on("click",".delete_message", function(){
			if( confirm("You sure to delete this message?")){
				app.ajax("delete_message", { "ID": $(this).attr("data-id")}, {});
				}
			});
		$( document).on("click",".delete_channel", function( e){
			var $this = $(this);
			e.stopPropagation();
			if( confirm("You sure to delete this channel?")){
				app.ajax("delete_channel", { "ID": $(this).parent("a").attr("data-id")}, {
					success: function( r){ $this.parent("a").remove(); }
					});
				}
			});
		
		$( document).on("click", ".list-group a", function( e){
			$(this).siblings().removeClass("active");
			$(this).addClass("active");
			e.preventDefault();
			app.update();
			});
		$("form#login_form").validate({
			rules: {
				name: { required: true},
				password: { required: true},
				},
			 errorPlacement: function(error, element) {
				alert( "Field: " + $( element).attr("placeholder") + "\n" + $(error).html());
				}
			});
		$( document).on("click", "#login", function( e){ e.preventDefault(); app.login();});
		$( document).on("click", "#logout", function( e){ e.preventDefault(); app.ajax("logout", {}, {success:function(){location.reload();}});});
		
		if( $("#send").size() > 0 ){
			setInterval( app.update, 1500);
			setInterval( function(){ app.ajax("update_activity", {}, {});}, 2500);
			}
		}
	app.login = function(){
		if( $("form#login_form").valid()){
			app.v.user = {}
			app.v.user.name = $("form#login_form input[name=name]").val();
			app.v.user.pass = $("form#login_form input[name=password]").val();
			
			app.ajax("login", { "user" : app.v.user}, {
				success: function( r){
					if(r == true){
						location.reload();
					}else{
						alert("Wrong name or password.");
						}
					}
				});
			}
		}
	app.send = function(){
		app.v.send = {}
		app.v.send.message = $("#message").val();
		app.v.send.user = $("#users .active").attr("data-id");
		app.v.send.channel = $("#channels .active").attr("data-id");
		
		if( app.v.send. message == ""){ alert("Enter something.."); return false;}
		if( typeof app.v.send.user == "undefined"){ app.v.send.user = "0";}
		if( typeof app.v.send.channel == "undefined") { alert("Channel is not selected"); return false;}
		
		app.ajax( "add_message", {"message": app.v.send}, {success: function( r){
			$("#message").val("");
			}});
		}
	app.addchannel = function(){
		var name = prompt("Enter new channel name:");
		if( name != "" && name !== null){ app.ajax("add_channel", name, {}); }
		}
	app.update = function(){	
		if( $("#channels .active").size() == 0) $("#channels > a").addClass("active");
			
		app.v.active = {}
		app.v.active.channel = $("#channels .active").attr("data-id");
		app.v.active.user = $("#users .active").attr("data-id");
	
		app.ajax( "update", app.v.active, {
			success: function( r){
				$("#channels").html( r.channels);
				$("#users").html( r.users);
				$("#messages").html( r.messages);
				}
			});
		}
	
	app.ajax = function(action, data, config){
		jQuery.ajax({
			url: "ajax.php", type: "POST",
			data: { "action" : action, "json": JSON.stringify(data)},
			success: function( r){
				var parsed = JSON.parse(r);
				if( parsed.error) alert( parsed.error);
				if( config.success) config.success( parsed); }
			});
		}
	
	$( document ).on( "ready", function(){
		app.init();
		});
	
	})( jQuery);
