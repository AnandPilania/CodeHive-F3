ch.chatBar=(function(){
	var ele=$('#chatBar'),placeholder='Write your message here...';
	ele.find('textarea').val(placeholder)
	ele.find('textarea').on('click', function(_z){
		_z.preventDefault();
		if($(this).val() === placeholder){
			$(this).val('');
		}
	}).on('blur', function(_z){
		_z.preventDefault();
		if($(this).val() === '') {
			$(this).val(placeholder);
		}
	});

	$('html').on('click', '.chatbox .header', function(_z){
		_z.preventDefault();
		ch.chatBar.minmax($(this).closest('.chatbox').attr('data-id'));
		$(window).trigger('chat:refresh');
	});
	$('html').on('click', '.minimizedChatbox .count', function(_z){
		_z.preventDefault();$(this).closest('.minimizedChatbox').toggleClass('active');
	});

	$(window).on('chat:refresh', function(_z){
		var cWidth=ele.outerWidth(),cbhWidth=ele.find('.chatboxholder').outerWidth()+150,cb=ele.find('.chatbox');
		if(cWidth<=cbhWidth){
			var cbMin=ele.find('.chatbox.min'),loop=(cbMin.length<=0?cb:cbMin);
			for(var i=1;i<=loop.length-1;i++){
				var _this=loop.eq(i);
				if(_this.hasClass('active')){
					continue;
				}
				if($('.minimizedChatbox').length<=0){
					$('.chatboxholder').prepend('<li class="minimizedChatbox chatbox"><div class="count">(0)</div><div class="inner"></div></li>');
				}

				$('.minimizedChatbox .inner').append('<div data-id="'+_this.attr('data-id')+'"><h4 class="ellipsis">'+_this.find('.header .name').text()+'</h4><span class="fa fa-close"></span></div>');
				_this.remove();
				break;
				$(window).trigger('chat:refresh');
			}
		}
		$('.minimizedChatbox .count').text($('.minimizedChatbox .inner div').length);
	});
	return {
		init: function(id){},
		send: function(id){},
		minmax: function(id){$('.chatbox.active').removeClass('active');(cb=$('.chatbox[data-id="'+id+'"]'),cb.toggleClass('min').toggleClass('active'));},
		minimize: function(id){$('.chatbox[data-id="'+id+'"]').removeClass('active').toggleClass('min');},
		maximize: function(id){$('.chatbox.active').removeClass('active');$('.chatbox[data-id="'+id+'"]').addClass('active').toggleClass('min');}
	};
}());