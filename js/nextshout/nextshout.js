var NextShout = {};
NextShout.options = {};

/** @param {jQuery} $ jQuery Object */
!function ($, window, document, _undefined)
{
	/**
	 * Data that should be shared between any shoutboxes on the page.
	 */
	$.extend(NextShout,
	{
		timestamps      : [],
		debug           : false,
		isWindowFocused : true,
		baseWindowTitle : '',
		totalNewShouts  : 0,

		updateDocTitle: function()
		{
			if ( !NextShout.options.notifyNewShouts ) {
				return;
			}

			var n = NextShout.totalNewShouts;
			if ( n > 0 && !NextShout.isWindowFocused ) {
				document.title = '(' + n + ') ' + NextShout.baseWindowTitle;
			} else {
				document.title = NextShout.baseWindowTitle;
			}
		}
	});

	/**
	 * Handles storing multiple values in a single cookie.
	 */
	NextShout.Cookie = function(name) { this.__construct(name); };
	NextShout.Cookie.prototype =
	{
		__construct: function(name)
		{
			this.name = name;
			this.str  = $.getCookie(this.name) || '';
			this.data = {};

			var p, d = this.str.split('&');
			for ( var i = 0; i < d.length; i++ ) {
				p = d[i].split('=');
				this.data[decodeURIComponent(p[0])] = decodeURIComponent(p[1]);
			}
		},

		save: function()
		{
			var value = $.param(this.data, true);
			var expiry = new Date();
			expiry.setFullYear(expiry.getFullYear() + 1);
			$.setCookie(this.name, value, expiry);
		},

		set: function(key, value)
		{
			this.data[key] = value;
			this.save();
			return this;
		},

		get: function(key)
		{
			return this.data[key] || null;
		},

		remove: function(key)
		{
			if ( this.data[key] ) {
				delete this.data[key];
				this.save();
			}
			return this;
		}
	};

	/**
	 * Model to represent a single shout.
	 */
	NextShout.Shout = function(list) { this.__construct(list); };
	NextShout.Shout.prototype =
	{
		__construct: function(list)
		{
			this.list = list;
		}
	};
	
	/**
	 * Model to represent a list of shouts.
	 */
	NextShout.ShoutList = function(shoutbox) { this.__construct(shoutbox); };
	NextShout.ShoutList.prototype =
	{
		__construct: function(shoutbox)
		{
			this.shoutbox = shoutbox;
		}
	};
	
	/**
	 * Represents a single shoutbox on the page. Should take markup enclosed in a 
	 * .NextShoutBox element and make it actually do something worthwhile.
	 */
	NextShout.ShoutBox = function($element) { this.__construct($element); };
	NextShout.ShoutBox.prototype = 
	{
		__construct: function($element)
		{
			this.$element  = $element;
			this.shoutList = new NextShout.ShoutList(this);
		}
		// note, when doing ajax, set global: false for options to hide throbber.
	};

	NextShout.ShoutQueue = function(maxSize) { this.__construct(maxSize); };
	NextShout.ShoutQueue.prototype =
	{
		__construct: function(maxSize)
		{
			this.maxSize    = maxSize;
			this.queue      = [];
			this.processing = false;
		}
	};

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
}
(jQuery, this, document);

