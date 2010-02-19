jQuery(function($) {
	var gallerySortable;
	var gallerySortableInit = function() {
		try { // a hack to make sortables work in jQuery 1.2+ and IE7
			$('#media-items').SortableDestroy();
		} catch(e) {}
		$('#media-items .media-item').
		gallerySortable = $('#media-items').Sortable( {
			accept: 'media-item',
			helperclass: 'sorthelper',
			handle: 'div.filename',
			onchange: galleryReorder,
			onStop: gallerySortableInit
		} );
	}
	
	var galleryReorder = function(myOrder) {
		jQuery.each(myOrder[0]['o']['media-items'], function(i, id) {
			jQuery('#' + id + ' .menu_order input')[0].value = i;
		})
	}

	// initialize sortable
	gallerySortableInit();
	$('#media-items .media-item').css({
		position: 'static'
	});
	$('#media-items .media-item div.filename').css({
		cursor: 'move'
	});
});
