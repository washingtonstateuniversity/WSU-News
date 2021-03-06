(function($,window){
	var strHeader;
	var strFooter;
	var thedate = new Date().getFullYear();

	function getHeader() {
		strHeader = '' + '<div id=\'globalnav\'>' + '<ul>' + '<li><a href=\'http://index.wsu.edu/\'>A-Z Index</a></li>' + '<li><a href=\'http://www.about.wsu.edu/statewide/\'>Statewide</a></li>' + '<li><a href=\'https://portal.wsu.edu/\'>zzusis</a></li>' + '<li><a href=\'http://www.wsu.edu/\'>WSU Home</a></li>' + '<li>' + getSearchBar() + '</li>' + '</ul>' + '</div>' + '<div id=\'logo\'><a href=\'http://www.wsu.edu\'><img src=\'//repo.wsu.edu/identity/bg-logo3.jpg\' alt=\'Washington State University - World Class Face to Face\' width=\'185\' height=\'105\' /></a></div><div></div>';
		return strHeader
	}
	function getFooter() {
		strFooter = '' + '<div id=\'wsufooter\'>' + '<a href=\'http://publishing.wsu.edu/copyright/WSU.html\'>&copy; ' + thedate + '</a> ' + '<a href=\'http://www.wsu.edu\'>Washington State University</a> | ' + '<a href=\'http://access.wsu.edu/\'>Accessibility</a> | ' + '<a href=\'http://policies.wsu.edu/\'>Policies</a> | ' + '<a href=\'http://publishing.wsu.edu/copyright/WSU.html\'>Copyright</a>' + '</div>';
		return strFooter
	}
	function getImageButton() {
		var str;
		str = '<a href=\'#\' onclick=\'document.wsu_headersearch.submit(); return false;\'>';
		str += '<img border=\'0\' alt=\'Submit\' id=\'searchbuttonimg\' width="9" height="12" src=\'//repo.wsu.edu/identity/global-search-arrow.jpg\' align=\'top\'>';
		str = str + '</a>';
		return str
	}
	function checktextbox() {
		if ( document.wsu_headersearch.my_filter.value.replace( /\s+/g, '' ) == '' ) {
			document.wsu_headersearch.my_filter.value = 'Search WSU Web/People'
		}
		return false
	}
	function erasetextbox() {
		if ( document.wsu_headersearch.my_filter.value.toUpperCase() == 'SEARCH WSU WEB/PEOPLE' ) {
			document.wsu_headersearch.my_filter.value = ''
		}
		return false
	}
	function getSearchBar() {
		var searchhtml = '<form name=\'wsu_headersearch\' method=\'get\' action=\'http://search.wsu.edu/Default.aspx\' id=\'globalnavsearchform\'><input name=\'cx\' value=\'013644890599324097824:kbqgwamjoxq\' type=\'hidden\'/><input name=\'cof\' value=\'FORID:11\' type=\'hidden\'/> <input name=\'sa\' value=\'Search\' type=\'hidden\'/> <input name=\'fp\' value=\'true\' type=\'hidden\'/> <input class=\'txtsearch2\' name=\'q\' type=\'text\' value=\'Search WSU Web/People\' onClick=\'erasetextbox();\' onBlur=\'checktextbox();\' id=\'my_filter\' />';
		searchhtml += getImageButton();
		searchhtml += '</form>';
		return searchhtml
	}

	// Make these public.
	window.erasetextbox = erasetextbox;
	window.checktextbox = checktextbox;

	strHeader = getHeader();
	$('#wrapper' ).prepend(strHeader);
	strFooter = getFooter();
	$('body' ).append(strFooter);

})(jQuery, window);