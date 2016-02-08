var xfStackAlert=XenForo.stackAlert;XenForo.stackAlert=function(a,b,c){xfStackAlert(a,b,c);0==a instanceof jQuery&&(a=$("<span>"+a+"</span>"));LiveUpdate.SendNotification(a[0].innerText)};
