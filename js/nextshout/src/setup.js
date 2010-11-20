$(document).bind('XenForoWindowFocus', function() {
	if ( NextShout.isWindowFocused ) {
		return;
	}
	NextShout.isWindowFocused = true;
	NextShout.totalNewShouts  = 0;
	NextShout.updateDocTitle();
});

$(document).bind('XenForoWindowBlur', function() {
	NextShout.isWindowFocused = false;
});

$(document).ready(function() {
	NextShout.baseWindowTitle = document.title;
});

// Initialize the shoutbox for all class="NextShoutBox"
XenForo.register('.NextShoutBox', 'NextShout.ShoutBox');
