/**
 * Model to represent a single shout.
 */
NextShout.Shout = function(list, shout) { this.__construct(list, shout); };

// constants
$.extend(NextShout.Shout, {
	FLAG_ME    : 1,
	FLAG_EDIT  : 2,
	FLAG_DELETE: 4
});

NextShout.Shout.TEMPLATE_SIMPLE =
  '<li class="nextshout_shoutSimple">\n'
+ '  %shout%\n'
+ '</li>\n';

NextShout.Shout.TEMPLATE_FULL = 
  '<li class="nextshout_shoutFull">\n'
+ '  %deleteIcon%\n'
+ '  %editIcon%\n'
+ '  %channelData%\n'
+ '  <span class="nextshout_date">[%dateText%]</span>\n'
+ '  {if:meShout}{else}<span class="nextshout_user">%userLink%:</span>{endif}\n'
+ '  {if:meShout}<span class="nextshout_me">*%userLink% %shout%*</span>{else}<span class="nextshout_shout">%shout%</span>{endif}\n'
+ '</li>\n';

NextShout.Shout.prototype =
{
	__construct: function(list, shout)
	{
		this.list = list;
		this.box  = this.list.box;
		
		// load/normalize shout data
		this.id           = shout.ID || 0;
		this.channelName  = shout.CN || '';
		this.channelColor = shout.CC ? 'color:' + shout.CC : '';
		this.dateText     = shout.DT || '';
		this.userId       = shout.UI || 0;
		this.userName     = shout.UN || '';
		this.shout        = shout.ST || '';

		this.meShout      = (shout.OP & NextShout.Shout.FLAG_ME)     ? true : false;
		this.showEditIcon = (shout.OP & NextShout.Shout.FLAG_EDIT)   ? true : false;
		this.showDelIcon  = (shout.OP & NextShout.Shout.FLAG_DELETE) ? true : false;

		this.cachedHtml = '';
	},

	render: function()
	{
		return this.id ? this.renderFull() : this.renderSimple();
	},

	renderFull: function()
	{
		if ( this.cachedHtml.length ) {
			return this.cachedHtml;
		}

		this.cachedHtml = NextShout.template(NextShout.Shout.TEMPLATE_FULL, {
			id          : this.id,
			channelData : channelData,
			dateText    : this.dateText,
			userLink    : 'members/' + this.userId + '/', // fix me?
			userName    : this.userName,
			shout       : shout,
			deleteIcon  : deleteIcon,
			editIcon    : editIcon
		});
		return this.cachedHtml;
	},

	renderSimple: function()
	{
		return NextShout.template(NextShout.Shout.TEMPLATE_SIMPLE, {
			shout: this.shout
		});
	}
};
