<div id="main">
	<SCRIPT src="/scripts/backend/struct.js" type="text/javascript"></SCRIPT>
	<SCRIPT src="/scripts/backend/utils.js" type="text/javascript"></SCRIPT>
  <form method='post' style="margin: 0 0 0 0" name='form<!--#slot src='id'-->'>
    <input type='hidden' name='object' value='struct'>
    <input type='hidden' name='mode' value='tree'>
    <input type='hidden' name='action' value='upd'>
		<!--#list src='root'-->
		<div>
		  <!--#elem-->
		  <div><img src='/images/__backend/struct/folder-01c.gif' style="float:left">&nbsp;<a <!--#slot src='_switch' link='active'--> href='/_backend/struct/<!--#slot src='mode_alias'-->'><!--#slot src='name'--></a></div>
		  <div style="padding:2 0 0 4"><!--#slot link='sublist'--></div>
		  <!--#endelem-->
		<!--#endlist-->
		<br />
		<table width="95%" cellspacing="0" cellpadding="0">
		  <tr>
		    <td id="add">
			    <div class="cent">
			      <h1><nobr>ДОБАВЛЕНИЕ РАЗДЕЛА:</nobr></h1>
			      <div class="clear"></div>
			      <!--#slot src='warnings' link='warnings'-->
			      <p class="type">Тип раздела:</p>
			      <p><input class="checker" <!--#slot src='_switch' link='type_s_checked'--> type="radio" name="add[type]" id="partstat" value="0" checked onclick="document.all('dinamic_modules').disabled=true"><label for="partstat" onclick="document.all('dinamic_modules').disabled=true">Статический</label></p>
			      <p><input class="checker" <!--#slot src='_switch' link='type_l_checked'--> type="radio" name="add[type]" id="partlink" value="2" onclick="document.all('dinamic_modules').disabled=true"><label for="partlink" onclick="document.all('dinamic_modules').disabled=true">Ссылка</label></p>
				  <p><input class="checker" <!--#slot src='_switch' link='type_d_checked'--> type="radio" name="add[type]" id="partdin" value="1" onclick="document.all('dinamic_modules').disabled=false"><label for="partdin" onclick="document.all('dinamic_modules').disabled=false">Динамический:</label></p>
						<p class="modules"><!--#slot src='modules' link='modules'--></p>
						<p class="nav">Навигация:&nbsp;
						<!--#slot src='navtypes' link='navtypes'-->
						<img style="cursor:hand" onclick="window.open('/_backend/struct/_navigation/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14"></p>
						<p>Псевдоним:<br /><input class="inn" name="add[alias]" value="<!--#slot src='alias' filter='html' -->"></p>
						<p>Название:<br /><input class="inn" name="add[name]" value="<!--#slot src='name' filter='html'-->"></p>
						<p>Полное название:<br /><input class="inn" name="add[fullname]" value="<!--#slot src='fullname' filter='html'-->"></p>
						<p class="submit"><input type='image' name='ins' alt="Добавить" src="/images/__backend/common/but-add.gif"></p>
			    </div>
		    </td>
		  </tr>  
		</table>  
	</form>
</div>

<!--#partsep-->

//------------------------------------------------------------------------------------------------
//  Созданние раздела: список навигационных меню
//------------------------------------------------------------------------------------------------
<!--#list name='navtypes'-->
<select name='add[menu]'>
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

//------------------------------------------------------------------------------------------------
//  Созданние раздела: Checked если выбран статический тип
//------------------------------------------------------------------------------------------------

<!--#list name='type_s_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == "s" || $_ds->getParam('type');
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние раздела: Checked если выбрана тип "ссылка"
//------------------------------------------------------------------------------------------------

<!--#list name='type_l_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == "l";
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->
//------------------------------------------------------------------------------------------------
//  Созданние раздела: Checked если выбран динамический тип
//------------------------------------------------------------------------------------------------

<!--#list name='type_d_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == "d";
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->
//------------------------------------------------------------------------------------------------
//  Созданние раздела: Disabled если выбран не динамический тип
//------------------------------------------------------------------------------------------------

