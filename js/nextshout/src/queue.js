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
