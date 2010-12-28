$(document).ready(function(){
   $("#input_text").keyup(function(){count_char();}); 
});

function count_char(){
    var charlen = $("#input_text").val();
    //if(charlen.length != "150"){ charlen.length = "Twitter"; }
    $("label[for='input_text']").text(charlen.length+" Zeichen");
}