<html>
<head>
	<meta http-equiv="Pragma" content="no-cache">
	<title><!--#slot src='title'--></title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel="stylesheet" type="text/css" href="<!--#slot src='theme_url'-->css/dialog.css">
	<script language="javascript" src="/scripts/htmleditor/utils.js"></script>

  <script language="javascript">
  <!--
  function Init() {
    var iProps = window.dialogArguments;
//    var protocols = new Array('http','ftp');

    if (iProps)
    {
      var i;
      if (iProps.href)
      {
        var poptions = img_prop.cprotocol.options;
        var S;
	      i = 1;
        S = iProps.href;
        while (i<poptions.length && S.indexOf(poptions[i].value)!=0) i++;
        if (i>=poptions.length)
        {
	        img_prop.chref.value = S;
	        img_prop.cprotocol.selectedIndex = 0;
        }
        else
        {
	        img_prop.chref.value = S.substr(poptions[i].value.length);
	        img_prop.cprotocol.selectedIndex = i;
        }
      }
      if (iProps.target)
      {
        var toptions = img_prop.ctarget.options;
        i = 0;
        while (i<toptions.length && toptions[i].value!=iProps.target) i++;

        if (i>=toptions.length) img_prop.ctarget.selectedIndex = 0;
        else img_prop.ctarget.selectedIndex = i;

      }
    }
    resizeDialogToContent();
  }

  function okClick() {
    // validate paramters
      var iProps = {};
      iProps.href = (img_prop.chref.value)?(img_prop.cprotocol.options[img_prop.cprotocol.selectedIndex].value+img_prop.chref.value):'';
      iProps.target = (img_prop.ctarget.value)?(img_prop.ctarget.value):'';
      window.returnValue = iProps;
      window.close();
  }

  function cancelClick() {
    window.close();
  }


  //-->
  </script>
</head>

<body onLoad="Init()" dir="ltr">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="img_prop">
<tr>
  <td><!--#slot src='protocol'-->:</td>
  <td colspan="3">
  	<select name="cprotocol">
      <option value="">внутренняя ссылка</option>
      <option value="http://">http</option>
      <option value="mailto:">mailto</option>
      <option value="ftp://">ftp</option>
    </select>
  </td>
</tr>
<tr>
  <td><!--#slot src='href'-->:</td>
  <td colspan="3"><input type="text" name="chref" class="input" size="24"></td>
</tr>
<tr>
  <td><!--#slot src='target'-->:</td>
  <td colspan="3">
  	<select name="ctarget">
      <option value="_self">текущее окно</option>
      <option value="_blank">новое окно</option>
      <option value="_parent">родительское окно</option>
      <option value="_search">панель поиска</option>
      <option value="_top">самое верхнее окно</option>
    </select>
  </td>
</tr>
<tr>
<td colspan="4" align="right" valign="bottom" nowrap>
<input type="button" value="<!--#slot src='ok'-->" onClick="okClick()" class="bt">
<input type="button" value="<!--#slot src='cancel'-->" onClick="cancelClick()" class="bt">
</td>
</tr>
</form>
</table>

</body>
</html>