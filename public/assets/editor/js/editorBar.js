ch.editorBar=(function(){
	var ele=$('#editorBar');
	var private={
		encoding: function(){},
		mode: function(){},
		ext: function(){}
	};

	$('html').on('click', '#fileproperties [data-mode]', function(_z){_z.preventDefault();var t=$(this),dm=t.attr('data-mode');$('#filemodeselected').html(dm);$('.editorurl.active').attr('data-mode',dm);t.parent('li').addClass('active');});
	$('html').on('click', '#fileproperties [data-encoding]', function(_z){_z.preventDefault();var t=$(this),dm=t.attr('data-encoding');$('#fileencodingselected').html(dm);$('.editorurl.active').attr('data-encoding',dm);t.parent('li').addClass('active');});

	$('html').on('click', '[data-id="find"],[data-id="replace"]', function(_z){_z.preventDefault();var s=$('#editorBar [data-lower-upper]');(s.attr('data-lower-upper')==='hidden'?(s.attr('data-lower-upper','shown'),$('[data-id="cmsearch"]').val('').focus()):s.attr('data-lower-upper','hidden'));});
	$('html').on('click', '#editorsearch .close', function(_z){_z.preventDefault();$('[data-id="find"]').click();});
	$('html').on('click', '[data-id="filestatus"] span.close', function(_z){_z.preventDefault();$('[data-lower-lower]').attr('data-lower-lower','hidden');});
    $('html').on('click', '[data-id="browse"]', function(_z){var li=$('li.active.editorurl'); ch.resultBar.show(li.attr('data-id'), li.attr('title'), li.find('h4').text());});

    $(window).on('editor:refresh', function(_z){
    	$('#fileencodingselected').html($('#defaultencoding li.selected span').attr('data-encoding'));
		$('#filemodeselected').html($('#defaultmode li.selected span').attr('data-mode'));
		$('#editorsearch .search').width(($('#editorsearch').width()-$('#editorsearch .editorsearchcontrol').width())-0);
    });

    return {
    	init: function(){
    		if($('#fileencoding').length<=0){
    			$fileencoding = '<li id="fileencoding"><div><span>Encoding: <code id="fileencodingselected">...</code></span></div><ul class="smenu up">';
				for(var i in ch.encoding) {
					$fileencoding += '<li><div><span data-encoding="'+ch.encoding[i]+'">'+ch.encoding[i]+'</span></div></li>';
				}
				$('#fileproperties').prepend($fileencoding+'</ul></li>');
    		}
    		if($('#filemode').length<=0){
    			$filemode='<li id="filemode"><div><span>Mode: <code id="filemodeselected">...</code></span></div><ul class="smenu up">';
				for(var i in ch.mode) {
					$filemode += '<li><div><span data-mode="'+ch.mode[i]+'">'+i+'</span></div></li>';
				}
				$('#fileproperties').prepend($filemode+'</ul></li>');
    		}
    	},
    	load: function(id, encoding, mode, src, name, ext){
    		ele.find('.top .upper').append('<li class="editorurl active" data-id="'+id+'" title="'+src+'" data-encoding="'+encoding+'" data-mode="'+mode+'"><h4>'+name+'.'+ext+'</h4><span class="close fa fa-close"></span></li>');
    		ele.find('#editorarea').append('<textarea data-id="'+id+'" class="active">'+name+'</textarea>');
    		$(window).trigger('editor:refresh');
    	}
    };
})();