<html>
<head>
	<meta http-equiv="Pragma" content="no-cache">
  <title><!--#slot src='title'--></title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <link rel="stylesheet" type="text/css" href="<!--#slot src='theme_url'-->css/dialog.css">
  <script language="javascript" src="/scripts/htmleditor/utils.js"></script>

  <script language="javascript">
  <!--
  function showColorPicker(curcolor) {
    var newcol = showModalDialog('/_dialog/htmleditor/colorpicker/<!--#slot src='lang'-->/<!--#slot src='theme'-->/', curcolor,
      'dialogHeight:250px; dialogWidth:366px; resizable:no; status:no');
    try {
      table_prop.tbgcolor.value = newcol;
      table_prop.color_sample.style.backgroundColor = table_prop.tbgcolor.value;
    }
    catch (excp) {}
  }

  function Init() {
    var tProps = window.dialogArguments;
    if (tProps)
    {
      // set attribute values
      table_prop.trows.value = '3';
      table_prop.trows.disabled = true;
      table_prop.tcols.value = '3';
      table_prop.tcols.disabled = true;

      table_prop.tborder.value = tProps.border;
      table_prop.tcpad.value = tProps.cellPadding;
      table_prop.tcspc.value = tProps.cellSpacing;
      table_prop.tbgcolor.value = tProps.bgColor;
      table_prop.color_sample.style.backgroundColor = table_prop.tbgcolor.value;
      if (tProps.width) {
        if (!isNaN(tProps.width) || (tProps.width.substr(tProps.width.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(tProps.width))
            table_prop.twidth.value = tProps.width;
          else
            table_prop.twidth.value = tProps.width.substr(0,tProps.width.length-2);
          table_prop.twunits.options[0].selected = false;
          table_prop.twunits.options[1].selected = true;
        }
        else
        {
          // percents
          table_prop.twidth.value = tProps.width.substr(0,tProps.width.length-1);
          table_prop.twunits.options[0].selected = true;
          table_prop.twunits.options[1].selected = false;
        }
      }
      if (tProps.width) {
        if (!isNaN(tProps.height) || (tProps.height.substr(tProps.height.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(tProps.height))
            table_prop.theight.value = tProps.height;
          else
            table_prop.theight.value = tProps.height.substr(0,tProps.height.length-2);
          table_prop.thunits.options[0].selected = false;
          table_prop.thunits.options[1].selected = true;
        }
        else
        {
          // percents
          table_prop.theight.value = tProps.height.substr(0,tProps.height.length-1);
          table_prop.thunits.options[0].selected = true;
          table_prop.thunits.options[1].selected = false;
        }
      }
    }
    else
    {
      // set default values
      table_prop.trows.value = '3';
      table_prop.tcols.value = '3';
      table_prop.tborder.value = '1';
    }
    resizeDialogToContent();
  }

  function validateParams()
  {
    // check whether rows and cols are integers
    if (isNaN(parseInt(table_prop.trows.value)))
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_rows_nan'-->');
      table_prop.trows.focus();
      return false;
    }
    if (isNaN(parseInt(table_prop.tcols.value)))
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_columns_nan'-->');
      table_prop.tcols.focus();
      return false;
    }
    // check width and height
    if (isNaN(parseInt(table_prop.twidth.value)) && table_prop.twidth.value != '')
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_width_nan'-->');
      table_prop.twidth.focus();
      return false;
    }
    if (isNaN(parseInt(table_prop.theight.value)) && table_prop.theight.value != '')
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_height_nan'-->');
      table_prop.theight.focus();
      return false;
    }
    // check border, padding and spacing
    if (isNaN(parseInt(table_prop.tborder.value)) && table_prop.tborder.value != '')
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_border_nan'-->');
      table_prop.tborder.focus();
      return false;
    }
    if (isNaN(parseInt(table_prop.tcpad.value)) && table_prop.tcpad.value != '')
    {
      alert('<!--#slot src='error'--> : <!--#slot src='error_cellpadding_nan'-->');
      table_prop.tcpad.focus();
      return false;
    }
    if (isNaN(parseInt(table_prop.tcspc.value)) && table_prop.tcspc.value != '')
    {
    	alert('<!--#slot src='error'--> : <!--#slot src='error_cellspacing_nan'-->');
      table_prop.tcspc.focus();
      return false;
    }

    return true;
  }

  function okClick() {
    // validate paramters
    if (validateParams())
    {
      var newtable = {};
      newtable.width = (table_prop.twidth.value)?(table_prop.twidth.value + table_prop.twunits.value):'';
      newtable.height = (table_prop.theight.value)?(table_prop.theight.value + table_prop.thunits.value):'';
      newtable.border = table_prop.tborder.value;
      newtable.cols = table_prop.tcols.value;
      newtable.rows = table_prop.trows.value
      newtable.cellPadding = table_prop.tcpad.value;
      newtable.cellSpacing = table_prop.tcspc.value;
      newtable.bgColor = table_prop.tbgcolor.value;

      window.returnValue = newtable;
      window.close();
    }
  }

  function cancelClick() {
    window.close();
  }

  function setSample()
  {
    try {
      table_prop.color_sample.style.backgroundColor = table_prop.tbgcolor.value;
    }
    catch (excp) {}
  }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="ltr">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="table_prop">
<tr>
  <td><!--#slot src='rows'-->:</td>
  <td><input type="text" name="trows" size="3" maxlenght="3" class="input_small"></td>
  <td><!--#slot src='columns'-->:</td>
  <td><input type="text" name="tcols" size="3" maxlenght="3" class="input_small"></td>
</tr>
<tr>
  <td><!--#slot src='width'-->:</td>
  <td nowrap>
    <input type="text" name="twidth" size="3" maxlenght="3" class="input_small">
    <select size="1" name="twunits" class="input_small">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
  <td><!--#slot src='height'-->:</td>
  <td nowrap>
    <input type="text" name="theight" size="3" maxlenght="3" class="input_small">
    <select size="1" name="thunits" class="input_small">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
</tr>
<tr>
  <td><!--#slot src='border'-->:</td>
  <td colspan="3"><input type="text" name="tborder" size="2" maxlenght="2" class="input_small"><!--#slot src='pixels'--></td>
</tr>
<tr>
  <td><!--#slot src='cellpadding'-->:</td>
  <td><input type="text" name="tcpad" size="3" maxlenght="3" class="input_small"></td>
  <td><!--#slot src='cellspacing'-->:</td>
  <td><input type="text" name="tcspc" size="3" maxlenght="3" class="input_small"></td>
</tr>

<tr>
  <td colspan="4"><!--#slot src='bg_color'-->: <img src="/data/htmleditor/dialog/spacer.gif" id="color_sample" border="1" width="30" height="18" align="absbottom">&nbsp;
  <input type="text" name="tbgcolor" size="7" maxlenght="7" class="input_color" onKeyUp="setSample()">&nbsp;
  <img src="<!--#slot src='theme_url'-->img/tb_colorpicker.gif" border="0" onClick="showColorPicker(tbgcolor.value)" align="absbottom">
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