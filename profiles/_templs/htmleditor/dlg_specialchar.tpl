<html>
<head>
<style type="text/css">
	BODY {
		background-color: buttonface;
	}
	TD, INPUT {
		font-family: "Sans Serif";
		font-size: x-small;
		vertical-align: middle;
		cursor: hand;
	}
	TD.HOVER{
		background-color : Fuchsia;
	}


	.dlg TD {
		align: left;
		height: 20;

	}

	.dlg INPUT {
		border-top: 1px solid white;
		border-left: 1px solid white;
		border-bottom: 1px solid black;
		border-right: 1px solid black;
		font-size: x-small;
		width: 60; }
</style>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!--
var chars = ["!","&quot;","#","$","%","&","'","(",")","*","+","-",".","/","0","1","2","3","4","5","6","7","8","9",":",";","&lt;","=","&gt;","?","@","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","[","]","^","_","`","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","{","|","}","~","&euro;","�","�","�","�","�","�","\�","�","�","�","&lsquo;","&rsquo;","&rsquo;","&ldquo;","&rdquo;","�","&ndash;","&mdash;","�","�","�","�","�","�","&iexcl;","&cent;","&pound;","&pound;","&curren;","&yen;","&brvbar;","&sect;","&uml;","&copy;","&ordf;","&laquo;","&not;","�","&reg;","&macr;","&deg;","&plusmn;","&sup2;","&sup3;","&acute;","&micro;","&para;","&middot;","&cedil;","&sup1;","&ordm;","&raquo;","&frac14;","&frac12;","&frac34;","&iquest;","&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&times;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&uuml;","&yacute;","&thorn;","&yuml;"]

function tab(w,h) {
	var strtab = ["<TABLE border='1' cellspacing='0' cellpadding='0' align='center' bordercolor='#dcdcdc' bgcolor='#C0C0C0'>"]
	var k = 0;
	for(var i = 0; i < w; i++) {
		strtab[strtab.length] = "<TR>";
		for(var j = 0; j < h; j++) {
			strtab[strtab.length] = "<TD width='14' align='center' onClick='getchar(this)' onMouseOver='hover(this,true)' onMouseOut='hover(this,false)'>"+(chars[k]||'')+"</TD>";
			k++;
		}
		strtab[strtab.length]="</TR>";
	}
	strtab[strtab.length] = "</TABLE>";
	return strtab.join("\n");
}

function hover(obj,val) {
	if (!obj.innerHTML) {
		obj.style.cursor = "default";
		return;
	}
	obj.style.border = val ? "1px solid black" : "1px solid #dcdcdc";
	//obj.style.backgroundColor = val ? "black" : "#C0C0C0"
	//obj.style.color = val ? "white" : "black";
}
function getchar(obj) {
	if(!obj.innerHTML) return;
	window.returnValue = obj.innerHTML || "";
	window.close();
}
function cancel() {
	window.returnValue = null;
	window.close();
}
//-->
</SCRIPT>

	<title><!--#slot src='title'--></title>
</head>

<body>
<table class="dlg" cellpadding="0" cellspacing="2" width="100%" height="100%">
<tr><td><table width="100%"><tr><td nowrap><!--#slot src='title'--></td><td valign="middle" width="100%"><hr width="100%"></td></tr></table></td></tr>
<tr>
<td>
    <table border="0" align="center" cellpadding="5">
      <tr valign="top">
       <td>

       <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
       <!--
          document.write(tab(7,32))
       //-->
       </SCRIPT>

       </td>
      </tr>
    </table>

    </td>
  </tr>
<tr><td><table width="100%"><tr><td valign="middle" width="90%"><hr width="100%"></td></tr></table></td></tr>
<tr><td align="right">
	<input type="button" value="<!--#slot src='close'-->" onclick="cancel()"></td></tr>
</table>


</body>
</html>