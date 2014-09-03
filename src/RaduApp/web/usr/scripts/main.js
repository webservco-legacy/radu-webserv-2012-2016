$(document).ready(function() {

});

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
				
				$("#item_" + item_id).html("<a target=\"_blank\" onclick=\"JavaScript:catalogue_delete('" + item_id + "'); return false;\" href=\"\" title=\"Delete\">⚠</a>&nbsp;" + response + " [" + item_id + "]");
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

