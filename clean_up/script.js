var page = 1;
var hash_before = window.location.hash;
var nick_g = '', search_g = '', global_g = false, interval;
$(document).ready(function(){
	open();
	handleHash();
	interval2 = window.setInterval("checkHash(true)", 100);
	$("#more_twibbs").click(function(){
		checkHash();
		load_dips(global_g, nick_g, search_g);
	});
	$(".twibb").live("mouseover mouseout", function(event){
		if(event.type == 'mouseover')
			$(this.children[3]).show();
		if(event.type == 'mouseout')
			$(this.children[3]).hide();
	});
	$("#logo > a > img").click(function(){
		dyn_get(true, true);
	});
	$(".comment_link").live("click", function(){
		$("#in_comment_to").text("in reply to: <a href='#' class='reply_to_link'>"+$(this).parents()[1].id+"</a>");
	});
	$(".reply_to_link").live("click", function(){
		$("#"+$(this).text()).css('border', '5px solid yellow').delay(2000).css('border', '0');
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
function dyn_submit(to_id){
	var text = $("#input_text").val();
	var url = "api.php?new_entry=1";
	var options = {
		text: text
	};
	if(to_id != ''){
		url = "api.php?new_entry=1&comment=1";
		options = {
			text: text,
			to_id: to_id
		}
	}
	if(text.replace(/^\s+|\s+$/g,"") != "" && text.length <= 250 && $("#nickname").val() != ""){
		$.post(url, options,function(ret){
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
	$("#status").freeow("Loading", "Mehr Twibbs werden geladen!", {
		classes: ["smokey"],
		autoHideDelay: 2500
	});
	dyn_get(false, global, nick, search);
}

function handleHash(){
	if(interval != undefined)
		window.clearInterval(interval);
	var hash = checkHash();
	switch(hash[0]){
		case 'nick':
			$("#status").freeow("Loading", "Twibbs von "+hash[1]+" werden geladen", {
				classes: ["smokey"],
				autoHideDelay: 1500
			});
			dyn_get(true, false, hash[1]);
			break;
		case 'search':
			$("#status").freeow("Loading", "Twibbs werden gesucht ("+hash[1]+")", {
				classes: ["smokey"],
				autoHideDelay: 1500
			});
			dyn_get(true, false, '', hash[1]);
			break;
		default:
			interval = window.setInterval("dyn_get(true, true)", 20000);
			break;
	}
}

function checkHash(handle){
	reset_vars();
	if(location.hash != hash_before){
		hash_before = location.hash;
		if(handle)
			handleHash();
	}
	var hash = location.hash.replace('#', '');
	// @TODO multiple hash, first trail with ";" then "#" ..., support search for #hash tags
	hash = hash.split('=');
	switch(hash[0]){
		case 'nick':
			nick_g = hash[1];
			break;
		case 'search':
			search_g = hash[1];
			break;
		default:
			global_g = true;
			break;
	}
	return hash;
}

function reset_vars(){
	search_g = '';
	global_g = false;
	nick_g = '';
}