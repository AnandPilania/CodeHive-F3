ch.resultBar=(function(){
	var ele=$('#resultBar');
	$(window).on('frame:closed', function(){
		if(ele.find('iframe').length<=1){}
	});
	return {
		show: function(id, src, text) {
			($('body').attr('data-resultBar')==='hidden'||'null')?($('body').attr('data-resultBar','shown')):(!1);
			ele.find('li.active,.input.active,iframe.active').removeClass('active');
			if(ele.find('iframe[data-id="'+id+'"]').length<=0){
				ele.find('.top .upper').append('<li class="active" data-id="'+id+'" title="'+src+'"><h4>'+text+'</h4><span class="close fa fa-close"></span></li>');
				ele.find('.top .lower').append('<div data-id="'+id+'" class="input active"><input type="text" class="inputtext_disabled" disabled="disabled" value="'+text+'" /></div>');
				ele.find('.bottom .upper').append('<iframe data-id="'+id+'" class="active" src="'+src+'"></iframe>')
			}else{
				ele.find('.top .upper li[data-id="'+id+'"], .top .lower .input[data-id="'+id+'"], .bottom .upper iframe[data-id="'+id+'"]').addClass('active');
			}
		}
	};
}());