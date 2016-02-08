var LiveUpdate = {};

!function($, window, document, _undefined)
{
	LiveUpdate.SetupAutoPolling = function()
	{
		if (!$('html').hasClass('LoggedIn'))
		{
			return;
		}

		if (!LiveUpdate.displayOptions)
		{
			return;
		}

		$(document).bind('XFAjaxSuccess', LiveUpdate.AjaxSuccess);

		LiveUpdate.AjaxSuccess();
		setInterval(LiveUpdate.PollServer, LiveUpdate.pollInterval / 2);
	};

	LiveUpdate.PollServer = function()
	{
		if (!LiveUpdate.xhr && new Date().getTime() - LiveUpdate.lastAjaxCompleted > LiveUpdate.pollInterval)
    	{
    		var ajaxStart = $(document).data('events').ajaxStart[0].handler;
    		$(document).unbind('ajaxStart', ajaxStart);
    		LiveUpdate.xhr = XenForo.ajax('index.php?liveupdate', {}, function(){},
			{
    			error: function(xhr, responseText, errorThrown)
    			{
    				delete(LiveUpdate.xhr);
    				switch (responseText)
					{
						case 'timeout':
						{
							console.warn(XenForo.phrases.server_did_not_respond_in_time_try_again);
							break;
						}
						case 'parsererror':
						{
							console.error('PHP ' + xhr.responseText);
							break;
						}
					}
					return false;
    			},
    			timeout: LiveUpdate.pollInterval
    		});
    		$(document).bind('ajaxStart', ajaxStart);
    	}
	};

	LiveUpdate.AjaxSuccess = function(ajaxData)
	{
		var count = parseInt($('#ConversationsMenu_Counter span.Total').text()) + parseInt($('#AlertsMenu_Counter span.Total').text());

		if (LiveUpdate.displayOptions.indexOf('tab_icon') !== -1)
		{
			LiveUpdate.favico.badge(count);
		}

		if (LiveUpdate.displayOptions.indexOf('tab_title') !== -1)
		{
			LiveUpdate.SetTabTitle(count);
		}

		if (!count && LiveUpdate.Notification)
		{
			LiveUpdate.Notification.close();
		}

  		LiveUpdate.lastAjaxCompleted = new Date().getTime();

  		delete(LiveUpdate.xhr);
	};

	LiveUpdate.pageTitleCache = '';

	LiveUpdate.SetTabTitle = function(count)
	{
		pageTitle = document.title;
		if (LiveUpdate.pageTitleCache.length == 0)
		{
			LiveUpdate.pageTitleCache = pageTitle;
		}
		if (pageTitle.charAt(0) === '(')
		{
			pageTitle = LiveUpdate.pageTitleCache;
		}

		if (count > 0)
		{
			document.title = '(' + count + ') ' + pageTitle;
		}
		else
		{
			document.title = pageTitle;
		}
	};

	LiveUpdate.SetupNotificationAPI = function()
	{
		if (Notification.permission !== 'denied' && Notification.permission !== 'granted')
		{
			Notification.requestPermission(function (permission) {})
		}
	};

	LiveUpdate.Notification = null;

	LiveUpdate.SendNotification = function(message)
	{
		if (LiveUpdate.displayOptions.indexOf('notifications_api') === -1
			|| Notification.permission !== 'granted'
		)
		{
			return;
		}

		LiveUpdate.Notification = new Notification(LiveUpdate.boardTitle, {
			body: message,
			icon: XenForo.baseUrl() + LiveUpdate.iconPath
		});
	};

	$(document).ready(function()
	{
		LiveUpdate.SetupAutoPolling();
		LiveUpdate.SetupNotificationAPI();
	});
}
(jQuery, this, document);