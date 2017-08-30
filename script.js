$(function() {
	$("#componentTree").delegate("input[type=checkbox][name=selectFile]","change",function() {
		nx=$("#componentTree").find("input[type=checkbox][name=selectFile]:checked").length;
		if(nx>0) {
			$("#pgtoolbar .onsidebarSelect").show();

			if(nx>1) {
				$("#pgtoolbar .onOnlyOneSelect").hide();
			}
		} else {
			$("#pgtoolbar .onsidebarSelect").hide();
		}
	});
	
	$("#pgtoolbar a[data-cmd]").click(function() {
		cmd=$(this).data("cmd");
		func="pg"+cmd.charAt(0).toUpperCase()+cmd.substr(1)
		if(window[func]!=null) {
			window[func](this);
		} else if(window[func.toLowerCase()]!=null) {
			window[func.toLowerCase()](this);
		} else if(window[cmd]!=null) {
			window[cmd](this);
			//console.warn("Page function not defined for : "+cmd);
		} else {
			console.warn("Page function not defined for : "+cmd);
		}
	});
	$("#pgtoolbar a[data-drop]").click(function() {
		$("#pgtoolbar a[data-drop]").closest("li[data-cmd]").find("a.active").removeClass("active");
		$(this).addClass("active");

		dx=$(this).data("drop");
		cmd=$(this).closest("li[data-cmd]").data("cmd");
		func="pg"+cmd.charAt(0).toUpperCase()+cmd.substr(1)

		if(window[func]!=null) {
			window[func](this,dx);
		} else if(window[func.toLowerCase()]!=null) {
			window[func.toLowerCase()](this,dx);
		} else if(window[cmd]!=null) {
			window[cmd](this);
			//console.warn("Page function not defined for : "+cmd);
		} else {
			console.warn("Page function not defined for : "+cmd);
		}
	});
	
	$("#pgtoolbar a[data-drop]").closest("li[data-cmd]").find("a.active").removeClass("active");
});
