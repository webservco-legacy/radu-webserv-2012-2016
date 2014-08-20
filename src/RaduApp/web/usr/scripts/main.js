$(document).ready(function() {

});

function catalogue_delete(item_id) {
	
	if (confirm("Fo Sho??\n\n #" + item_id
			+ " will be gone forever! and ever!")) {

		$.post(url_www + "Catalogue/Ajax/Post", {
			cmd : 'catalogue_delete',
			item : item_id,
		}, function(response) {
			console.log(response);
			//hide
			//$("#item_" + item_id).hide();
			$("#item_" + item_id).html("<span class=\"ko\">⚠ 'Tis Gone, M'Lord!</span>");
			//alert("'Tis Done, M'Lord!");
			
		}).fail(function(object) {
			console.log('error');
			// console.log(object.responseText);
			// console.log(object.status);
			// console.log(object.statusText);
		});
	} else {

		return false;
	}
}

