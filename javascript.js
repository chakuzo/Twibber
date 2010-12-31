$(document).ready(function(){
   $("#input_text").keyup(function(){count_char();}); 
   interval = window.setInterval("dyn_get(true, true)", 20000);
});

function count_char(){
    var charlen = $("#input_text").val();
    //if(charlen.length != "150"){ charlen.length = "Twitter"; }
    $("label[for='input_text']").text(charlen.length+" Zeichen");
}
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
	$.post("api.php?new_entry=1",{text:text},function(ret){ $("#status").fadeIn().text(ret); dyn_get(true, true); });
	$("#input_text").val("");
    }else{
	alert("Error! Nachricht zu lang, keine Nachricht vorhanden oder kein Nickname eingegeben.");
    }
}
function insert_nick(nick){
    $("#input_text").val("@"+nick+" "+$("#input_text").val());
    $("#input_text").focus();
}