<div style="margin: 0 0 10 4; width:90%">
<a href='..'>Все пользователи</a>
<span>&nbsp;&rarr;&nbsp;</span>
<!--#slot src='surname' -->&nbsp;<!--#slot src='name' -->
</div>

<div class="gray_block">
<p class="frame_head"><nobr>Редактирование данных пользователя:</nobr></p>
<div class="clear"></div>


<!--#slot src='warnings' link='warnings'-->
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='users_list'>
<input type='hidden' name='action' value='upd'>

<table align='center' width="100%">
<tr>
   <td width="15%" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Логин:</b></nobr></td>
   <td width="30%"><input class="inn" type='text' name='upd[login]' value='<!--#slot src='login' -->'></td>
   <td width="5%"></td>
   <td width="15%" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Пароль:</b></nobr></td>
   <td width="35%"> <input class="inn" type='password' name='upd[password]' value='<!--#slot src='password' -->'></td>
</tr>
<tr>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Имя:</b></nobr></td>
   <td><input class="inn" type='text' name='upd[name]' value='<!--#slot src='name' -->'></td>
   <td></td>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Фамилия:</b></nobr></td>
   <td><input class="inn" type='text' name='upd[surname]' value="<!--#slot src='surname' -->"></td>
</tr>
<tr>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Телефон:</b></nobr></td>
   <td><input class="inn" type='text' name='upd[phone]' value='<!--#slot src='phone' -->'></td>
   <td></td>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Город:</b></nobr></td>
   <td><input class="inn" type='text' name='upd[email]' value="<!--#slot src='email' -->"></td>
</tr>
<tr>
  <td colspan='5' align="center">
   <input style="margin: 15 0 0 0" type='image' alt="Обновить" src="/images/__backend/common/but-refresh.gif">
  </td>
</tr>
</table>
</form>


</div>

<!--#slot link='is_master'-->

<!--#partsep-->
//------------------------------------------------------------------------------------------------
//  Список предупреждений
//------------------------------------------------------------------------------------------------
<!--#list name='warnings'-->
<div style="margin-left: 65">
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

//------------------------------------------------------------------------------
//  Убиваемый профиль
//------------------------------------------------------------------------------
<!--#list src='_switch' name='killable'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('perms');
<!--#endcond-->
<input type='checkbox' name='kill[]' value="<!--#slot src='id' -->">
<!--#endelem-->
<!--#elem-->
<span style="text-align: center; width: 12px;background-color: #F9595E; color: #FFFFFF;"><b>@</b></span>
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
//  Убиваемый модуль
//------------------------------------------------------------------------------
<!--#list src='_switch' name='killable2'-->
<!--#elem-->
<!--#cond-->
return ($_ds->getParam('version_id') && !$_ds->getParam('version_perms')) || !$_ds->getParam('perms');
<!--#endcond-->
<span style="text-align: center; width: 12px;background-color: #F9595E; color: #FFFFFF;"><b>@</b></span>
<!--#endelem-->
<!--#elem-->
<input type='checkbox' name="kill[<!--#slot src='id' -->][<!--#slot src='version_id'-->]" value="1">
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
<!--#list src='_switch' name='is_master'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('master');
<!--#endcond-->
<div style="height:20"></div>
<p>Глобальный доступ</p>
<!--#endelem-->
<!--#elem-->
<!--
<div style="height:20"></div>
<table border="0" width="90%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Доступ к профилям:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 10">

<table width="100%">
<tr>
<td width="40%" valign="top">
<!--#list src='profiles_del'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Имеет доступ к профилям:</b></nobr>

<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='userprofiles'>
<input type='hidden' name='action' value='del'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
<td width="1%" align='center'><!--#slot link='killable'--></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Не имеет доступа к профилям</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
<td width="10%" background="/images/__backend/common/gray.gif" style="background-position: center; background-repeat: repeat-y;"></td>
<td width="50%" valign="top">
<!--#list src='profiles_add'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Возможные профили:</b></nobr>
<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='userprofiles'>
<input type='hidden' name='action' value='add'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
<td width="1%"><input type='checkbox' name='add[]' value="<!--#slot src='id' -->"></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Нет доступных профилей</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
</tr>
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
-->
<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>Доступ к меню:</nobr></p>
<div class="clear"></div>
    
    
<table width="100%">
<tr>
<td width="40%" valign="top">
<!--#list src='menu_del'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Имеет доступ к пунктам:</b></nobr>

<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='usermenu'>
<input type='hidden' name='action' value='del'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
<td width="1%" align='center'><!--#slot link='killable'--></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Не имеет доступа к меню</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
<td width="10%" background="/images/__backend/common/gray.gif" style="background-position: center; background-repeat: repeat-y;"></td>
<td width="50%" valign="top">
<!--#list src='menu_add'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Возможные пункты:</b></nobr>
<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='usermenu'>
<input type='hidden' name='action' value='add'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
<td width="1%"><input type='checkbox' name='add[]' value="<!--#slot src='id' -->"></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Нет пунктов меню</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
</tr>
</table>

</div>

<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>Доступ к модулям:</nobr></p>
<div class="clear"></div>

<table width="100%">
<tr>
<td width="40%" valign="top">
<!--#list src='modules_del'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Имеет доступ к модулям:</b></nobr>

<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='usermodules'>
<input type='hidden' name='action' value='del'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('version_id');
<!--#endcond-->
<tr>
<td width="1%" align='center'><!--#slot link='killable2'--></td>
<td width="99%"><!--#slot src='name' --> &mdash; <!--#slot src='version_name' --></td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
<td width="1%" align='center'><!--#slot link='killable2'--></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Не имеет доступа к модулям</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
<td width="10%" background="/images/__backend/common/gray.gif" style="background-position: center; background-repeat: repeat-y;"></td>
<td width="50%" valign="top">
<!--#list src='modules_add'-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Возможные модули:</b></nobr>
<table width="100%" style="margin-top:5">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object' -->'>
<input type='hidden' name='mode' value='usermodules'>
<input type='hidden' name='action' value='add'>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="2"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('version_id');
<!--#endcond-->
<tr>
<td width="1%"><input type='checkbox' name="add[<!--#slot src='id' -->][<!--#slot src='version_id'-->]" value="1"></td>
<td width="99%"><!--#slot src='name' --> &mdash; <!--#slot src='version_name' --></td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
<td width="1%"><input type='checkbox' name="add[<!--#slot src='id' -->][<!--#slot src='version_id'-->]" value="1"></td>
<td width="99%"><!--#slot src='name' --></td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 0; color:gray"><nobr><b>Нет модулей</b></nobr>
<!--#endalter-->
<tr>
  <td colspan="2">
<input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif">
  </td>
</tr>
</form>
</table>
<!--#endlist-->
</td>
</tr>
</table>

</div>

<!--#endelem-->
<!--#endlist-->