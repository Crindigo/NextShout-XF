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
	jsCommands      : {},

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
	},

	/**
	 * Register a command to be handled by JS. The callback receives
	 * a NextShout.ShoutBox instance and the shout text.
	 */
	registerCommand: function(cmd, callback)
	{
		NextShout.jsCommands[cmd] = callback;
	},

	registerCommands: function(commands)
	{
		$.each(commands, function(cmd, callback) {
			NextShout.registerCommand(cmd, callback);
		});
	},
	
	/**
	 * Replaces %param% in text with values of the passed object.
	 * Also handles simple true/false conditions with {if:param}blah{else}foo{endif}.
	 */
	template: function(text, obj)
	{
		obj = obj || {};

		// the price we pay to make templates look a bit nicer.
		text = text.replace(/\{if:([A-Za-z0-9_]+)\}(.*)\{else\}(.*)\{endif\}/g, function(str, cond, ifTrue, ifFalse) {
			return obj[cond] ? ifTrue : ifFalse;
		});

		return text.replace(/%([A-Za-z0-9_]+)%/g, function(str, param1) {
			if ( typeof obj[param1] != 'undefined' ) {
				return obj[param1];
			} else {
				return str;
			}
		});
	}

});

NextShout.registerCommands({
	color: function(box, arg) { box.setColor(arg); },
	style: function(box, arg) { box.setStyle(arg); },
	font : function(box, arg) { box.setFont(arg); }
});

/**
 * Handles storing multiple values in a single cookie.
 */
NextShout.Cookie = function(prefix) { this.__construct(prefix); };
NextShout.Cookie.prototype =
{
	__construct: function(prefix)
	{
		this.name   = 'nextshout';
		this.prefix = prefix || '';
		this.str    = $.getCookie(this.name) || '';
		this.data   = {};

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
		key = this.prefix + key;
		this.data[key] = value;
		this.save();
		return this;
	},

	get: function(key)
	{
		key = this.prefix + key;
		return this.data[key] || null;
	},

	remove: function(key)
	{
		key = this.prefix + key;
		if ( this.data[key] ) {
			delete this.data[key];
			this.save();
		}
		return this;
	}
};
