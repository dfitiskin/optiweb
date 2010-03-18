
	<div class="gray_block">
    <p class="frame_head"><nobr>Пользователи:</nobr></p>
    <div class="clear"></div>
<!--#list src='users'-->
<table border=0>
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='<!--#slot src='mode' -->'>
<input type='hidden' name='action' value='del'>
<tr>
    <td width="1%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td width="13%"><p style="margin: 0; color: gray">Логин:</td>
    <td width="30%"><p style="margin: 0; color: gray"><nobr>Фамилия, Имя:</nobr></td>
    <td width="25%"><p style="margin: 0; color: gray">Телефон:</td>
    <td width="31%"><p style="margin: 0; color: gray">Город:</td>
</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#CCCCCC" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
   <td align='center' id="t<!--#slot src='_current'-->"><!--#slot link='is_master'--></td>
   <td><!--#slot src='login' --></td>
   <td><a href='<!--#slot src='_url'--><!--#slot src='login' -->/'><!--#slot src='surname' -->&nbsp;<!--#slot src='name' --></a></td>
   <td><!--#slot src='phone' --></td>
   <td><!--#slot src='email' --></td>
</tr>
<!--#endelem-->

<!--#alter-->
Пользователей
<!--#endalter-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
<td colspan="6">
<input style="margin: 10 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif">
</td>
</tr>

<!--#endlist-->

</form>
</table>
</div>


<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>Новый пользователь:</nobr></p>

<div class="clear"></div>

<!--#slot src='warnings' link='warnings'-->
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='<!--#slot src='mode'-->'>
<input type='hidden' name='action' value='add'>
<table align='center' width="90%">
<tr>
   <td width="15%" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Логин:</b></nobr></td>
   <td width="30%"><input class="inn" type='text' name='add[login]' value='<!--#slot src='login' -->'></td>
   <td width="5%"></td>
   <td width="15%" align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Пароль:</b></nobr></td>
   <td width="30%"><input class="inn" type='password' name='add[password]'></td>
</tr>
<tr>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Имя:</b></nobr></td>
   <td><input class="inn" type='text' name='add[name]' value='<!--#slot src='name' -->'></td>
   <td></td>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Фамилия:</b></nobr></td>
   <td><input class="inn" type='text' name='add[surname]' value="<!--#slot src='surname' -->"></td>
</tr>
<tr>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Телефон:</b></nobr></td>
   <td><input class="inn" type='text' name='add[phone]' value='<!--#slot src='phone' -->'></td>
   <td></td>
   <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Город:</b></nobr></td>
   <td><input class="inn" type='text' name='add[email]' value="<!--#slot src='email' -->"></td>
</tr>
<tr>
  <td colspan='5' align="center">
   <input style="margin: 15 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif">
  </td>
</tr>
</table>
</form>
</div>


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

class //------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
<!--#list src='_switch' name='is_master'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('master');
<!--#endcond-->
<span style="text-align: center; width: 12px;background-color: #F9595E; color: #FFFFFF;"><b>!</b></span>
<!--#endelem-->
<!--#elem-->
<input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}" type='checkbox' name='kill[]' value="<!--#slot src='id' -->">
<!--#endelem-->
<!--#endlist-->
{
}