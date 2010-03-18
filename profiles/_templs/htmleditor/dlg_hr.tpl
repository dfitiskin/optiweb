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
      td_prop.ccolor.value = newcol;
      td_prop.color_sample.style.backgroundColor = td_prop.ccolor.value;
    }
    catch (excp) {}
  }

  function Init() {
    var cProps = window.dialogArguments;
    if (cProps)
    {
      // set attribute values
      td_prop.ccolor.value = cProps.color;
      td_prop.color_sample.style.backgroundColor = td_prop.ccolor.value;
      if (cProps.width) {
        if (!isNaN(cProps.width) || (cProps.width.substr(cProps.width.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(cProps.width))
            td_prop.cwidth.value = cProps.width;
          else
            td_prop.cwidth.value = cProps.width.substr(0,cProps.width.length-2);
          td_prop.cwunits.options[0].selected = false;
          td_prop.cwunits.options[1].selected = true;
        }
        else
        {
          // percents
          td_prop.cwidth.value = cProps.width.substr(0,cProps.width.length-1);
          td_prop.cwunits.options[0].selected = true;
          td_prop.cwunits.options[1].selected = false;
        }
      }

      if (cProps.size) {
        if (!isNaN(cProps.size) || (cProps.size.substr(cProps.size.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(cProps.size))
            td_prop.csize.value = cProps.size;
          else
            td_prop.csize.value = cProps.size.substr(0,cProps.size.length-2);
          td_prop.chunits.options[0].selected = false;
          td_prop.chunits.options[1].selected = true;
        }
        else
        {
          // percents
          td_prop.csize.value = cProps.size.substr(0,cProps.size.length-1);
          td_prop.chunits.options[0].selected = true;
          td_prop.chunits.options[1].selected = false;
        }
      }

      setHAlign(cProps.align);

      if (cProps.styleOptions) {
        for (i=1; i<cProps.styleOptions.length; i++)
        {
          var oOption = document.createElement("OPTION");
          td_prop.ccssclass.add(oOption);
          oOption.innerText = cProps.styleOptions[i].innerText;
          oOption.value = cProps.styleOptions[i].value;

          if (cProps.className) {
            td_prop.ccssclass.value = cProps.className;
          }
        }
      }
    }
    resizeDialogToContent();
  }

  function validateParams()
  {
    // check width and height
    if (isNaN(parseInt(td_prop.cwidth.value)) && td_prop.cwidth.value != '')
    {
      alert('<!--#slot src='error'-->:<!--#slot src='error_width_nan'-->');
      td_prop.cwidth.focus();
      return false;
    }
    if (isNaN(parseInt(td_prop.csize.value)) && td_prop.csize.value != '')
    {
      alert('<!--#slot src='error'-->:<!--#slot src='error_size_nan'-->');
      td_prop.csize.focus();
      return false;
    }

    return true;
  }

  function okClick() {
    // validate paramters
    if (validateParams())
    {
      var cprops = {};
      cprops.align = (td_prop.chalign.value)?(td_prop.chalign.value):'';
      cprops.width = (td_prop.cwidth.value)?(td_prop.cwidth.value + td_prop.cwunits.value):'';
      cprops.size = (td_prop.csize.value)?(td_prop.csize.value + td_prop.chunits.value):'';
      cprops.color = td_prop.ccolor.value;
      cprops.className = (td_prop.ccssclass.value != 'default')?td_prop.ccssclass.value:'';

      window.returnValue = cprops;
      window.close();
    }
  }

  function cancelClick() {
    window.close();
  }

  function setSample()
  {
    try {
      td_prop.color_sample.style.backgroundColor = td_prop.ccolor.value;
    }
    catch (excp) {}
  }

  function setHAlign(alignment)
  {
    switch (alignment) {
      case "left":
        td_prop.ha_left.className = "align_on";
        td_prop.ha_center.className = "align_off";
        td_prop.ha_right.className = "align_off";
        break;
      case "center":
        td_prop.ha_left.className = "align_off";
        td_prop.ha_center.className = "align_on";
        td_prop.ha_right.className = "align_off";
        break;
      case "right":
        td_prop.ha_left.className = "align_off";
        td_prop.ha_center.className = "align_off";
        td_prop.ha_right.className = "align_on";
        break;
    }
    td_prop.chalign.value = alignment;
  }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="ltr">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="td_prop">
<tr>
  <td colspan="2"><!--#slot src='align'-->:</td>
  <td colspan="2" align="right"><input type="hidden" name="chalign">
  <img id="ha_left" src="<!--#slot src='theme_url'-->img/tb_left.gif" class="align_off" onClick="setHAlign('left');" alt="<!--#slot src='left'-->">
  <img id="ha_center" src="<!--#slot src='theme_url'-->img/tb_center.gif" class="align_off" onClick="setHAlign('center');" alt="<!--#slot src='center'-->">
  <img id="ha_right" src="<!--#slot src='theme_url'-->img/tb_right.gif" class="align_off" onClick="setHAlign('right');" alt="<!--#slot src='right'-->">
  </td>
</tr>
<tr>
  <td><!--#slot src='width'-->:</td>
  <td nowrap>
    <input type="text" name="cwidth" size="3" maxlenght="3" class="input_small">
    <select size="1" name="cwunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
  <td><!--#slot src='size'-->:</td>
  <td nowrap>
    <input type="text" name="csize" size="3" maxlenght="3" class="input_small">
    <select size="1" name="chunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
</tr>
<tr>
  <td nowrap><!--#slot src='css_class'-->:</td>
  <td nowrap colspan="3">
    <select id="ccssclass" name="ccssclass" size="1" class="input">
    </select>
  </td>
</tr>
<tr>
  <td colspan="4"><!--#slot src='color'-->: <img src="/data/htmleditor/dialog/spacer.gif" id="color_sample" border="1" width="30" height="18" align="absbottom">&nbsp;<input type="text" name="ccolor" size="7" maxlenght="7" class="input_color" onKeyUp="setSample()">&nbsp;
  <img src="<!--#slot src='theme_url'-->img/tb_colorpicker.gif" border="0" onClick="showColorPicker(ccolor.value)" align="absbottom">
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