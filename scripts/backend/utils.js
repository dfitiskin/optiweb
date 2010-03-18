function getElement(id)
{
	if (document.getElementById)
		return document.getElementById(id);
	else if (document.all)
		return document.all[id];
	else return null;
}

function setCookie(name, value) {
	var expires=60*60*24;
	var path="/";
	var todaydate=new Date();
//        var domain=".web";

	var expdate=new Date(todaydate.getTime()+expires*1000);	
	var curCookie = name + "=" + escape(value) +
                ((path) ? "; path=" + path : "");
	return curCookie;
}

              /*
function setAttribute(id,value)
{
	var Elem = getElement(id);
	Elem
}           */

  function resizeDialogToContent()
  {
    // resize window so there are no scrollbars visible
    var dw = window.dialogWidth;
    while (isNaN(dw))
    {
      dw = dw.substr(0,dw.length-1);
    }
    difw = dw - this.document.body.clientWidth;
    window.dialogWidth = this.document.body.scrollWidth+difw+'px';

    var dh = window.dialogHeight;
    while (isNaN(dh))
    {
      dh = dh.substr(0,dh.length-1);
    }
    difh = dh - this.document.body.clientHeight;
    window.dialogHeight = this.document.body.scrollHeight+difh+'px';
  }
  
