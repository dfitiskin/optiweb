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
    if (iProps)
    {
      // set attribute values
      if (iProps.width) {
        img_prop.cwidth.value = iProps.width;
      }
      if (iProps.height) {
        img_prop.cheight.value = iProps.height;
      }

      setAlign(iProps.align);

      if (iProps.src) {
        img_prop.csrc.value = iProps.src;
      }
      if (iProps.alt) {
        img_prop.calt.value = iProps.alt;
      }
      if (iProps.border) {
        img_prop.cborder.value = iProps.border;
      }
      if (iProps.hspace) {
        img_prop.chspace.value = iProps.hspace;
      }
      if (iProps.vspace) {
        img_prop.cvspace.value = iProps.vspace;
      }
    }
    resizeDialogToContent();
  }

  function validateParams()
  {
    // check width and height
    if (isNaN(parseInt(img_prop.cwidth.value)) && img_prop.cwidth.value != '')
    {
      alert('<!--#slot src='error'-->: <!--#slot src='error_width_nan'-->');
      img_prop.cwidth.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cheight.value)) && img_prop.cheight.value != '')
    {
      alert('<!--#slot src='error'-->: <!--#slot src='error_height_nan'-->');
      img_prop.cheight.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cborder.value)) && img_prop.cborder.value != '')
    {
      alert('<!--#slot src='error'-->: <!--#slot src='error_border_nan'-->');
      img_prop.cborder.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.chspace.value)) && img_prop.chspace.value != '')
    {
      alert('<!--#slot src='error'-->: <!--#slot src='error_hspace_nan'-->');
      img_prop.chspace.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cvspace.value)) && img_prop.cvspace.value != '')
    {
      alert('<!--#slot src='error'-->: <!--#slot src='error_vspace_nan'-->');
      img_prop.cvspace.focus();
      return false;
    }

    return true;
  }

  function okClick() {
    // validate paramters
    if (validateParams())
    {             
      var iProps = {};
      iProps.align = (img_prop.calign.value)?(img_prop.calign.value):'';
      iProps.width = (img_prop.cwidth.value)?(img_prop.cwidth.value):'';
      iProps.height = (img_prop.cheight.value)?(img_prop.cheight.value):'';
      iProps.border = (img_prop.cborder.value)?(img_prop.cborder.value):'';
      iProps.src = (img_prop.csrc.value)?(img_prop.csrc.value):'';
      iProps.alt = (img_prop.calt.value)?(img_prop.calt.value):'';
      iProps.hspace = (img_prop.chspace.value)?(img_prop.chspace.value):'';
      iProps.vspace = (img_prop.cvspace.value)?(img_prop.cvspace.value):'';

      window.returnValue = iProps;
      window.close();
    }
  }

  function cancelClick() {
    window.close();
  }


  function setAlign(alignment)
  {
    for (i=0; i<img_prop.calign.options.length; i++)
    {
      al = img_prop.calign.options.item(i);
      if (al.value == alignment.toLowerCase()) {
        img_prop.calign.selectedIndex = al.index;
      }
    }
  }

  //-->
  </script>
</head>

<body onLoad="Init()" dir="ltr">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="img_prop">
<tr>
  <td><!--#slot src='source'-->:</td>
  <td colspan="3"><input type="text" name="csrc" class="input" size="32"></td>
</tr>
<tr>
  <td><!--#slot src='alt'-->:</td>
  <td colspan="3"><input type="text" name="calt" class="input" size="32"></td>
</tr>
<tr>
  <td><!--#slot src='align'-->:</td>
  <td align="left">
  <select name="calign" size="1" class="input">
    <option value=""></option>
    <option value="left"><!--#slot src='left'--></option>
    <option value="right"><!--#slot src='right'--></option>
    <option value="top"><!--#slot src='top'--></option>
    <option value="middle"><!--#slot src='middle'--></option>
    <option value="bottom"><!--#slot src='bottom'--></option>
    <option value="absmiddle"><!--#slot src='absmiddle'--></option>
    <option value="texttop"><!--#slot src='texttop'--></option>
    <option value="baseline"><!--#slot src='baseline'--></option>
  </select>
  </td>
  <td><!--#slot src='border'-->:</td>
  <td align="left"><input type="text" name="cborder" class="input_small"></td>
</tr>
<tr>
  <td><!--#slot src='width'-->:</td>
  <td nowrap>
    <input type="text" name="cwidth" size="3" maxlenght="3" class="input_small">
  </td>
  <td><!--#slot src='height'-->:</td>
  <td nowrap>
    <input type="text" name="cheight" size="3" maxlenght="3" class="input_small">
  </td>
</tr>
<tr>
  <td><!--#slot src='hspace'-->:</td>
  <td nowrap>
    <input type="text" name="chspace" size="3" maxlenght="3" class="input_small">
  </td>
  <td><!--#slot src='vspace'-->:</td>
  <td nowrap>
    <input type="text" name="cvspace" size="3" maxlenght="3" class="input_small">
  </td>
</tr>
<tr>
<td colspan="4" nowrap>
<hr width="100%">
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