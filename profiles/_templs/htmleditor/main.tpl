<!--
<html>
<body>
//-->

<script language='JavaScript'>
<!--
var optiweb_active_toolbar = true;
//-->

</script>

<script language="JavaScript" src="/scripts/htmleditor/script.js"></script>

<script language="JavaScript" src="<!--#slot src='theme_url'-->js/toolbar.js"></script>
<link rel="stylesheet" type="text/css" href="<!--#slot src='theme_url'-->css/toolbar.css">

<table border="0" cellspacing="0" cellpadding="0" height="<!--#slot src='table_height'-->" width="<!--#slot src='table_width'-->">
<tr>

	<td id="optiweb_<!--#slot src='editor_name'-->_toolbar_top_design" class="optiweb_<!--#slot src='theme'-->_toolbar" colspan="3">
	    <!--#slot src='top_design' link='htoolbars'-->
	</td>
	<td id="optiweb_<!--#slot src='editor_name'-->_toolbar_top_html" class="optiweb_<!--#slot src='theme'-->_toolbar" colspan="3" style="display : none;">
    	<!--#slot src='top_html' link='htoolbars'-->
    </td>
</tr>
<tr>
    	<td id="optiweb_<!--#slot src='editor_name'-->_toolbar_left_design" valign="top" class="optiweb_<!--#slot src='theme'-->_toolbar" >
			<!--#slot src='left_design' link='vtoolbars'-->
        </td>
        <td id="optiweb_<!--#slot src='editor_name'-->_toolbar_left_html" valign="top" class="optiweb_<!--#slot src='theme'-->_toolbar" style="display : none;">
			<!--#slot src='left_html' link='vtoolbars'-->
        </td>
        <td align="left" valign="top" height="100%" width="100%">
            <textarea id="<!--#slot src='editor_name'-->" name="<!--#slot src='editor_name'-->" style="width:<!--#slot src='editor_width'-->; height:<!--#slot src='editor_height'-->; display:none; border:0px;" class="optiweb_<!--#slot src='theme'-->_editarea"><!--#slot src='htmldoc' filter='sts;addbaseurl'--></textarea>
            <input type="hidden" id="optiweb_<!--#slot src='editor_name'-->_editor_mode" name="optiweb_<!--#slot src='editor_name'-->_editor_mode" value="design">
            <input type="hidden" id="optiweb_<!--#slot src='editor_name'-->_lang" value="<!--#slot src='lang'-->">
            <input type="hidden" id="optiweb_<!--#slot src='editor_name'-->_theme" value="<!--#slot src='theme'-->">
            <input type="hidden" id="optiweb_<!--#slot src='editor_name'-->_borders" value="on">
            <input type="hidden" id="optiweb_<!--#slot src='editor_name'-->_imagelib" value="<!--#slot src='imagelib'-->">
            <OBJECT CLASS="<!--#slot src='editor_name'-->_rEdit" classid=clsid:2D360201-FFF5-11d1-8D03-00A0C959BC0A ID='<!--#slot src='editor_name'-->_rEdit' WIDTH="<!--#slot src='editor_width'-->" HEIGHT="<!--#slot src='editor_height'-->"  style="width:<!--#slot src='editor_width'-->; height:<!--#slot src='editor_height'--> class="optiweb_<!--#slot src='theme'-->_editarea" frameborder="no" style="direction : " VIEWASTEXT><PARAM NAME="ScrollbarAppearance" VALUE="0"></OBJECT>
            <br>


<!--
              <SCRIPT language='JavaScript'>

