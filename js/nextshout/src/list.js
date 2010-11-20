/**
 * Model to represent a list of shouts.
 */
NextShout.ShoutList = function(shoutbox) { this.__construct(shoutbox); };
NextShout.ShoutList.prototype =
{
	/**
	 * Constructor.
	 * @param {NextShout.ShoutBox} Related shoutbox instance.
	 */
	__construct: function(box)
	{
		this.box = box;
	}
};
