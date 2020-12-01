jQuery(document).ready(function() {
		
	function getParameterByName(name, url) {
		    if (!url) url = window.location.href;
		    name = name.replace(/[\[\]]/g, "\\$&");
		    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		        results = regex.exec(url);
		    if (!results) return null;
		    if (!results[2]) return '';
		    return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		
		function checkLaunchModal() {
			var action = getParameterByName('action');
			
			if(action == 'read') {
				
				jQuery( "details:contains('Read')" ).attr('open', '');
				jQuery( "body,html" ).animate({scrollTop: jQuery("details:contains('Read')").offset().top},1000);
				// jQuery( "details:contains('Leggi')" ).attr('open');
				// jQuery( "details:contains('Lire')" ).attr('open');
				
			}
			
			if(action == 'share') {

				jQuery('#share-modal').modal('show');
				
			}
			
		}
		
		checkLaunchModal();
		
});