ch.editorBar=(function(){
	$fileproperties = '<li id="fileencoding"><div><span>Encoding: <code id="fileencodingselected">...</code></span></div><ul class="smenu up">';
	for(var i in ch.encoding) {
		$fileproperties += '<li><div><span data-encoding="'+ch.encoding[i]+'">'+ch.encoding[i]+'</span></div></li>';
	}
	$fileproperties += '</ul></li><li id="filemode"><div><span>Mode: <code id="filemodeselected">...</code></span></div><ul class="smenu up">';
	for(var i in ch.mode) {
		$fileproperties += '<li><div><span data-mode="'+ch.mode[i]+'">'+i+'</span></div></li>';
	}
	$('#fileproperties').prepend($fileproperties+'</ul></li>');

	$('html').on('click', '#fileproperties [data-mode]', function(_z){_z.preventDefault();var t=$(this),dm=t.attr('data-mode');$('#filemodeselected').html(dm);$('.editorurl.active').attr('data-mode',dm);t.parent('li').addClass('active');});
	$('html').on('click', '#fileproperties [data-encoding]', function(_z){_z.preventDefault();var t=$(this),dm=t.attr('data-encoding');$('#fileencodingselected').html(dm);$('.editorurl.active').attr('data-encoding',dm);t.parent('li').addClass('active');});
	$('#fileencodingselected').html($('#defaultencoding li.selected span').attr('data-encoding'));
	$('#filemodeselected').html($('#defaultmode li.selected span').attr('data-mode'));

	$('html').on('click', '[data-id="find"],[data-id="replace"]', function(_z){_z.preventDefault();var s=$('#editorBar [data-lower-upper]');(s.attr('data-lower-upper')==='hidden'?(s.attr('data-lower-upper','shown'),$('[data-id="cmsearch"]').val('').focus()):s.attr('data-lower-upper','hidden'));});
	$('html').on('click', '#editorsearch .close', function(_z){_z.preventDefault();$('[data-id="find"]').click();});
	$('#editorsearch .search').width(($('#editorsearch').width()-$('#editorsearch .editorsearchcontrol').width())-0);
    $('html').on('click', '[data-id="filestatus"] span.close', function(_z){_z.preventDefault();$('[data-lower-lower]').attr('data-lower-lower','hidden');});
    $('html').on('click', '[data-id="browse"]', function(_z){var li=$('li.active.editorurl'); ch.resultBar.show(li.attr('data-id'), li.attr('title'), li.find('h4').text());});
})();