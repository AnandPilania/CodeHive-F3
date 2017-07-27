ch.leftBar=(function(){
	//$('html').on('focus','#leftbarsearch',function(_z){_z.preventDefault();$(this).parent('div').addClass('active');}).on('blur','#leftbarsearch',function(_z){$(this).val('').parent('div').removeClass('active');});
	ch.search('#leftbarsearch', '#fileTree li', 'h4');
})();