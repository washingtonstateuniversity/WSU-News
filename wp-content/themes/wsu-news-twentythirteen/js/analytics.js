(function($){

	var GAcode = 'UA-6322839-2';
	var _load  = 'element_v2';
	var _DN    = 'news.wsu.edu/';
	var _CP    = false;

	var url='//images.wsu.edu/javascripts/tracking/configs/pick.asp';
	$.getJSON(url+'?callback=?'+(_load!=false?'&loading='+_load:''), function(data){
		$.jtrack.defaults.debug.run = false;
		$.jtrack.defaults.debug.v_console = false;
		$.jtrack.defaults.debug.console = true;
		$.jtrack({ load_analytics:{account:GAcode},options:jQuery.extend({},(_DN!=false?{'domainName':_DN}:{}),(_CP!=false?{'cookiePath':_CP}:{})), trackevents:data });
	});
})(jQuery);