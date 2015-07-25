$(document).ready(function() {
	
	$(".discogs_price").one('click', function(e) {
		
		$(this).select();
	});
	
	/*
	if ($(".image-popup-vertical-fit").length > 0) {
		
		$('.image-popup-vertical-fit').magnificPopup({
	          type: 'image',
	          closeOnContentClick: true,
	          mainClass: 'mfp-img-mobile',
	          image: {
	            verticalFit: true
	          }
	          
	        });
	}
	*/
	
	//if ($(".image-popup-vertical-fit").length > 0) {
	if ($(".popup-gallery").length > 0) {
		
		$('.popup-gallery').magnificPopup({
	          delegate: 'a',
	          type: 'image',
	          tLoading: 'Loading image #%curr%...',
	          mainClass: 'mfp-img-mobile',
	          gallery: {
	            enabled: true,
	            navigateByImgClick: true,
	            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	          },
	          image: {
	            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
	            titleSrc: function(item) {
	              return item.el.attr('title') + '';
	            }
	          }
	        });
	}
	
});

function discogs_update_price(listing_id) {
	
	var field = $("#discogs_price_" + listing_id);
	
	var price = field.val();
	
	$.post(url_www + "Music/Ajax/Post", {
		cmd : 'discogs_update_field',
		listing_id : listing_id,
		field : 'price',
		value : price,
	}, function(response) {
		//console.log(response);
		field.val(response);
		field.attr('class', 'noborder');
		//alert("'Tis Done, M'Lord!");
		
	}).fail(function(object) {
		//console.log('error');
		// console.log(object.responseText);
		// console.log(object.status);
		// console.log(object.statusText);
	});
}

function discogs_update_comments(listing_id) {
	
	var field = $("#discogs_comments_" + listing_id);
	
	var comments = field.val();
	
	$.post(url_www + "Music/Ajax/Post", {
		cmd : 'discogs_update_field',
		listing_id : listing_id,
		field : 'comments',
		value : comments,
	}, function(response) {
		//console.log(response);
		field.val(response);
		alert("'Tis Done, M'Lord!");
		
	}).fail(function(object) {
		//console.log('error');
		// console.log(object.responseText);
		// console.log(object.status);
		// console.log(object.statusText);
	});
}

function discogs_update_location(listing_id) {
	
	var field = $("#discogs_location_" + listing_id);
	
	var location = field.val();
	
	$.post(url_www + "Music/Ajax/Post", {
		cmd : 'discogs_update_field',
		listing_id : listing_id,
		field : 'location',
		value : location,
	}, function(response) {
		//console.log(response);
		field.val(response);
		alert("'Tis Done, M'Lord!");
		
	}).fail(function(object) {
		//console.log('error');
		// console.log(object.responseText);
		// console.log(object.status);
		// console.log(object.statusText);
	});
}

function catalogue_delete(item_id) {
	
	/*
	if (confirm("Fo Sho??\n\n #" + item_id
			+ " will be gone forever! and ever!")) {*/

		$.post(url_www + "Catalogue/Ajax/Post", {
			cmd : 'catalogue_delete',
			item : item_id,
		}, function(response) {
			//console.log(response);
			//hide
			//$("#item_" + item_id).hide();
			$("#item_" + item_id).html("<span class=\"ko\">⚠ 'Tis Gone, M'Lord! [" + item_id + "]</span>&#160;<a onclick=\"JavaScript:catalogue_restore('" + item_id + "'); return false;\" href=\"\" title=\"Restore\">WAIT!!! I changed my mind!</a>");
			//alert("'Tis Done, M'Lord!");
			
		}).fail(function(object) {
			//console.log('error');
			// console.log(object.responseText);
			// console.log(object.status);
			// console.log(object.statusText);
		});
	/*} else {

		return false;
	}*/
}

function catalogue_restore(item_id) {
	
		$.post(url_www + "Catalogue/Ajax/Post", {
			cmd : 'catalogue_restore',
			item : item_id,
		}, function(response) {
			//console.log(response);
			
			if (response != 'error') {
				
				$("#item_" + item_id).html("<a target=\"_blank\" onclick=\"JavaScript:catalogue_delete('" + item_id + "'); return false;\" href=\"\" title=\"Delete\">⚠</a>&nbsp;" + response + " [" + "<a target=\"_blank\" href=\"" + url_www + "Catalogue/Catalogue/Item/id/" + item_id + "\" title=\"Details\">" + item_id + "</a>" + "]");
			} else {
				$("#item_" + item_id).html("<span class=\"ko\">" + response + "</span>");
			}
			
		}).fail(function(object) {
			//console.log('error');
			// console.log(object.responseText);
			// console.log(object.status);
			// console.log(object.statusText);
		});
}