<!--#list name='dinamic_modules_disabled'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') != "d";
<!--#endcond-->
disabled = 'true'
<!--#endelem-->
<!--#endlist-->
//------------------------------------------------------------------------------------------------
//  Список модулей для создания динамического раздела
//------------------------------------------------------------------------------------------------
<!--#list name='modules'-->
    <select name="add[module]" id="dinamic_modules"  <!--#slot src='_switch' link='dinamic_modules_disabled'-->>
<!--#elem-->
	<option value="<!--#slot src='id'-->"><!--#slot src='name'-->
<!--#endelem-->
    </select>
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Список предупреждений
//------------------------------------------------------------------------------------------------
<!--#list name='warnings'-->
<div style="margin: 0">
<table>
<!--#elem-->
<tr><td valign="top"><img style="margin-top:1" src="/images/__backend/common/error_pic.gif"></td>
<td valign="top">
<p style="margin: 0 0 5 0"><!--#slot src='message'--></p>
</td></tr>
<!--#endelem-->
</table>
</div>
<!--#endlist-->


//------------------------------------------------------------------------------------------------
//  Рекурсивный список разделов
//------------------------------------------------------------------------------------------------
<!--#list src='sublist' name='sublist'-->

<!--#elem-->
<!--#cond-->
return $_ds->GetParam('terminal') & 1;
<!--#endcond-->
<table cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><!--#slot src='_switch' link='corner'--></td>
    <td width="1%"><!--#slot src='_switch' link='node_open'--></td>
    <td width="96%" class="section"><div class="item"><a title="<!--#slot src='name'-->" <!--#slot src='_switch' link='active' filter='trim'--> href='/_backend/struct/<!--#slot src='mode_alias'--><!--#slot src='_url'-->'><!--#slot src='name'--></a></div></td>
    <td width="1%"><!--#slot src='_switch' link='node_move' filter='trim'--></td>
    <td width="1%" style="padding:0 0 0 3"><input class="checked" type='image' name='del[<!--#slot src='id'-->]' onclick="return confirm('Вы хотите удалить раздел <!--#slot src='name' filter='html'--> ?')" src="/images/__backend/struct/del.gif"></td>
  </tr>
</table>
<!--#endelem-->
<!--#elem-->
<table cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><!--#slot src='_switch' link='corner' filter='trim'--></td>
    <td width="1%"><!--#slot src='_switch' link='node_open' filter='trim'--></td>
    <td width="96%" class="section"><div class="item"><a title="<!--#slot src='name'-->" <!--#slot src='_switch' link='active' filter='trim'--> href='/_backend/struct/<!--#slot src='mode_alias'--><!--#slot src='_url'-->'><!--#slot src='name'--></a></div></td>
    <td width="1%"><!--#slot src='_switch' link='node_move' filter='trim'--></td>
    <td width="1%" style="padding:0 0 0 3"><input class="checked" type='image' name='del[<!--#slot src='id'-->]' onclick="return confirm('Вы хотите удалить <!--#slot src='name' filter='html'--> ?')" src="/images/__backend/struct/del.gif"></td>
  </tr>
</table>

<div id="div<!--#slot src='id'-->" class="upperlist" <!--#slot src='_switch' link='style' filter='trim'-->>
	<div class="underlist"><!--#slot link='sublist'--></div>
</div>
<!--#endelem-->