function optiweb_<!--#slot src='editor_name'-->_ShowMenu(){
   ContextMenu = new Array();
	  var MenuStrings = new Array();
	  var MenuStates = new Array();
   genID=0;
   var default_items= [new ContextMenuItem('Копировать',DECMD_COPY),new ContextMenuItem('Вырезать',DECMD_CUT),new ContextMenuItem('Вставить',DECMD_PASTE)];
   g=0;   for (i=0;i<default_items.length;i++){
		state=document.all['<!--#slot src='editor_name'-->_rEdit'].QueryStatus(default_items[i].cmdId); 	if (state!=DECMDF_DISABLED){
   		ContextMenu[genID++]=default_items[i];
     	g++;
}
} 	if (g)
   		ContextMenu[genID++]=new ContextMenuItem('',0);
   for (i=0;i<ContextMenu.length-1;i++){
      MenuStrings[i]=ContextMenu[i].string;
      if (ContextMenu[i].cmdId==0) {
         MenuStates[i]=OLE_TRISTATE_GRAY;
      } else {
         state=document.all['<!--#slot src='editor_name'-->_rEdit'].QueryStatus(ContextMenu[i].cmdId);
 	    if (state==DECMDF_LATCHED)
 	        MenuStates[i]=OLE_TRISTATE_CHECKED;
 	    else if (state==DECMDF_ENABLED)
 	        MenuStates[i]=OLE_TRISTATE_UNCHECKED;
 	    else MenuStates[i]=OLE_TRISTATE_GRAY;
      }
   }
   document.all['<!--#slot src='editor_name'-->_rEdit'].SetContextMenu(MenuStrings, MenuStates);
}</SCRIPT>
<SCRIPT event=ShowContextMenu for="<!--#slot src='editor_name'-->_rEdit"language=javascript>return optiweb_<!--#slot src='editor_name'-->_ShowMenu();</SCRIPT>
<SCRIPT event=ContextMenuAction(itemIndex) for="<!--#slot src='editor_name'-->_rEdit"language=javascript>optiweb_MenuAction('<!--#slot src='editor_name'-->',itemIndex);</SCRIPT>
-->

<SCRIPT event=DocumentComplete for="<!--#slot src='editor_name'-->_rEdit" language=javascript>optiweb_editorChangeStyle('<!--#slot src='editor_name'-->','/wysiwyg.css','ltr');</SCRIPT>
<SCRIPT event=onkeyup for="<!--#slot src='editor_name'-->_rEdit"language=javascript>optiweb_onkeyup('<!--#slot src='editor_name'-->');</SCRIPT>
<SCRIPT event=onmouseup for="<!--#slot src='editor_name'-->_rEdit"language=javascript>optiweb_update_toolbar('<!--#slot src='editor_name'-->', true);</SCRIPT>



	    </td>
	    <td id="optiweb_<!--#slot src='editor_name'-->_toolbar_right_design" valign="top" class="optiweb_<!--#slot src='theme'-->_toolbar">
        	<!--#slot src='right_design' link='vtoolbars'-->
         </td>
	    <td id="optiweb_<!--#slot src='editor_name'-->_toolbar_right_html" valign="top" class="optiweb_<!--#slot src='theme'-->_toolbar" style="display : none;">
        			<!--#slot src='right_html' link='vtoolbars'-->
        </td>
    </tr>
    <tr>
    	<td class="optiweb_<!--#slot src='theme'-->_toolbar"></td>
        <td id="optiweb_<!--#slot src='editor_name'-->_toolbar_bottom_design" class="optiweb_<!--#slot src='theme'-->_toolbar" width="100%">
        	<!--#slot src='bottom_design' link='htoolbars'-->
        </td>
        <td id="optiweb_<!--#slot src='editor_name'-->_toolbar_bottom_html" class="optiweb_<!--#slot src='theme'-->_toolbar" width="100%" style="display : none;">
        	<!--#slot src='bottom_html' link='htoolbars'-->
        </td>
        <td class="optiweb_<!--#slot src='theme'-->_toolbar"></td>
    </tr>
</table>  


<!--
</body>
</html>
-->

<!--#partsep-->

<!--#list name='htoolbars'-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<!--#elem-->
<tr>
    <td align="<!--#slot src='align'-->" valign="<!--#slot src='valign'-->" class="optiweb_<!--#slot src='theme'-->_toolbar_<!--#slot src='pos'-->">
		<!--#slot src='button_groups' link='buttons'-->
    </td>
</tr>
<!--#endelem-->
</table>
<!--#endlist-->


