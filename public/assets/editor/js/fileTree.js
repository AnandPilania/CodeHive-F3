ch.fileTree=(function(){
	var context={
		file: {
			edit:{name:"Edit",icon:"edit",callback:function(key,opt){alert("Edit!");}},
			cut:{name:"Cut",icon:"cut",callback:function(key,opt){alert("Cut!");}},
			copy:{name:"Copy",icon:"copy",callback:function(key,opt){alert("Edit!");}},
			delete:{name:"Delete",icon:"delete",callback:function(key,opt){alert("Delete!")}},
			rename:{name:"Rename",icon:"",callback:function(key,opt){alert("Rename!");}},
			sep1: "----------",
			share:{name:"Share",icon:"",callback:function(key,opt){alert("Share!");}},
			fold1: {
				name: "Download",
				items: {
					"fold1-key1":{name:"As file",icon:"",callback:function(key,opt){alert("Share!");}},
					"fold1-key2":{name:"As ZIP",icon:"",callback:function(key,opt){alert("Share!");}}
				}
			},
			"sep2": "---------",
			properties:{name:"Properties",icon:"properties",callback:function(key,opt){alert("Propertise!");}}
		},
		dir: {
			move:{name:"Move",icon:"move",callback:function(key,opt){alert("Move!");}},
			copy:{name:"Copy",icon:"copy",callback:function(key,opt){alert("Copy!");}},
			delete:{name:"Delete",icon:"delete",callback:function(key,opt){alert("Delete!")}},
			rename:{name:"Rename",icon:"",callback:function(key,opt){alert("Rename!");}},
			sep1: "----------",
			share:{name:"Share",icon:"",callback:function(key,opt){alert("Share!");}},
			fold1: {
				name: "Download",
				items: {
					"fold1-key1":{name:"As gz",icon:"",callback:function(key,opt){alert("Share!");}},
					"fold1-key2":{name:"As ZIP",icon:"",callback:function(key,opt){alert("Share!");}}
				}
			},
			"sep2": "---------",
			properties:{name:"Properties",icon:"properties",callback:function(key,opt){alert("Propertise!");}}
		}
	};

	$.contextMenu({
	  selector:"#fileTree li[data-type='file']",
	  items:context.file
	});
	$.contextMenu({
	  selector:"#fileTree li[data-type='dir']",
	  items:context.dir
	});

	return {};
})();