<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Послежний или не последний элемент (для фоновой линии)
//------------------------------------------------------------------------------------------------
<!--#list name='back'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current');
<!--#endcond-->
background:none
<!--#endelem-->
<!--#elem-->
background:url('/images/__backend/struct/line-back.gif') repeat-y 7px 2px
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Закрытый или открытый лист
//------------------------------------------------------------------------------------------------
<!--#list name='node_open'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('terminal') & 1;
<!--#endcond-->
<img src='/images/__backend/struct/page-<!--#slot src='type'--><!--#slot src='_switch' link='cont'-->.gif'>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_opened');
<!--#endcond-->
<img style="cursor:hand" onclick="change(<!--#slot src='id'-->,'<!--#slot src='_switch' link='node_type' filter='trim'-->','<!--#slot src='_switch' link='cont'-->','<!--#slot src='type'-->')" id='img<!--#slot src='id'-->' border="0" src='/images/__backend/struct/folder-<!--#slot src='type'--><!--#slot src='_switch' link='cont'-->o.gif'>
<!--#endelem-->
<!--#elem-->
<img style="cursor:hand" onclick="change(<!--#slot src='id'-->,'<!--#slot src='_switch' link='node_type' filter='trim'-->','<!--#slot src='_switch' link='cont'-->','<!--#slot src='type'-->')" id='img<!--#slot src='id'-->' border="0" src='/images/__backend/struct/folder-<!--#slot src='type'--><!--#slot src='_switch' link='cont'-->c.gif'>
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Закрытый или открытый список подразделов
//------------------------------------------------------------------------------------------------
<!--#list name='cont'-->
<!--#elem-->
<!--#cond-->
return !$_ds->GetParam('type');
<!--#endcond--><!--#slot src='content'--><!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Закрытый или открытый список подразделов
//------------------------------------------------------------------------------------------------
<!--#list name='style'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_opened');
<!--#endcond-->style="display:block;"<!--#endelem-->
<!--#elem-->style="display:none"<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Передвижение раздела вверх вниз
//------------------------------------------------------------------------------------------------
<!--#list name='node_move'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_count')==1;
<!--#endcond-->
<img src="/images/__backend/struct/1.gif" width="39" height="1">
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current');
<!--#endcond-->
<input class="checked" style="margin: 0 0 0 13" type='image' name="up[<!--#slot src='id'-->]" src="/images/__backend/struct/a_up.gif" width=11 height=11><img style="margin: 0 0 0 3" src="/images/__backend/struct/1.gif" width="11" height="1">
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_current')==1;
<!--#endcond-->
<input class="checked" style="margin: 0 0 0 28" type='image' name="down[<!--#slot src='id'-->]" src="/images/__backend/struct/a_down.gif" width=11 height=11>
<!--#endelem-->
<!--#elem-->
<nobr><input class="checked" style="margin: 0 0 0 13" style type='image' name="up[<!--#slot src='id'-->]" src="/images/__backend/struct/a_up.gif" width=11 height=11><input style="margin: 0 0 0 3" type='image' name="down[<!--#slot src='id'-->]" src="/images/__backend/struct/a_down.gif" width=11 height=11></nobr>
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Уголки в линиях
//------------------------------------------------------------------------------------------------

<!--#list name='corner'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('terminal') & 1;
<!--#endcond-->
<img src="/images/__backend/struct/c-none-<!--#slot src='_switch' link='node_type'-->.gif">
<!--#endelem-->

<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_opened');
<!--#endcond-->
<img style="cursor:hand" onclick="change(<!--#slot src='id'-->,'<!--#slot src='_switch' link='node_type'-->','<!--#slot src='_switch' link='cont'-->','<!--#slot src='type'-->')"  id='cor<!--#slot src='id'-->' border="0" src="/images/__backend/struct/c-minus-<!--#slot src='_switch' link='node_type'-->.gif" width="18" height="18">
<!--#endelem-->

<!--#elem-->
<img style="cursor:hand" onclick="change(<!--#slot src='id'-->,'<!--#slot src='_switch' link='node_type'-->','<!--#slot src='_switch' link='cont'-->','<!--#slot src='type'-->')"  id='cor<!--#slot src='id'-->' border="0" src="/images/__backend/struct/c-plus-<!--#slot src='_switch' link='node_type'-->.gif" width="18" height="18">
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Определение типа папочки
//------------------------------------------------------------------------------------------------
<!--#list name='node_type'-->
<!--#elem--><!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current') && $_ds->GetParam('_current')==1;
<!--#endcond-->corner<!--#endelem-->
<!--#elem--><!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current');
<!--#endcond-->corner<!--#endelem-->
<!--#elem-->cross<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Определение активного пункта
//------------------------------------------------------------------------------------------------
<!--#list name='active'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('id') != $_ds->GetParam('active_id');
<!--#endcond-->
style="text-decoration:none; color:black;"
<!--#endelem-->
<!--#elem-->
style="text-decoration:none; color:black; font-weight: bold;"
<!--#endelem-->
<!--#endlist-->