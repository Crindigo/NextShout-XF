/**
 * Represents a single shoutbox on the page. Should take markup enclosed in a 
 * .NextShoutBox element and make it actually do something worthwhile.
 */
NextShout.ShoutBox = function($element) { this.__construct($element); };
NextShout.ShoutBox.prototype = 
{
	__construct: function($element)
	{
		this.$element = $element;
		this.identifier = $element.data('identifier') || 'main';
		this.defaultChannel = $element.data('defaultChannel') || 'main'; // fixme, use channel 1 from data

		this.$title = $element.find('h3.subHeading');
		this.baseTitle = this.$title.html();

		this.$input  = $element.find('.nextshout_inputRow > input.textCtrl');
		this.$button = $element.find('.nextshout_inputRow > input.button');
		this.$channelIndicator = $element.find('.nextshout_inputRow > span');
		this.canShout = (this.$input.length > 0);

		this.$shoutList = $element.find('div.nextshout_shoutList');
		this.shoutList  = new NextShout.ShoutList(this);

		this.$loadAnim = $element.find('div.nextshout_loadAnim');
		
		this.loadCookie();
		this.setupDragger();
		this.requestData();
//		setInterval($.context(this.runResizeQueue, this), 100);
	},
	// note, when doing ajax, set global: false for options to hide throbber.

	/**
	 * Loads settings stored in a cookie prefixed with this box's identifier.
	 */
	loadCookie: function()
	{
		this.cookie = new NextShout.Cookie(this.identifier + '_');
		
		// channel setup
		var channel = this.cookie.get('channel');
		if ( !channel || !this.isValidChannel(channel) ) {
			channel = this.defaultChannel;
		}
		this.changeChannel(channel, true);
		
		// last channel used (aggregate)
		this.lastChannelUsed = this.cookie.get('lastChannel');
		if ( !this.lastChannelUsed || !this.isValidChannel(this.lastChannelUsed) ) {
			this.lastChannelUsed = this.defaultChannel;
		}
		
		// height
		var height = this.cookie.get('height');
		if ( height ) {
			this.$shoutList.height(height);
		}

		// custom styles
		this.setColor(this.cookie.get('color'));
		this.setStyle(this.cookie.get('style'));
		this.setFont(this.cookie.get('font'));
	},

	/**
	 * Runs procedures for changing the channel (updating title, reloading)
	 */
	changeChannel: function(channel, skipRequest)
	{
		if ( !this.isValidChannel(channel) ) {
			return;
		}

		this.channel = channel;
		this.$title.html(this.baseTitle + ' - ' + this.channel); // fixme, use channel name not ident

		if ( skipRequest ) {
			return;
		}

		this.cookie.set('channel', this.channel);
		if ( this.canShout ) {
			this.$button.val('Shout'); // fixme, phrase this
			this.$input.focus();
		}

		this.list.clear();
		this.lastDelta = -1;
		this.requestData();
	},

	/**
	 * Sets up the drag-drop resize feature.
	 */
	setupDragger: function()
	{
		this.isDragging = false;

		var _dragStart = function(e)
		{
			this.isDragging = true;
			this.dragStartY = e.pageY;
			this.dragStartH = this.$shoutList.height();
		};

		var _dragMove = function(e)
		{
			if ( this.isDragging ) {
				var newHeight = this.dragStartH + (e.pageY - this.dragStartY);
				this.$shoutList.height(newHeight);
			}
		};

		var _dragStop = function(e)
		{
			if ( this.isDragging ) {
				// set cookie and auto resize images
				this.cookie.set('height', this.$shoutList.height());
				
				this.isDragging = false;
			}
		};


		$footer = this.$element.find('div.sectionFooter');
		$footer.mousedown($.context(_dragStart, this));
		$(document).mousemove($.context(_dragMove, this));
		$(document).mouseup($.context(_dragStop, this));
	},
	
	/**
	 * Attempt to execute a clientside command.
	 */
	executeCommand: function(shout)
	{
		var givenCmd = shout.match(/^\/([A-Za-z0-9]+)/), fn;
		if ( givenCmd && givenCmd[1] && NextShout.jsCommands[givenCmd[1]] ) {
			fn = NextShout.jsCommands[givenCmd[1]];
			fn(this, shout.substr(givenCmd[1].length + 2));
			this.clearInput();
			return true;
		}
		return false;
	},

	/**
	 * Toggles the custom load animation.
	 */
	toggleLoadAnim: function(onOff)
	{
		this.$loadAnim.css('visibility', onOff ? 'visible' : 'hidden');
	}
};
