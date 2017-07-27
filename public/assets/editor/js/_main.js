ch=window.ch||{};

ch.encoding = ["ASCII", "Big5", "EUC-KR", "GB2312", "KOI8-R", "CP1256", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-13", "ISO-8859-14", "ISO-8859-15", "JIS", "Windows-1250", "Windows-1251", "Windows-1252", "Windows-1253", "Windows-1254", "Windows-1255", "Windows-1256", "Windows-1257", "Windows-1258", "UTF-7", "UTF-8", "UTF-16", "UTF-32"];
ch.mode = {"APL": "text/apl", "ASP.NET": "application/x-aspx", "Asterisk": "text/x-asterisk", "C": "text/x-csrc", "C#": "text/x-csharp", "C++": "text/x-c++src", "CMake": "text/x-cmake", "CQL": "text/x-cassandra", "CSS": "text/css", "Clojure": "text/x-clojure", "Cobol": "text/x-cobol", "CoffeeScript": "text/x-coffeescript", "Common Lisp": "text/x-common-lisp", "Cypher": "application/x-cypher-query", "Cython": "text/x-cython", "DTD": "application/xml-dtd", "Dart": "application/dart", "Django": "text/x-django", "DockerFile": "text/x-dockerfile", "Dylan": "text/x-dylan", "EBNF": "text/x-ebnf", "ECL": "text/x-ecl", "Eiffel": "text/x-eiffel", "Embedded Javascript": "application/x-ejs", "Embedded Ruby": "application/x-erb", "Erlang": "text/x-erlang", "F#": "text/x-fsharp", "Forth": "text/x-forth", "Fortran": "text/x-fortran", "Gas": "text/x-gas", "Gherkin": "text/x-feature", "GitHub Flavored Markdown": "text/x-gfm", "Go": "text/x-go", "Groovy": "text/x-groovy", "HAML": "text/x-haml", "HTML": "text/html", "HTTP": "message/http", "HXML": "text/x-hxml", "Haskell": "text/x-haskell", "Haxe": "text/x-haxe", "IDL": "text/x-idl", "JSON": "application/json", "JSON-LD": "application/ld+json", "Jade": "text/x-jade", "Java": "text/x-java", "Java Server Pages": "application/x-jsp", "JavaScript": "text/javascript", "Jinja2": null, "Julia": "text/x-julia", "Kotlin": "text/x-kotlin", "LESS": "text/x-less", "LaTeX": "text/x-latex", "LiveScript": "text/x-livescript", "Lua": "text/x-lua", "MS SQL": "text/x-mssql", "MariaDB": "text/x-mariadb", "MariaDB SQL": "text/x-mariadb", "Markdown": "text/x-markdown", "Modelica": "text/x-modelica", "MySQL": "text/x-mysql", "NTriples": "text/n-triples", "Nginx": "text/x-nginx-conf", "OCaml": "text/x-ocaml", "Objective C": "text/x-objectivec", "Octave": "text/x-octave", "PEG.js": null, "PGP": "application/pgp", "PHP": "application/x-httpd-php", "PLSQL": "text/x-plsql", "Pascal": "text/x-pascal", "Perl": "text/x-perl", "Pig": "text/x-pig", "Plain Text": "text/plain", "Properties files": "text/x-properties", "Puppet": "text/x-puppet", "Python": "text/x-python", "Q": "text/x-q", "R": "text/x-rsrc", "RPM Changes": "text/x-rpm-changes", "RPM Spec": "text/x-rpm-spec", "Ruby": "text/x-ruby", "Rust": "text/x-rustsrc", "SCSS": "text/x-scss", "SPARQL": "application/sparql-query", "SQL": "text/x-sql", "Sass": "text/x-sass", "Scala": "text/x-scala", "Scheme": "text/x-scheme", "Shell": "text/x-sh", "Sieve": "application/sieve", "Slim": "text/x-slim", "Smalltalk": "text/x-stsrc", "Smarty": "text/x-smarty", "Solr": "text/x-solr", "Soy": "text/x-soy", "Spreadsheet": "text/x-spreadsheet", "SystemVeriLog": "text/x-systemverilog", "TOML": "text/x-toml", "Tcl": "text/x-tcl", "Textile": "text/x-textile", "TiddlyWiki": "text/x-tiddlywiki", "Tiki wiki": "text/tiki", "Tornado": "text/x-tornado", "Turtle": "text/turtle", "TypeScript": "application/typescript", "VB.NET": "text/x-vb", "VBScript": "text/vbscript", "Velocity": "text/velocity", "Verilog": "text/x-verilog", "XML": "application/xml", "XQuery": "application/xquery", "YAML": "text/x-yaml", "Z80": "text/x-z80", "diff": "text/x-diff", "mIRC": "text/mirc", "reStructuredText": "text/x-rst", "sTeX": "text/x-stex", "troff": "troff"};
ch.search=function(inputEle, fromEle, exactEle) {
	$('html').on('keyup', inputEle, function(_z) {
		_z.preventDefault();
		var val=$(this).val().toUpperCase();
		$(fromEle).each(function(index){
			if($(this).find(exactEle).text().toUpperCase().indexOf(val)>-1){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	}).on('blur', inputEle, function(_z){
		_z.preventDefault();
		$(this).val('');
		$(fromEle).show();
	});
};
ch.editor=(function(){
	$('html').on('click','[data-action]',function(_z){
	    _z.preventDefault();
	    var t=$(this),d=t.attr('data-action'),j=$.parseJSON(d);
		if(typeof j==='object'){
		  if(j.id==='data'){
		    w=$(j.which).attr(j.which.replace('[','').replace(']',''));
		    $(j.which).attr(j.which.replace('[','').replace(']',''),j.with);
		    t.attr('data-action','{"id":"'+j.id+'","which":"'+j.which+'","with":"'+w+'"}');
		  }else if(j.id==='class'){
		    $(j.which).toggleClass(j.which,j.with);
		  }
		}
	});
	$('html').on('click', '.tmenu li div', function(_z){_z.preventDefault();var t=$(this).parent('li');(t.hasClass('active')?t.removeClass('active'):(($(this).next('ul.smenu').length>0?($('.tmenu li').removeClass('active'),t.addClass('active')):($(this).next('ul.ssmenu').length>0?($('.tmenu li li').removeClass('active'),t.addClass('active')):!1))));});
	$('html').on('click','.top .upper li h4',function(_z){_z.preventDefault();var t=$(this),p=t.parent('li'),id=p.attr('data-id'),pp=p.parent('ul'),ppp=pp.parent('div').parent('div').attr('id');(!$(this).hasClass('active')?(pp.find('li.active').removeClass('active'),p.addClass('active'),(ppp==='editorBar'?($('#editorBar').find('textarea.active').removeClass('active').end().find('textarea[data-id="'+id+'"]').addClass('active'),$('#filemodeselected').html(p.attr('data-mode')),$('#fileencodingselected').html(p.attr('data-encoding'))):(ch.resultBar.show(id, p.attr('title'), p.find('h4').text())))):!1);});
	$('html').on('click','.top .upper li .close',function(_z){_z.preventDefault();var t=$(this),p=t.parent('li'),id=p.attr('data-id'),pp=p.parent('ul'),ppp=pp.parent('div').parent('div').attr('id'),li=(p.prev('li').length>0?p.prev('li'):p.next('li'));(pp.find('li').length===1 ?($(window).trigger('frame:closed'),$('#'+ppp).remove(),$('body').attr('data-'+ppp,'hidden')):((($('#'+ppp+' [data-id="'+id+'"]').hasClass('active') && $('#'+ppp+'.top .upper li.active').length<=1)?($('#'+ppp+' [data-id="'+li.attr('data-id')+'"]').addClass('active')):!1),$('#'+ppp+' [data-id="'+id+'"]').remove(),(ppp==='editorBar'?($('#filemodeselected').html(li.attr('data-mode')),$('#fileencodingselected').html(li.attr('data-encoding'))):!1)));});
})();