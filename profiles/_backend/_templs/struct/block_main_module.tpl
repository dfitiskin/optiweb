<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Параметры раздела:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">

<form method='post'>
<input type='hidden' name='object' value='struct'>
<input type='hidden' name='mode' value='module'>
<input type='hidden' name='action' value='upd'>
<input type='hidden' name='id' value='<!--#slot src='id'-->'>

<table border="0" width="90%" cellspacing="0" cellpadding="5">
<tr>
<td colspan="2" bgcolor="#EFEFEF"><b>Основные:</b></td>
</tr>
<tr>
   <td colspan="2" style="padding:0"><div style="height:3" /></td>
</tr>
<!--#list src='_switch'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('level') > 1;
<!--#endcond-->
<tr>
   <td><p><nobr>Псевдоним:</nobr></td>
<td>
<input class="inn" style="width:200" size = '59' name='nodeparams[alias]' value="<!--#slot src='alias' filter='html'-->" />
   </td>
</tr>
<!--#endelem-->
<!--#endlist-->
<tr>
   <td><p><nobr>Короткое название:</nobr></td>
<td>
<input class="inn" style="width:200" size = '59' name='nodeparams[name]' value="<!--#slot src='name' filter='html'-->">
   </td>
</tr>
<tr>
   <td><p>Полное название:</p></td>
<td>
<input class="inn" style="width:200" size = '59' name='nodeparams[fullname]' value="<!--#slot src='fullname' filter='html'-->">
   </td>
</tr>
<tr>
   <td><p>Навигация:&nbsp;</td>
<td><!--#slot src='navtypes' link='navtypes'-->
<img style="cursor:hand" onclick="window.open('/_backend/struct/_navigation/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
   </td>
</tr>
<tr>
   <td colspan="2"><a href="/_backend/interactive/<!--#slot src='object'-->/<!--#slot src='module_version'-->/">Управление данными</a></td>
</tr>
<tr>
   <td colspan="2"><div style="height:7" /></td>
</tr>
<tr>
   <td colspan="2" bgcolor="#EFEFEF"><b>Настройки модуля &laquo;<!--#slot src='module_name'-->&raquo;:</b></td>
</tr>
<tr>
   <td colspan="2" style="padding:0"><div style="height:3" /></td>
</tr>
<!--#slot src='versions' link='versions'-->
<!--#slot src='moduleparams' link='moduleparams'-->
<!--#slot src='modes' link='modes'-->
</table>
<p style="margin: 10 0 0 0"><input type='image' alt='Сохранить' src="/images/__backend/common/but-save.gif"></p>
</form>
</td>
    <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
  </tr>
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-3.gif" width="9" height="4"></td>
    <td width="98%" style="background-position:bottom; background-repeat:repeat-x" background="/images/__backend/common/gray.gif"></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-4.gif" width="9" height="4"></td>
  </tr>
</table>
<!--#partsep-->

<!--#list name='navtypes'-->
<select name='nodeparams[menu]'>
<option selected value=''>Не задана
<!--#elem-->
<!--#cond-->
return $_ds->getParam('alias') != $_ds->getParam('menu');
<!--#endcond-->
<option value='<!--#slot src='alias'-->'><!--#slot src='name'-->
<!--#endelem-->
<!--#elem-->
<option selected value='<!--#slot src='alias'-->'><!--#slot src='name'-->
<!--#endelem-->
</select>
<!--#endlist-->

<!--#list name='versions'-->
<tr>
<td><p>Версия:</td>
<td>
<select name='version'>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('alias') == $_ds->getParam('_active');
<!--#endcond-->
<option value='<!--#slot src='alias'-->' selected ><!--#slot src='name'-->
<!--#endelem-->
<!--#elem-->
<option value='<!--#slot src='alias'-->'><!--#slot src='name'-->
<!--#endelem-->
</select>
</td>
</tr>


<!--#endlist-->

//------------------------------------------------------------------------------
// Список параметров модуля
//------------------------------------------------------------------------------
<!--#list name='moduleparams'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 'sel';
<!--#endcond-->
<tr>
<td><!--#slot src='descr'-->:</td>
<td>
<select name='module[<!--#slot src='name'-->]'>
<!--#slot src='values' link='options'-->
</select>
</td>
</tr>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 'st';
<!--#endcond-->
<tr>
	<td><!--#slot src='descr'-->:</td>
	<td>
		<input name='module[<!--#slot src='name'-->]' value='<!--#slot src='svalue'-->'>
	</td>
</tr>
<!--#endelem-->
<!--#endlist-->

<!--#list  name='options'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('svalue') == $_ds->getParam('value');
<!--#endcond-->
<option selected value='<!--#slot src='value'-->'><!--#slot src='name'-->
<!--#endelem-->
<!--#elem-->
<option value='<!--#slot src='value'-->'><!--#slot src='name'-->
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
// Список режимов работы
//------------------------------------------------------------------------------

<!--#list name='modes'-->
<tr>
   <td colspan="2"><div style="height:7" /></td>
</tr>
<tr>
<td colspan="2" bgcolor="#EFEFEF"><b>Режимы модуля:</b></td>
</tr>
<tr>
   <td colspan="2" style="padding:0"><div style="height:3" /></td>
</tr>
<!--#elem-->
<tr>
   <td colspan="2"><b><!--#slot src='descr' -->:</b></td>
</tr>
<tr>
<td>Блок вывода:</td>
<td>
<input class="inn" style="width:200" name="modes[<!--#slot src='name' -->][block]" value="<!--#slot src='block' -->">
</td>
</tr>

<!--#slot src='params' link='params_list'-->
<!--#slot src='templs' link='templs_list'-->

<!--#endelem-->

<!--#endlist-->


//------------------------------------------------------------------------------
// Список параметров
//------------------------------------------------------------------------------
<!--#list name='params_list'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 'list';
<!--#endcond-->
<tr>
<td><!--#slot src='desc'--></td>
<td>
        <select name='modes[<!--#slot src='mode_name'-->][params][<!--#slot src='name'-->]'>
	<!--#slot src='valuesset' link='list_param_values'-->
        </select>
</td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
<td><!--#slot src='desc'--></td>
<td>
        <input class="inn" style="width:200" name='modes[<!--#slot src='mode_name'-->][params][<!--#slot src='name'-->]' value='<!--#slot src='value'-->'>
</td>
</tr>
<!--#endelem-->

<!--#endlist-->

//------------------------------------------------------------------------------
// Список значений параметры
//------------------------------------------------------------------------------
<!--#list name='list_param_values'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('svalue') == $_ds->getParam('value');
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
<!--#elem-->
<tr>
    <td>Шаблон:</td>
    <td><!--#slot src='avtempls' link='templs_avail'-->
    <!--#slot src='_switch' link='edit_template'--></td>
</tr>
<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------
// Список доступных шаблонов
//------------------------------------------------------------------------------
<!--#list name='templs_avail'-->
<select name='modes[<!--#slot src='mode_name'-->][templs][<!--#slot src='name'-->]'>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('filename') == $_ds->getParam('activefile');
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
return  $_ds->GetParam('library');
<!--#endcond-->
<img style="cursor:hand; margin: 0 0 0 3" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='library'-->/<!--#slot src='activefile'-->/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
<!--#endelem-->
<!--#endlist-->