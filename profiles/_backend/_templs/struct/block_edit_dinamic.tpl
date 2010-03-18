<table width="100%" height="100%" cellspacing="10" cellpadding="0">
<tr height="1%">
<td colspan="2"><h2>Редактирование блока <b><!--#slot src='name'--></b></h2></td>
</tr>
  <form method='post'>
  <input type='hidden' name='object' value='struct'>
  <input type='hidden' name='mode' value='block_edit_dinamic'>
  <input type='hidden' name='action' value= 'upd'>
  <input type='hidden' name='block' value= '<!--#slot src='name'-->'>
  <input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
<tr height="1%">
    <td align="right" width="1%">Описание: </td>
    <td width="99%"><input class="inn" style="width:350" name='descr' value='<!--#slot src='descr'-->'></td>
</tr>

<tr height="1%">
<td align="right">Режим:</td>
<td><!--#slot src='modes' link='mode_select'--> </td>
</tr>

<tr height="1%">
<td colspan='2'>
<!--#slot src='params' link='params_list'-->
</td>
</tr>

<tr height="1%">
<td colspan='2'>
<!--#slot src='templs' link='templs_list'-->
</td>
</tr>

<tr height="95%">
    <td colspan="2" valign="bottom">
      <p style="margin: 7 0 7 0" align=center>
      <input type='image' name="save" alt="Сохранить" src="/images/__backend/common/but-save.gif">
      &nbsp;&nbsp;&nbsp;
      <img type='image' style="cursor:hand" onclick="window.close()" alt="Закрыть" src="/images/__backend/common/but-close.gif">
      </p>
    </td>
</tr>
</form>
</table>
<!--#partsep-->

//------------------------------------------------------------------------------
// Список режимов
//------------------------------------------------------------------------------
<!--#list name='mode_select'-->
<select name='act_mode'>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('name') == $_ds->getParam('mode');
<!--#endcond-->
	<option value='<!--#slot src='name'-->' SELECTED><!--#slot src='desc'-->
<!--#endelem-->
<!--#elem-->
	<option value='<!--#slot src='name'-->'><!--#slot src='desc'-->
<!--#endelem-->
</select>
<!--#endlist-->

//------------------------------------------------------------------------------
// Список параметров
//------------------------------------------------------------------------------
<!--#list name='params_list'-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:5 0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Параметры:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">
<table>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 'list';
<!--#endcond-->
<tr>
    <td align="right"><!--#slot src='desc'-->:</td>
    <td>
<select name='params[<!--#slot src='name'-->]'>
<!--#slot src='valuesset' link='list_param_values'-->
</select>
    </td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
    <td align="right"><!--#slot src='desc'-->:</td>
    <td><input class="inn" style="width:300px" name='params[<!--#slot src='name'-->]' value='<!--#slot src='svalue'-->'></td>
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
// Список значений параметры
//------------------------------------------------------------------------------
<!--#list name='list_param_values'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('value') == $_ds->getParam('svalue');
<!--#endcond-->
	<option value='<!--#slot src='value'-->' SELECTED><!--#slot src='name'-->
<!--#endelem-->
<!--#elem-->
	<option value='<!--#slot src='value'-->'><!--#slot src='name'-->
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Список шаблонов
//------------------------------------------------------------------------------
<!--#list name='templs_list'-->
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:5 0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Шаблоны:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">
<table>
<!--#elem-->
<tr>
    <td align="right"><!--#slot src='desc'-->:</td>
    <td>
	<select name='templs[<!--#slot src='name'-->]'>
	<option value = "">> шаблон не задан !
	<!--#slot src='avtempls' link='templs_avail'-->
	</select>
    </td>
    <td> <!--#slot src='_switch' link='edit_template'--></td>
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
// Список доступных шаблонов
//------------------------------------------------------------------------------
<!--#list name='templs_avail'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('filename') == $_ds->getParam('activefile');
<!--#endcond-->
<option value = "<!--#slot src='filename'-->" SELECTED><!--#slot link='filename'-->
<!--#endelem-->
<!--#elem-->
<option value = "<!--#slot src='filename'-->" ><!--#slot link='filename'-->
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Имя шаблона
//------------------------------------------------------------------------------
<!--#list src="_switch" name='filename'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('descript');
<!--#endcond-->
<!--#slot src='descript'-->
<!--#endelem-->
<!--#elem-->
<!--#slot src='name'-->
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Редактирование шаблона
//------------------------------------------------------------------------------
<!--#list name='edit_template'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('library');
<!--#endcond-->
<img style="cursor:hand" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='library'-->/<!--#slot src='activefile'-->/','edit','width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
<!--#endelem-->
<!--#endlist-->