$(document).ready(function(){
   interval = window.setInterval("dyn_get(true, true)", 20000);
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
	$.get("api.php?dyn_get=1",function(ret){ $("#twibber").html(ret); });
	return false;
    }
    if(nick != ''){
	$.get("api.php",{nick:nick},function(ret){ $("#twibber").html(ret); });
	return false;
    }
    if(search != ''){
	$.get("api.php",{search:search},function(ret){ $("#twibber").html(ret); });
	return false;
    }
    return true;
}
function dyn_submit(){
    var text = $("#input_text").val();
    if(text.replace(/^\s+|\s+$/g,"") != "" && text.length <= 250 && $("#nickname").val() != ""){
	$.post("api.php?new_entry=1",{text:text},function(ret){
	    $("#status").freeow(ret, ret+" gesendet!"); 
	    dyn_get(true, true); 
	});
	$("#input_text").val("");
    }else{
	alert("Error! Nachricht zu lang, keine Nachricht vorhanden oder kein Nickname eingegeben.");
    }
}
function insert_nick(nick){
    $("#input_text").focus();
    $("#input_text").val("@"+nick+" "+$("#input_text").val());
}