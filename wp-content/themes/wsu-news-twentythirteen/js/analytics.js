(function($, window){

	function param( name , process_url ){if(typeof(process_url)==='undefined'){process_url=window.location.href;}name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");var regexS = "[\\?&]"+name+"=([^&#]*)";var regex = new RegExp( regexS );var results = regex.exec( process_url );if( results == null ){ return false;}else{return results[1];}}

	var url = 'https://news.wsu.edu/bootstrap/bootstrap_v3.js?gacode=UA-6322839-2&amp;loading=element_v2&amp;domainName=news.wsu.edu/';
	var GAcode = param("gacode", url );
	var _load  = param("loading", url );
	var _DN    = param("domainName", url );
	var _CP    = param("cookiePath", url );

	var url='//images.wsu.edu/javascripts/tracking/configs/pick.asp';
	$.getJSON(url+'?callback=?'+(_load!=false?'&loading='+_load:''), function(data){
		$.jtrack.defaults.debug.run = false;
		$.jtrack.defaults.debug.v_console = false;
		$.jtrack.defaults.debug.console = true;
		$.jtrack({ load_analytics:{account:GAcode},options:jQuery.extend({},(_DN!=false?{'domainName':_DN}:{}),(_CP!=false?{'cookiePath':_CP}:{})), trackevents:data });
	});
})(jQuery, window);