var page = 1;
var hashbefore = window.location.hash;
var nick_g = '', search_g = '', global_g = false;
$(document).ready(function(){
	open();
	checkHash();
	$("a").live("click", function(){
		checkHash(true);
	});
	$("#more_twibbs").click(function(){
		reset_vars();
		checkHash();
		load_dips(global_g, nick_g, search_g);
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
		$.get("api.php", {
			dyn_get: 1,
			latest: latest,
			page: page
		}, function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	if(nick != ''){
		$.get("api.php", {
			nick: nick,
			latest: latest,
			page: page
		}, function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	if(search != ''){
		$.get("api.php", {
			search: search,
			latest: latest,
			page: page
		}, function(ret){
			$("#twibber").html(ret);
		});
		return false;
	}
	return true;
}
function dyn_submit(){
	var text = $("#input_text").val();
	if(text.replace(/^\s+|\s+$/g,"") != "" && text.length <= 250 && $("#nickname").val() != ""){
		$.post("api.php",{
			text: text,
			new_entry: 1
		},function(ret){
			dyn_get(true, true);
			$("#status").freeow(ret, ret.replace('!','')+" gesendet!", {
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

function checkHash(action){
	if(location.hash != hashbefore)
		hashbefore = location.hash;
	var hash = location.hash.replace(/#/g, '');
	// @TODO multiple hash, first trail with ";" then "#" ...
	hash = hash.split('=');
	switch(hash[0]){
		case 'nick':
			if(action)
				dyn_get(true, false, hash[1]);
			nick_g = hash[1];
			break;
		case 'search':
			if(action)
				dyn_get(true, false, '', hash[1]);
			search_g = hash[1];
			break;
		default:
			if(action)
				interval = window.setInterval("dyn_get(true, true)", 20000);
			global_g = true;
			break;
	}
}

function reset_vars(){
	search_g = '';
	global_g = false;
	nick_g = '';
}