$(document).ready(function(){
   $("#input_text").keyup(function(){count_char();}); 
});

function count_char(){
    var charlen = $("#input_text").val();
    //if(charlen.length != "150"){ charlen.length = "Twitter"; }
    $("label[for='input_text']").text(charlen.length+" Zeichen");
}
function dyn_get(latest, global, nick){
    // @TODO Replace old #twibber content with new.
}
function dyn_submit(){
    var text = $("#input_text").val();
    $.post("api.php?new_entry=1",{text:text});
    dyn_get(true, true);
}