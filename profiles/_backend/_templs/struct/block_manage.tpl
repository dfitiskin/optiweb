<script>
function checkform() {
var errormes = "";
if (document.all('newr').checked) {
if (document.all('source').value=="") {errormes+="Выберите тип блока. "};
} else {
if (document.all('tonew').value=="") {errormes+="Выберите блок для копирования. "};
}
if (document.all('blockname').value=="" && document.all('newr').checked) {errormes+=" Введите название блока. "}
if (errormes=='') { return true } else { alert(errormes); return false; }
}
</script>

<SCRIPT src="/scripts/backend/blocks.js" type="text/javascript"></SCRIPT>

<script>
    ParentStateInit(<!--#slot src='hide_blocks'-->);
</script>

<!--#list src='main_template'-->
<table width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="add" style="padding:10 0 0 0">
			<div class="cent">
				<h1 onclick="switch_section('<!--#slot src='section_name'-->')"><nobr><a>ОСНОВНОЙ ШАБЛОН:</a></nobr></h1>
				<div class="clear"></div>
				<div id='<!--#slot src='section_name'-->' <!--#slot link='setion_visible'--> >
					<form method='post'>
						<input type='hidden' name='object' value='struct'>
						<input type='hidden' name='mode' value='blocks_manage'>
						<input type='hidden' name='action' value='upd_templ'>
						<table width="100%" cellspacing="5" cellpadding="0">
							<!--#slot src='_switch' link='default_templ'-->
							<tr><td></td></tr>
							<tr>
								<td width="1%" valign="top" align="right">
								<p style="margin: 0 4 0 10; color:gray"><nobr><b>Только для раздела:</b></nobr></td>
								<td width="99%"><!--#slot src='this_templs' link='template'--></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray" align="right"><b>Для всех:</b></td>
								<td><!--#slot src='shuffle_templs' link='template'--></td>
							</tr>
							<tr>
							<td></td>
							<td><input style="margin:10 0 0 0" type='image' alt="Сохранить" src="/images/__backend/common/but-save.gif"></td>
							</tr>
						</table>
					</form>
   			</div>
			</div>
	  </td>
	</tr>  
</table>  
<!--#endlist-->

<div style="height:20"></div>

<!--#list src='spec_template'-->

<table width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="add" style="padding:10 0 0 0">
			<div class="cent">
				<h1 onclick="switch_section('<!--#slot src='section_name'-->')"><nobr><a style="cursor:hand">СПЕЦИАЛЬНЫЕ ШАБЛОНЫ:</a></nobr></h1>
				<div class="clear"></div>
				<div id='<!--#slot src='section_name'-->' <!--#slot link='setion_visible'--> >
					<form method='post'>
						<input type='hidden' name='object' value='struct'>
						<input type='hidden' name='mode' value='blocks_manage'>
						<input type='hidden' name='action' value='upd_spectempl'>
						<table width="100%" cellspacing="5" cellpadding="0">
							<!--place for default_templ-->
							<!--#slot src='_switch' link='default_templ4print'-->
							<tr><td></td></tr>
							<tr>
								<td width="1%" valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Для печати:</b></nobr></td>
								<td width="99%"><!--#slot src='templs4print' link='template'--></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray" align="right"><nobr><b>Страница не найдена:</b></nobr></td>
								<td><!--#slot src='template404' link='template'--></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray" align="right"><nobr><b>Страница недоступна:</b></nobr></td>
								<td><!--#slot src='template403' link='template'--></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray" align="right"><nobr><b>Ошибки на странице:</b></nobr></td>
								<td><!--#slot src='templateerror' link='template'--></td>
							</tr>
							<tr>
							<td></td>
							<td><input style="margin:10 0 0 0" type='image' alt="Сохранить" src="/images/__backend/common/but-save.gif"></td>
							</tr>
						</table>
					</form>
   			</div>
			</div>
	  </td>
	</tr>  
</table>  

<!--#endlist-->

<div style="height:20"></div>

<table width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="add" style="padding:10 0 0 0">
			<div class="cent" style="padding:7">
				<div style="cursor:pointer;width:1%" onclick='switch_blocks()'>
					<table cellspacing="0" cellpadding="3">
					  <tr>
					    <td><img id="parentfilter"  src='/images/__backend/common/chkgray<!--#slot src='hide_blocks'-->.gif'></td>
					    <td><nobr>Отображать родительские блоки</nobr></td>
					  </tr>
					</table>
				</div>
			</div>
	  </td>
	</tr>  
</table>  

<!--#slot src='this_blocks' link='blocks'-->
<!--#slot src='shuffle_blocks' link='blocks'-->
<!--#slot src='childs_blocks' link='blocks'-->

<div style="height:20"></div>

<!--#list src='addblock'-->
<table width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="add" style="padding:10 0 0 0">
			<div class="cent">
				<h1 onclick="switch_section('<!--#slot src='section_name'-->')"><nobr><a style="cursor:hand">СОЗДАНИЕ БЛОКА:</a></nobr></h1>
				<div class="clear"></div>
				<div id='<!--#slot src='section_name'-->' <!--#slot link='setion_visible'--> >
					<form method='post'>
						<input type='hidden' name='object' value='struct'>
						<input type='hidden' name='mode' value='blocks_manage'>
						<input type='hidden' name='action' value='add'>
						<!--#slot src='warnings' link='warnings'-->
						<table width="100%" cellspacing="5" cellpadding="0">
							<!--place for default_templ-->
							<tr><td></td></tr>
							<tr>
								<td width="1%" valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Название:</b></nobr></td>
								<td width="99%"><input id="blockname" class="inn" name='add[name]' value='<!--#slot src='block_name'-->'></td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td valign="top" align="right"></td>
								<td>
					        <input type="radio" class="checker" id="newr" value="new" <!--#slot src='_switch' link='creating3'--> name="add[create]" onclick="document.all('tonew').disabled=true;document.all('source').style.display='';if (document.all('dinamic').checked) {document.all('source2').disabled=false}">
					        <label for="newr" onclick="document.all('tonew').disabled=true;document.all('source').style.display='';if (document.all('dinamic').checked) {document.all('source2').disabled=false}">Новый</label>
					        <br>
					        <input type="radio" class="checker" id="basedr" <!--#slot src='_switch' link='creating4'--> value="based" name="add[create]" onclick="document.all('tonew').disabled=false;document.all('source').style.display='none'; document.all('source2').disabled=true">
					        <label for="basedr" onclick="document.all('tonew').disabled=false;document.all('source').style.display='none'; document.all('source2').disabled=true">На основе блока:</label>
					    		<select name='add[from]' id="tonew" <!--#slot src='_switch' link='creating1'-->>
						        <!--#slot src='this_blocks' link='from_block'-->
						        <!--#slot src='shuffle_blocks' link='from_block'-->
						        <!--#slot src='childs_blocks' link='from_block'-->
					        </select>
								</td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td width="1%" valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Описание:</b></nobr></td>
								<td width="99%"><input class="inn" name='add[descr]' value='<!--#slot src='block_descr' filter='html'-->'></td>
							</tr>
							<tr><td></td></tr>
							<tr <!--#slot src='_switch' link='create_hide_type'--> id="source" style="border-style: none">
								<td width="1%" valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Тип:</b></nobr></td>
								<td width="99%">
									<input class="checker" <!--#slot src='_switch' link='type_s_checked'--> type="radio" value="s" id="static" name="add[src]" onclick="document.all('source2').disabled=true">
									<label for="static" onclick="document.all('source2').disabled=true">Статический</label><br>
									<input class="checker" <!--#slot src='_switch' link='type_t_checked'--> type="radio" value="t" id="template" name="add[src]" onclick="document.all('source2').disabled=true">
									<label for="template" onclick="document.all('source2').disabled=true">Шаблонный</label><br>
									<input class="checker" <!--#slot src='_switch' link='type_d_checked'--> type="radio" value="d" id="dinamic" name="add[src]" onclick="document.all('source2').disabled=false">
									<label for="dinamic" onclick="document.all('source2').disabled=false">Динамический:</label>
									<select id="source2" name='add[module]' <!--#slot src='_switch' link='create_hide_modules'-->>
									<!--#list src='modules'-->
									<!--#elem-->
									  <option <!--#slot src='_switch' link='active_option'--> value='<!--#slot src='alias'-->'><!--#slot src='name'-->
									<!--#endelem-->
									<!--#endlist-->
									</select>
								</td>
							</tr>
							<tr><td></td></tr>
							<tr>
								<td width="1%" valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Область действия:</b></nobr></td>
								<td width="99%">
									<!--#list src='block_scope'-->
									<select name='add[scope]'>
									<!--#elem-->
							      <option <!--#slot src='_switch' link='active_option'--> value='<!--#slot src='alias'-->'><!--#slot src='name'-->
									<!--#endelem-->
									</select>
									<!--#endlist-->
								</td>
							</tr>
							<tr>
							<td></td>
							<td><input style="margin: 14 0 0 0" type='image' name='ins' alt="Добавить" src="/images/__backend/common/but-add.gif" onclick="return checkform();" ></td>
							</tr>
						</table>
					</form>
   			</div>
			</div>
	  </td>
	</tr>  
</table>  
<!--#endlist-->


<!--#partsep-->
//------------------------------------------------------------------------------------------------
//  Созданние блока: выбор блока динамического типа
//------------------------------------------------------------------------------------------------

<!--#list name='type_d_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('src') == "d";
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->
//------------------------------------------------------------------------------------------------
//  Созданние блока: выбор блока шаблонного типа
//------------------------------------------------------------------------------------------------

<!--#list name='type_t_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('src') == "t";
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->
//------------------------------------------------------------------------------------------------
//  Созданние блока: выбор блока статического типа
//------------------------------------------------------------------------------------------------

<!--#list name='type_s_checked'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('src') == "s" || !$_ds->getParam('src');
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние блока: выключение списка ТИП
//------------------------------------------------------------------------------------------------

<!--#list name='create_hide_modules'-->
<!--#elem-->
<!--#cond-->
return !$_ds->getParam('src') || $_ds->getParam('src') == "s" || $_ds->getParam('src') == "t";
<!--#endcond-->
disabled
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние блока: выключение списка ТИП
//------------------------------------------------------------------------------------------------

<!--#list name='creating4'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('create') == "based";
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние блока: выключение списка БЛОКИ
//------------------------------------------------------------------------------------------------

<!--#list name='creating3'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('create') == "new" || !$_ds->getParam('create');
<!--#endcond-->
CHECKED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние блока: выключение списка ТИП
//------------------------------------------------------------------------------------------------

<!--#list name='create_hide_type'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('create') == "based";
<!--#endcond-->
style="display:none"
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Созданние блока: выключение списка БЛОКИ
//------------------------------------------------------------------------------------------------

<!--#list name='creating1'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('create') == "new" || !$_ds->getParam('create');
<!--#endcond-->
DISABLED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Добавление:Активная опция
//------------------------------------------------------------------------------------------------

<!--#list name='active_option'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('alias') == $_ds->getParam('active');
<!--#endcond-->
SELECTED
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
//  Список предупреждений
//------------------------------------------------------------------------------------------------
<!--#list name='warnings'-->
<div style="margin-left: 110">
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


//----------------------------------------------------------------------------
//  Шаблон по умоланию (от предка)
//----------------------------------------------------------------------------
<!--#list name='default_templ'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('p_templ');
<!--#endcond-->
<tr>
<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>По умолчанию:</b></nobr></td>
<td><p style="color:gray"><!--#slot src='p_templ_descr' -->&nbsp;<b><!--#slot src='p_templ' --></b></p></td>
</tr>

<!--#endelem-->
<!--#endlist-->

//----------------------------------------------------------------------------
//  Шаблон для печати по умоланию (от предка)
//----------------------------------------------------------------------------
<!--#list name='default_templ4print'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('p_templ4print');
<!--#endcond-->
<tr>
<td valign="top" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>По умолчанию:</b></nobr></td>
<td><p style="color:gray"><!--#slot src='p_templ4print_descr' -->&nbsp;<b><!--#slot src='p_templ4print' --></b></p></td>
</tr>

<!--#endelem-->
<!--#endlist-->


//----------------------------------------------------------------------------
//  Выбор шаблона
//----------------------------------------------------------------------------
<!--#list name='template'-->
<select name='template[<!--#slot src="name"-->]' style="float:left">
<option value=''>Не&nbsp;задан
<!--#elem-->
   <!--#cond-->
	return $_ds->GetParam('filename') == $_ds->GetParam('active');
   <!--#endcond-->
<option value='<!--#slot src='filename'-->' SELECTED><!--#slot src='descript'-->(<!--#slot src='filename'-->)
<!--#endelem-->
<!--#elem-->
<option value='<!--#slot src='filename'-->'><!--#slot src='descript'-->(<!--#slot src='filename'-->)
<!--#endelem-->
</select>
<!--#slot src='_switch' link='edit_template'-->
<!--#endlist-->



//----------------------------------------------------------------------------
//  Редактирование шаблона
//----------------------------------------------------------------------------
<!--#list name='edit_template'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('active') && $_ds->GetParam('library');
<!--#endcond-->
<div style="width:100px; float:left">
<img style="cursor:hand; margin:2 0 0 4; float:left" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='library'-->/<!--#slot src='active'-->/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif">
<p style="color:blue; text-decoration:underline; cursor:hand; margin:2 0 0 4" onmouseover="style.color='red'" onmouseout="style.color='blue'" onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/<!--#slot src='library'-->/<!--#slot src='active'-->/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')">Редактировать</p>
</div>
<!--#endelem-->
<!--#elem-->
<div style="width:100px; float:left">
<img src="/images/__backend/struct/edit2.gif" style="margin:2 0 0 4; float:left"><p style="color:#CCCCCC; margin:2 0 0 4">Редактировать</p>
</div>
<!--#endelem-->
<!--#endlist-->

//----------------------------------------------------------------------------
//  Есть ли хотябы один установленный блок
//----------------------------------------------------------------------------
<!--#list src='_switch' name='no_setted_block'-->
<!--#elem-->
<!--#cond-->
return  $_ds->GetParam('no_setted_blocks');
<!--#endcond-->
id='parent_block'
<!--#slot link='hide_blocks_style'-->
<!--#endelem-->
<!--#endlist-->

//----------------------------------------------------------------------------
//  Отображение секции
//----------------------------------------------------------------------------
<!--#list src='_switch' name='setion_visible'-->
<!--#elem-->
<!--#cond-->
if (isset($_COOKIE['params'][$_ds->getParam('section_name')])) return $_COOKIE['params'][$_ds->getParam('section_name')];
else return 0;
<!--#endcond-->
style="display:block;"
<!--#endelem-->
<!--#elem-->
style="display:none;"
<!--#endelem-->
<!--#endlist-->


//----------------------------------------------------------------------------
//  Скрыть родительские блоки
//----------------------------------------------------------------------------
<!--#list src='_switch' name='hide_blocks_style'-->
<!--#elem-->
<!--#cond-->
return !$_ds->GetParam('hide_blocks');
<!--#endcond-->style="display:block;"<!--#endelem-->
<!--#elem-->style="display:none"<!--#endelem-->
<!--#endlist-->


//----------------------------------------------------------------------------
//  Список Блоков
//----------------------------------------------------------------------------

<!--#list name='blocks'-->
<div <!--#slot link='no_setted_block'-->  >
<div style="height:20"></div>
<table width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="add" style="padding:10 0 0 0">
			<div class="cent">
				<h1 onclick="switch_section('<!--#slot src='section_name'-->')"><nobr><a style="cursor:hand; text-transform:uppercase"><!--#slot src='part_full_name'--></a></nobr></h1>
				<div class="clear"></div>
				<div id='<!--#slot src='section_name'-->' <!--#slot link='setion_visible'--> style="margin:7 0 0 0">
<!--#sep-->
<!--#cond-->
return  !$_ds->GetParam('setted') || ($_ds->GetParam('was_parent') && !$_ds->GetParam('was_setted'));
<!--#endcond-->
<div id='parent_block' <!--#slot link='hide_blocks_style'-->>
<div style="width:100%; height:1px; background:#ccc"><div style="width:10px; height:1px; background:#fff"><spacer type="block"></div></div>
</div>
<!--#endsep-->
<!--#sep-->
<div style="width:100%; height:1px; background:#ccc"><div style="width:10px; height:1px; background:#fff"><spacer type="block"></div></div>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 's' && $_ds->GetParam('setted') == '1';
<!--#endcond-->
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #ddd">
	<tr onmouseover="this.style.backgroundColor='#F5F5F5';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
				<p><b><!--#slot src='descr'--></b></p>
		    <p><!--#slot src='name'--></font> <!--#slot link='parent_block'--></p>
	    	
	  </td>
	  <td width="1%"><img class="checker" style="cursor:hand" onclick="window.open('/_backend/struct/_edit/<!--#slot src='name'-->/<!--#slot src='scope'-->/<!--#slot src='url'-->',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать блок" src="/images/__backend/struct/edit.gif"></td>
		<td width="1%"><img class="checker" style="cursor:hand" onclick="window.open('/_backend/struct/_view/<!--#slot src='name'-->/<!--#slot src='scope'-->/<!--#slot src='url'-->',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Просмотреть блок" src="/images/__backend/struct/preview.gif"></td>
		<td width="1%"><input class="checker" alt="Удалить блок" id="del<!--#slot src='scope'--><!--#slot src='name'-->" type="image" onclick="return confirm('Вы действительно хотите удалить блок \'\'<!--#slot src='name'-->\'\' ?')" src="/images/__backend/struct/delete.gif" value='x'></td>
	</tr>
</table>
</form>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 's' && $_ds->GetParam('setted') == '0';
<!--#endcond-->
<div id='parent_block' <!--#slot link='hide_blocks_style'-->>
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #ddd">
	<tr onmouseover="this.style.backgroundColor='#F5F5F5';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
				<p><b><!--#slot src='descr'--></b></p>
		    <p><!--#slot src='name'--> <!--#slot link='parent_block'--></p>
	    	
	  </td>
	  <td width="1%"><img src="/images/__backend/struct/edit2.gif"></td>
		<td width="1%"><img style="cursor:hand" onclick="window.open('/_backend/struct/_view/<!--#slot src='name'-->/<!--#slot src='scope'-->/<!--#slot src='url'-->',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Просмотреть блок" src="/images/__backend/struct/preview.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/delete2.gif"></td>
	</tr>
</table>
</form>
</div>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 'd' && $_ds->GetParam('setted') == '1';
<!--#endcond-->
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #9abacb">
	<tr onmouseover="this.style.backgroundColor='#f2f7f9';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
				<p style="color:#6193ad"><b><!--#slot src='descr'--></b><br><b>Модуль:&nbsp;</b><!--#slot src='module_descr'-->&nbsp;&nbsp;&nbsp;<b>Режим:&nbsp;</b><!--#slot src='mode_descr'--></p>
		        <p><!--#slot src='name'--> <!--#slot link='parent_block'--></p>
	    	
	  </td>
	  <td width="1%"><img style="cursor:hand" onclick="window.open('/_backend/struct/_edit/<!--#slot src='name'-->/<!--#slot src='scope'-->/<!--#slot src='url'-->',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать блок" src="/images/__backend/struct/edit.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/preview2.gif"></td>
		<td width="1%"><input id="del<!--#slot src='scope'--><!--#slot src='name'-->" type="image" onclick="return confirm('Вы действительно хотите удалить блок \'\'<!--#slot src='name'-->\'\' ?')" src="/images/__backend/struct/delete.gif" alt="Удалить блок" value='x'></td>
	</tr>
</table>
</form>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 'd' && $_ds->GetParam('setted') == '0';
<!--#endcond-->
<div id='parent_block' <!--#slot link='hide_blocks_style'-->>
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #9abacb">
	<tr onmouseover="this.style.backgroundColor='#f2f7f9';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
				<p style="color:#6193ad"><b><!--#slot src='descr'--></b><br><b>Модуль:&nbsp;</b><!--#slot src='module_descr'-->&nbsp;&nbsp;&nbsp;<b>Режим:&nbsp;</b><!--#slot src='mode_descr'--></p>
		    	<p><!--#slot src='name'--></font></b> <!--#slot link='parent_block'--></p>
	    	
	  </td>
	  <td width="1%"><img src="/images/__backend/struct/edit2.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/preview2.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/delete2.gif"></td>
	</tr>
</table>
</form>
</div>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 't' && $_ds->GetParam('setted') == '1';
<!--#endcond-->
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #b3dfa2">
	<tr onmouseover="this.style.backgroundColor='#e8f7e6';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
				 <p style="color:#84B071"><b><!--#slot src='descr'--></b></p>
		   		 <p><!--#slot src='name'--> <!--#slot link='parent_block'--></p>
		   
	  </td>
	  <td width="1%"><img style="cursor:hand" onclick="window.open('/_backend/struct/_edit/<!--#slot src='name'-->/<!--#slot src='scope'-->/<!--#slot src='url'-->',null,'width=750,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать блок" src="/images/__backend/struct/edit.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/preview2.gif"></td>
		<td width="1%"><input id="del<!--#slot src='scope'--><!--#slot src='name'-->" type="image" onclick="return confirm('Вы действительно хотите удалить блок \'\'<!--#slot src='name'-->\'\' ?')" name="del[<!--#slot src='id'-->]" src="/images/__backend/struct/delete.gif" alt="Удалить блок" value='x'></td>
	</tr>
</table>
</form>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
	return $_ds->GetParam('src') == 't' && $_ds->GetParam('setted') == '0';
<!--#endcond-->
<div id='parent_block' <!--#slot link='hide_blocks_style'-->>
<table id="tab_r" cellspacing="0" cellpadding="5" style="border-left:10px solid #b3dfa2">
	<tr onmouseover="this.style.backgroundColor='#e8f7e6';" onmouseout="this.style.backgroundColor='#fff'">
		<td width="97%">
			<form method='post' style="margin: 0">
				<input type='hidden' name='object' value='struct'>
				<input type='hidden' name='mode' value='blocks_manage'>
				<input type='hidden' name='action' value= 'del'>
				<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
				<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>
		    <b><font color="#008000"><!--#slot src='name'--></font></b> <!--#slot link='parent_block'--><br /><p style="margin: 0 20 0 0; color:#84B071"><!--#slot src='descr'--></p>
	  </td>
	  <td width="1%"><img src="/images/__backend/struct/edit2.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/preview2.gif"></td>
		<td width="1%"><img src="/images/__backend/struct/delete2.gif"></td>
	</tr>
</table>
</form>
</div>
<!--#endelem-->
</div>
			</div>
	  </td>
	</tr>  
</table>  
<!--#endlist-->

//------------------------------------------------------------------------------
// Список родителей
//------------------------------------------------------------------------------
<!--#list src='_switch' name='parent_block'-->
  <!--#elem-->
   <!--#cond-->
	return $_ds->GetParam('parent')===0;
   <!--#endcond-->
  <!--#endelem-->
  <!--#elem-->
   <!--#cond-->
	return $_ds->GetParam('parent')===1;
   <!--#endcond-->
	(Смешанный)
  <!--#endelem-->
  <!--#elem-->
  (<a href = "/_backend/struct/_blocks/<!--#slot src='parent_url' -->">Родитель</a>)
  <!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
//  Копировать из блока
//------------------------------------------------------------------------------------------------

<!--#list name='from_block'-->
<option value='' style="background-color:#EEEEEE"><!--#slot src='part_name'-->:
<!--#elem-->
<option <!--#slot src='_switch' link='parent_act_opt'--> value='<!--#slot src='name'-->|<!--#slot src='setted'-->|<!--#slot src='scope'-->|<!--#slot src='level'-->'>&nbsp;&nbsp;&nbsp;<!--#slot src='name'-->
<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
//  Добавление:Активная опция для блока-источника
//------------------------------------------------------------------------------------------------

<!--#list name='parent_act_opt'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('name').'|'.$_ds->getParam('setted').'|'.$_ds->getParam('scope').'|'.$_ds->getParam('level') == $_ds->getParam('active');
<!--#endcond-->
SELECTED
<!--#endelem-->
<!--#endlist-->