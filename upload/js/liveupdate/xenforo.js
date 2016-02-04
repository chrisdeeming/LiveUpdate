var xfStackAlert = XenForo.stackAlert;

XenForo.stackAlert = function(message, timeOut, $balloon) {
    xfStackAlert(message, timeOut, $balloon);

    if ((message instanceof jQuery) == false)
    {
        message = $('<span>' + message + '</span>');
    }

    LiveUpdate.SendNotification(message[0].innerText);
}