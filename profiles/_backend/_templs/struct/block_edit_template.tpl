<form method='post'>
	<input type='hidden' name='object' value='struct'>
	<input type='hidden' name='mode' value='block_edit_template'>
	<input type='hidden' name='action' value= 'upd'>
	<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
	<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>

	<table width="95%" align="center" border="0" height="93%" cellspacing="0" cellpadding="0" style="margin-top: 20px">
		<tr style="height: 35px">
			<td><h2>Редактирование блока <b><!--#slot src='name'--></b></h2></td>
		</tr>
		<tr style="height: 17px;vertical-align: top">
    		<td>Описание: </td>
    	</tr>
    	<tr style="height: 25px;vertical-align: top">
    		<td><input class="inn" style="width:100%" name='descr' value='<!--#slot src='descr'-->'></td>
		</tr>
		<tr style="height: 17px;vertical-align: top">
    		<td>Шаблон:</td>
    	</tr>
    	<tr style="height: 25px;vertical-align: top">
    		<td>
				<table border="0" cellspacing="0" cellpadding="0">
	  				<tr>
	    				<td><!--#slot src='avtempls' link='templs_avail'--></td>
	    				<td><!--#slot src='_switch' link='edit_template'--></td>
	  				</tr>
				</table>  	
    		</td>
    	</tr>	
		<tr>
			<td valign="top">
				<!--#slot src='slots' link='slots'-->
			</td>
		</tr>
		<tr>
    		<td>
      			<p style="margin: 7 0 7 0" align=center>
      				<input type='image' alt="Обновить" src="/images/__backend/common/but-refresh.gif">
      				&nbsp;&nbsp;&nbsp;
      				<img type='image' style="cursor:hand" onclick="window.close()" alt="Закрыть" src="/images/__backend/common/but-close.gif">
      			</p>
    		</td>
		</tr>
	</table>
</form>
<!--#partsep-->


//------------------------------------------------------------------------------
// Список доступных шаблонов
//------------------------------------------------------------------------------
<!--#list name='templs_avail'-->
<select name='templ'>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('filename') == $_ds->getParam('templ');
<!--#endcond-->
<option value = "<!--#slot src='filename'-->" SELECTED><!--#slot src='descript'--> (<!--#slot src='filename'-->)
<!--#endelem-->
<!--#elem-->
<option value = "<!--#slot src='filename'-->" ><!--#slot src='descript'--> (<!--#slot src='filename'-->)
<!--#endelem-->
</select>
<!--#endlist-->


//------------------------------------------------------------------------------
// Редактирование шаблона
//------------------------------------------------------------------------------
<!--#list name='edit_template'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('template') && $_ds->GetParam('template_lib');
<!--#endcond-->
<table><tr><td>
<img style="cursor:hand" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='this_tpl_lib'-->/<!--#slot src='template'-->/','second','width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
</td><td>
<p style="color:blue; text-decoration:underline; cursor:hand" onmouseover="style.color='red'" onmouseout="style.color='blue'" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='this_tpl_lib'-->/<!--#slot src='template'-->/','second','width=600,height=500,menubar=0,scrollbars=1,resizable=1')">Редактировать</p>
</td></tr></table>
<!--#endelem-->
<!--#elem-->
<table><tr><td>
<img src="/images/__backend/struct/edit2.gif" width="16" height="14">
</td><td>
<p style="color:#CCCCCC">Редактировать</p>
</td></tr></table>
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Список слотов
//------------------------------------------------------------------------------
<!--#list name='slots'-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:20 0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Слоты:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">


<table border=0 width="100%">
<!--#sep-->
<tr>
	<td style="padding:0;height: 10px" colspan="2"></td>
</tr>
<tr>
	<td bgcolor="#AAAAAA" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
	<td style="padding:0;height: 10px" colspan="2"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 's';
<!--#endcond-->
<tr>
	<td align="right" style="padding-right: 10px;">Название:</td>
	<td><b><!--#slot src='name'--></b></td>
</tr>
<tr>
	<td align="right" style="padding-right: 10px;">Описание:</td>
	<td><input class="inn" name = "upd[<!--#slot src='name'-->][descr]" value="<!--#slot src='descr'-->"></td>
<tr>
	<td align="right" style="padding-right: 10px;">Тип:</td>
	<td>
		<select class="inn" name="upd[<!--#slot src='name'-->][type]">
		<option value = '_stat'>Строковой</option>
		<!--#slot src='blocks' link='blocks4textslot'-->
		</select>
	</td>
</tr>
<tr>
	<td align="right" style="padding-right: 10px;">Значение:</td>
	<td><textarea class="inn" name = "upd[<!--#slot src='name'-->][value]"><!--#slot src='value'--></textarea></td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
	<td align="right" style="padding-right: 10px;">Название:</td>
	<td><b><!--#slot src='name'--></b></td>
</tr>
<tr>
	<td align="right" style="padding-right: 10px;">Описание:</td>
	<td><input class="inn" name = "upd[<!--#slot src='name'-->][descr]" value="<!--#slot src='descr'-->"></td>
<tr>
	<td align="right" style="padding-right: 10px;">Тип:</td>
	<td>
		<select name="upd[<!--#slot src='name'-->][type]" class="inn">
			<option value = '_stat'>Строковой</option>
			<!--#slot src='blocks' link='blocks4slot'-->
		</select>
	</td>
</tr>
<!--#endelem-->
</table>



</td>
    <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
  </tr>
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-3.gif" width="9" height="4"></td>
    <td width="98%" style="background-position:bottom; background-repeat:repeat-x" background="/images/__backend/common/gray.gif"></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-4.gif" width="9" height="4"></td>
  </tr>
</table>
<!--#endlist-->

//------------------------------------------------------------------------------
// Список доступных блоков для текста
//------------------------------------------------------------------------------
<!--#list name='blocks4textslot'-->
<!--#elem-->
<option value = "<!--#slot src='name'-->">Значение берется из блока &laquo;<!--#slot src='descr'-->(<!--#slot src='name'-->)&raquo;
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Список доступных блоков
//------------------------------------------------------------------------------
<!--#list name='blocks4slot'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('name') == $_ds->getParam('value');
<!--#endcond-->
<option value = "<!--#slot src='name'-->" SELECTED>Значение берется из блока &laquo;<!--#slot src='descr'-->(<!--#slot src='name'-->)&raquo;
<!--#endelem-->
<!--#elem-->
<option value = "<!--#slot src='name'-->" >Значение берется из блока &laquo;<!--#slot src='descr'-->(<!--#slot src='name'-->)&raquo;
<!--#endelem-->
<!--#endlist-->