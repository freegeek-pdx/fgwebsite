jQuery(document).ready(function(){

		// Init this page tree, with #treecontrol and cookie memory
		jQuery("#navigation").treeview({
			persist: "cookie",
			animated: "fast",
			cookieId: "treeview-public-navigation"
		});

	});