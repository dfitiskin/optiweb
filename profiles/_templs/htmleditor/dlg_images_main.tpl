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
    resizeDialogToContent();
  }

  function okClick() {
    // validate paramters
      var iProps = {};

      //iProps.src = filetree.document.all.preview.src
      iProps.src = document.all.src.value;
//      (img_prop.chref.value)?(img_prop.cprotocol.options[img_prop.cprotocol.selectedIndex].value+img_prop.chref.value):'';
//      iProps.target = (img_prop.ctarget.value)?(img_prop.ctarget.value):'';
      iProps.align = '';
      iProps.width = '';
      iProps.height = '';
      iProps.border = '';
//      iProps.src = '';
      iProps.alt = '';
      iProps.hspace = '';
      iProps.vspace = '';

      //window.returnValue = iProps;
      window.returnValue = filetree.document.all.preview.src
      window.close();
  }

  function cancelClick() {
    window.close();
  }


  //-->
</script>

</head>

<body id='123' onLoad="Init()">
<input type='hidden' id='src'>
<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
<tr height="99%">
    <td width="100%"><iframe id='filetree' height="100%" width="100%" src='<!--#slot src="frame_url"-->' ></iframe></td>
</tr>
<tr height="1%">
    <td width="100%" valign="top" align="center">
	<input type="button" value="<!--#slot src='select'-->" onClick="okClick()" class="bt">
	<input type="button" value="<!--#slot src='cancel'-->" onClick="cancelClick()" class="bt">
    </td>
</tr>
</table>
</body>
</html>