<!--#list name='buttons' func='button_type'-->
<!--#sep-->
<!--#cond-->
return $_ds->getParam('separate');
<!--#endcond-->
<img id="optiweb_<!--#slot src='editor_name'-->_tb_vertical_separator" alt="" src="<!--#slot src='theme_url'-->img/tb_vertical_separator.gif"  unselectable="on">
<!--#endsep-->
<!--#elem-->
<img id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" alt="<!--#slot src='label'-->" src="<!--#slot src='theme_url'-->img/tb_<!--#slot src='name'-->.gif" onClick="optiweb_<!--#slot src='name'-->_click('<!--#slot src='editor_name'-->',this)" class="optiweb_<!--#slot src='theme'-->_tb_out" onMouseOver="optiweb_<!--#slot src='theme'-->_bt_over(this)" onMouseOut="optiweb_<!--#slot src='theme'-->_bt_out(this)" onMouseDown="optiweb_<!--#slot src='theme'-->_bt_down(this)" onMouseUp="optiweb_<!--#slot src='theme'-->_bt_up(this)" unselectable="on">
<!--#endelem-->
<!--#elem-->
<img id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" alt="<!--#slot src='label'-->" src="<!--#slot src='theme_url'-->img/tb_<!--#slot src='name'-->.gif" unselectable="on">
<!--#endelem-->
<!--#elem-->
	            <select size="1" id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" name="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" align="absmiddle" class="optiweb_<!--#slot src='theme'-->_tb_input" onchange="optiweb_<!--#slot src='name'-->_change('<!--#slot src='editor_name'-->',this)" >
                <option><!--#slot src='label'--></option>
				<!--#slot src='dropdown' link='dropdown'-->
                </select>
<!--#endelem-->
<!--#endlist-->


<!--#list name='vtoolbars'-->
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<!--#elem-->
<td align="<!--#slot src='align'-->" valign="<!--#slot src='valign'-->" class="optiweb_<!--#slot src='theme'-->_toolbar_<!--#slot src='pos'-->">
<!--#slot src='button_groups' link='vbuttons'-->
</td>
<!--#endelem-->
</tr>
</table>
<!--#endlist-->

<!--#list name='vbuttons' func='button_type'-->
<table border=0 cellspacing=0 cellpadding =0>
<!--#sep-->
<!--#cond-->
return $_ds->getParam('separate');
<!--#endcond-->
<tr>
	<td>
	<img id="optiweb_<!--#slot src='editor_name'-->_tb_vertical_separator" alt="" src="<!--#slot src='theme_url'-->img/tb_vertical_separator.gif"  unselectable="on">
	</td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
	<td>
	<img height="24" width="24" id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" alt="<!--#slot src='label'-->" src="<!--#slot src='theme_url'-->img/tb_<!--#slot src='name'-->.gif" onClick="optiweb_<!--#slot src='name'-->_click('<!--#slot src='editor_name'-->',this)" class="optiweb_<!--#slot src='theme'-->_tb_out" onMouseOver="optiweb_<!--#slot src='theme'-->_bt_over(this)" onMouseOut="optiweb_<!--#slot src='theme'-->_bt_out(this)" onMouseDown="optiweb_<!--#slot src='theme'-->_bt_down(this)" onMouseUp="optiweb_<!--#slot src='theme'-->_bt_up(this)" unselectable="on">
	</td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
	<td>
    <img height="24" width="24" id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" alt="<!--#slot src='label'-->" src="<!--#slot src='theme_url'-->img/tb_<!--#slot src='name'-->.gif" unselectable="on">
	</td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
	<td>
        <select size="1" id="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" name="optiweb_<!--#slot src='editor_name'-->_tb_<!--#slot src='name'-->" align="absmiddle" class="optiweb_<!--#slot src='theme'-->_tb_input" onchange="optiweb_<!--#slot src='name'-->_change('<!--#slot src='editor_name'-->',this)" >
        <option><!--#slot src='label'--></option>
        <!--#slot src='dropdown' link='dropdown'-->
        </select>
	</td>
</tr>
<!--#endelem-->
</table>
<!--#endlist-->


<!--#list name='dropdown'-->
<!--#elem-->
<option value="<!--#slot src='value'-->"><!--#slot src='name'--></option>
<!--#endelem-->
<!--#endlist-->



<!--#func name='button_type'-->
return $_ds->getParam('type')-1;
<!--#endfunc-->