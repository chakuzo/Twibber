var page = 1;
var nick_g = '', search_g = '', global_g = false, interval;
window.onhashchange = checkHash(true);
$(document).ready(function(){
	open();
	handleHash();
	sortComments();
	$('#more_twibbs').click(function(){
		checkHash();
		load_dips(global_g, nick_g, search_g);
	});
	$('.twibb').live('mouseover mouseout', function(event){
		var children = this.children[3];
		if(event.type == 'mouseover')
			$(children).show();
		if(event.type == 'mouseout')
			$(children).hide();
	});
	$('#logo > a > img').click(function(){
		dyn_get(true, true);
	});
	$('.comment_link').live('click', function(){
		var id = $(this).parents()[1].id;
		$('#to_id').val(id);
		$('#in_comment_to').html("in reply to: <a href='#' class='reply_to_link'>"+id+"</a> <a href='#' class='remove'>X</a>").show();
		$('#input_text').focus();
	});
	$('.reply_to_link').live('click', function(){
		var id = '#'+parseInt($(this).text());
		$(id).css('border', '5px solid yellow');
		setTimeout(function(){
			$(id).css('border')
		}, 5000);
	});
	$('.remove').live('click', function(){
		$(this.parentElement).html('');
		$('#to_id').val('');
	});
	$('#refresh').click(function(){
		if($(this).hasClass('stop')){
			window.clearInterval(interval);
			$(this).addClass('start').removeClass('stop').text('Aktuallisieren fortsetzen');
			return;
		}
		$(this).addClass('stop').removeClass('start').text('Aktuallisieren stoppen');
		handleHash();
	});
	$('#twibb_form').submit(function(){
		dyn_submit();
		return false;
	});
	$('.nickname').click(function(){
		var nick = $(this).attr('class').split(' ');
		insert_nick(nick[0]);
	})
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
		$.get('api.php', {
			dyn_get: 1,
			latest: latest,
			page: page
		}, function(ret){
			$('#twibber').html(ret);
			sortComments();
		});
		return false;
	}
	if(nick != ''){
		$.get('api.php', {
			nick: nick,
			latest: latest,
			page: page
		}, function(ret){
			$('#twibber').html(ret);
			sortComments();
		});
		return false;
	}
	if(search != ''){
		$.get('api.php', {
			search: search,
			latest: latest,
			page: page
		}, function(ret){
			$('#twibber').html(ret);
			sortComments();
		});
		return false;
	}
	return true;
}
function dyn_submit(){
	var text = $('#input_text').val();
	var url = 'api.php?new_entry=1';
	var to_id = $('#to_id').val();
	var options = {
		text: text
	};
	if(to_id != ''){
		url = 'api.php?new_entry=1&comment=1';
		options = {
			text: text,
			to_id: to_id
		}
	}
	if(text.replace(/^\s+|\s+$/g, '') != '' && text.length <= 250){
		$.post(url, options, function(ret){
			dyn_get(true, true);
			$('#status').freeow(ret, ret.replace('!','')+' gesendet!', {
				classes: ['smokey'],
				autoHideDelay: 2500
			});
		});
		reset();
	}else{
		alert('Fehler: Nachricht zu lang oder keine Nachricht vorhanden.');
	}
}
function insert_nick(nick){
	$('#input_text').focus();
	$('#input_text').val('@'+nick+' '+$('#input_text').val());
}

function open(){
	$('#open').click(function(){
		// For basically all browsers, which not using webkit
		$('#panel').slideDown('slow').delay(1).css('display', 'block');
	});
	$('#close').click(function(){
		$('#panel').slideUp('slow');
	});
	$('#toggle a').click(function () {
		$('#toggle a').toggle();
		$('#toggle a').toggleClass('slider_active');
	});
}

function load_dips(global, nick, search){
	page++;
	$('#status').freeow('Loading', 'Mehr Twibbs werden geladen!', {
		classes: ['smokey'],
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
			$('#status').freeow('Loading', 'Twibbs von '+hash[1]+' werden geladen', {
				classes: ['smokey'],
				autoHideDelay: 1500
			});
			dyn_get(true, false, hash[1]);
			break;
		case 'search':
			$('#status').freeow('Loading', 'Twibbs werden gesucht ('+hash[1]+')', {
				classes: ['smokey'],
				autoHideDelay: 1500
			});
			dyn_get(true, false, '', hash[1]);
			break;
		default:
			interval = window.setInterval('dyn_get(true, true)', 20000);
			break;
	}
}

function checkHash(handle){
	reset_vars();
	if(handle)
		handleHash();
	var hash = location.hash.replace('#', '');
	// @TODO multiple hash, first trail with ';' then '#' ..., support search for #hash tags
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

function sortComments(){
	$.each(
		$('#twibber').children(),
		function(index, value){
			var to_id = $(value).attr('to_id');
			if(to_id != ''){
				$('#'+to_id).append(value);
			}
		}
		);
}

function reset(){
	$('#input_text').val('');
	$('#counter').text('250 Zeichen');
	$('#in_comment_to').html('');
	$('#to_id').val('');
}