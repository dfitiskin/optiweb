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
    cur_color = window.dialogArguments;
    resizeDialogToContent();
  }

  function okClick() {
    window.returnValue = true;
    window.close();
  }

  function cancelClick() {
    window.returnValue = false;
    window.close();
  }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="ltr">

<p align="center">
<br>
<!--#slot src='message'-->
<br><br>
<form name="colorpicker">
<input type="button" value="<!--#slot src='ok'-->" onClick="okClick()" class="bt">
<input type="button" value="<!--#slot src='cancel'-->" onClick="cancelClick()" class="bt">
</form>
</p>

</body>
</html>