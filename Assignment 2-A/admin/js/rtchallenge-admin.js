
(function( $ ) {

	'use strict';


	//function that returns url params ( Query String )
	$.getprm = function getQueryStringValue (key) {  
  		return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));  
	} 
	
	var post_id = $.getprm('post');


	 var sortList;
	 var errorPlace;
	$( function() {

		sortList = $( "#sortable" );
		errorPlace = $('#rtc-upload-images-meta');
	    
	    sortList.sortable();
	    // $( "#sortable" ).disableSelection();
  	} );


  	// scrip that displays media uploader
  	jQuery(document).ready(function($){

	  var mediaUploader;

	  $('#upload-button').click(function(e) {
		    e.preventDefault();
		    // If the uploader object has already been created, reopen the dialog
		      if (mediaUploader) {
		      mediaUploader.open();
		      return;
		    }
		    // Extend the wp.media object
		    mediaUploader = wp.media.frames.file_frame = wp.media({
		      title: 'Choose Image',
		      button: {
		      text: 'Choose Image'
		    }, multiple: true });

		    // When a file is selected, add it to the custom_metabox
		    mediaUploader.on('select', function() {
		    	//Add the uploaded images to the display box (custom_metabox);
		    	var attachment =  mediaUploader.state().get('selection').toJSON();
		    	for(var i=0 ; i<attachment.length; i++ ) {
		    		var li = document.createElement('li');
		    		var im = document.createElement('img');
		    		im.src = attachment[i].url;
		    		li.appendChild(im);
		    		li.className = "ui-state-default";
		    		// li.style = "";
		    		$('#sortable').append(li);
		    	}

		    	addDeleteButton();
		    
		    });
		    // Open the uploader dialog
		    mediaUploader.open();
		});

	});

	//function that updates the post
	function update_post( status, message ) {
		$.ajax({
	    			url: ajaxurl,
	    			type: 'POST',
	    			dataType: 'json',
	    			data: {
	    				action: 'save_order',
	    				order: $('#sortable').html(),
	    				post_id: post_id,
	    				post_title: $('#title').val(),
	    				post_status: status
	    			},
	    			success: function( result ){
	    				console.log( result );
	    				if(!document.getElementById(('suc_msg')))
	    					errorPlace.before('<div id="suc_msg" hidden class="updated">'+message+'</div>')
	    				
	    				$('#suc_msg').show('slow');
	    				setTimeout(function() {
	    					$('#suc_msg').hide('slow');
	    				}, 5000);
	    			},

	    			error: function( result ){
	    				console.log(result);
	    				errorPlace.before('<div id="suc_msg" class="error">Something went wrong!</div>')
	    			}
	    		});
	}


		//when save-draft is clicked
	
		// $('#save-post').on('click',function(event){
		// 	console.log("fireeeeeeed");
		// 	removeDeleteButtons();
		// 	update_post( 'draft', 'Draft Saved' );
		// 	var title = $('#title').val();
		// 	$('#post').trigger('reset');
		// 	location.reload();
		// 	return false;

		// });

		//when publish is clicked

		// $('#publish').on('click',function(event){
		// 	removeDeleteButtons();
		// 	update_post( 'publish', 'Post '+$('#publish').val()+'d' );
		// 	var title = $('#title').val();
		// 	$('#post').trigger('reset');
		// 	$('#title').val(title);
		// 	location.reload();
		// 	return false;

		// });


		// to add delete buttons to images


		function addDeleteButton() {
			
			var lis = document.getElementById('sortable').childNodes;

			console.log( $('#sortable').html() );
			
			
			for (var i = 0; i<lis.length; i++) {
				
				if(lis[i].nodeName != 'LI') continue;
				if(lis[i].childElementCount>1) {
					// lis[i].removeChild(lis[i].childNodes[1]);
					continue;
				}

				let div = document.createElement('div');
				div.innerHTML = 'Delete';
				div.className = 'deleteButton';
				let lisi = lis[i];
				div.onclick = function() {
					lisi.parentNode.removeChild(lisi);
					console.log(lisi);
				}
				lis[i].appendChild(div);
			}
				
		}

		function removeDeleteButtons() {
			var lis = document.getElementById('sortable').childNodes;
			
			
			for (var i = 0; i<lis.length; i++) {
				
				if(lis[i].nodeName != 'LI') continue;
				if(lis[i].childElementCount>1) {
					lis[i].removeChild(lis[i].childNodes[1]);
				}
			}
		}


		function addContentsToHiddenField() {
			
			removeDeleteButtons();
			var lis = document.getElementById( 'sortable' ).innerHTML;
			$( '#images-hidden' ).val(lis.trim());
			console.log($( '#images-hidden' ).val());

		}

		document.getElementById('post').onsubmit = function() {
			addContentsToHiddenField();
			
		}

		addDeleteButton();


  

})( jQuery );









 
