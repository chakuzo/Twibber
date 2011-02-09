var page = 1;
var hashbefore = window.location.hash;
$(document).ready(function(){
	open();
	$("a").live("click", function(){
		if(location.hash != hashbefore){
			hashbefore = location.hash;
			var hash = location.hash.replace('#', '');
			// @TODO multiple hash, first trail with ";" then "#" ...
			hash = hash.split('=');
			switch(hash[0]){
				case 'nick':
					dyn_get(true, false, hash[1]);
					break;
				case 'search':
					dyn_get(true, false, '', hash[1]);
					break;
				default:
					interval = window.setInterval("dyn_get(true, true)", 20000);
					break;
			}
		}
	});
});

$('#input_text').NobleCount('#counter',{
	on_negative: function(t_obj, char_area, c_settings, char_rem){
		char_area.css('color', 'red');
	},
	on_positive: function(t_obj, char_area, c_settings, char_rem){
		char_area.css('color', 'green');
	},
	max_chars: 250,
	on_update: function(t_obj, char_area, c_settings, char_rem){
		if (char_rem <= 10) {
			char_area.css('color', 'red');
		}
	}
});
function dyn_get(latest, global, nick, search){
	if(global){
		$.get("api.php?dyn_get=1&latest="+latest+"&page="+page,function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	if(nick != ''){
		$.get("api.php?latest="+latest+"&page="+page,{
			nick:nick
		},function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	if(search != ''){
		$.get("api.php?latest="+latest+"&page="+page,{
			search:search
		},function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	return true;
}
function dyn_submit(){
	var text = $("#input_text").val();
	if(text.replace(/^\s+|\s+$/g,"") != "" && text.length <= 250 && $("#nickname").val() != ""){
		$.post("api.php?new_entry=1",{
			text:text
		},function(ret){
			dyn_get(true, true);
			$("#status").freeow(ret, ret.replace(/!/,'')+" gesendet!", {
				classes: ["smokey"],
				autoHideDelay: 2500
			});
		});
		$("#input_text").val("");
		$("#counter").text("250");
	}else{
		alert("Error! Nachricht zu lang, keine Nachricht vorhanden oder kein Nickname eingegeben.");
	}
}
function insert_nick(nick){
	$("#input_text").focus();
	$("#input_text").val("@"+nick+" "+$("#input_text").val());
}

function open(){
	$("#open").click(function(){
		// For basically all browsers, which not using webkit
		$("#panel").slideDown("slow").delay(1).css("display","block");
	});
	$("#close").click(function(){
		$("#panel").slideUp("slow");
	});
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});
}

function load_dips(global, nick, search){
	page++;
	$("#status").freeow("Loading", "Neue Twibbs werden geladen!", {
		classes: ["smokey"],
		autoHideDelay: 2500
	});
	dyn_get(false, global, nick, search);
}