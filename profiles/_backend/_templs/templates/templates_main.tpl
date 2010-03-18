<!--#slot src='general' link='files'-->
<!--#slot src='profile' link='files'-->
<div class="gray_block">
<p class="frame_head"><nobr>Создание:</nobr></p>
<div class="clear"></div>

<form method='post'>
<input type='hidden' name='object' value='templates'>
<input type='hidden' name='mode' value='main'>
<input type='hidden' name='action' value='add'>



<table cellpadding="3">
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Имя:</b></nobr></td>
  <td><input class="inn" name='filename'></td>
  <td></td>
</tr>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Тип:</b></nobr></td>
  <td>
	<select name='type'>
 	  <option value="tpl">Шаблон
  	  <option value="css">Таблица стилей CSS
	</select>
  </td>
  <td></td>
</tr>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Описание:</b></nobr></td>
  <td colspan='2'>
	<input class="inn" size='40' name='descript'>
  </td>
</tr>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Параметры:</b></nobr></td>
  <td>
	<select name='from'>
	<option value="new">Новый
	<!--#slot src='general' link='select_from'-->
	<!--#slot src='profile' link='select_from'-->
	</select>
  </td>
  <td>
	<select name='to'>
	<option value="general">Общая библиотека
	<option value="profile">Библиотека профиля
	</select>
  </td>
</tr>
<tr>
  <td></td>
  <td colspan="2">
<input style="margin: 7 0 0 0" type='image' name='ins' alt="Добавить" src="/images/__backend/common/but-add.gif">
  </td>
</tr>
</form>
</table>


</div>

<!--#partsep-->

<!--#list name='files'-->
<div class="gray_block">
<p class="frame_head"><nobr><!--#slot src='lib_name'-->:</nobr></p>
<div class="clear"></div>

<table border="0" width="100%">
<form method='post'>
<input type='hidden' name='object' value='templates'>
<input type='hidden' name='mode' value='main'>
<input type='hidden' name='action' value='del'>
<input type='hidden' name='lib' value='<!--#slot src='library'-->'>
<tr>
    <td><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td colspan="2"><p style="margin: 0; color: gray">Имя:</td>
    <td><p style="margin: 0; color: gray">Для браузера:</td>
    <td><p style="margin: 0; color: gray">Описание:</td>
</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
    <td width="1%" id="t<!--#slot src='_current'-->"><input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}" type='checkbox' name='files[]' value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->'></td>
    <td width="1%"><img style="margin-right:3" src="/images/__backend/files/file-s-<!--#slot src='ext'-->.gif"></td>
    <td width="30%"><a target='_blank' onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/_<!--#slot src='library'-->/<!--#slot src='filename'-->/',0,'width=600,height=500,menubar=0,scrollbars=1,resizable=1');return false;" href='#'><b><!--#slot src='name'--></b></a></td>
    <td width="30%"><!--#slot src='_switch' link='browser'--></td>
    <td width="38%"><!--#slot src='descript'--></td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
<td style="padding:0" colspan="5"><input style="margin: 10 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif"></td>
</tr>
</form>
</table>

</div>

<div style="height:20"></div>
<!--#endlist-->

<!--#list name='select_from'-->
<option value="">&mdash;&nbsp;<!--#slot src='lib_name'-->
<!--#elem-->
<option value="<!--#slot src='library'-->_<!--#slot src='filename'-->">&nbsp;&nbsp;&nbsp;&nbsp;из <!--#slot src='filename'-->
<!--#endelem-->
<!--#endlist-->

<!--#list name='browser'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('mode');
<!--#endcond-->
<!--#slot src='mode'-->
<!--#endelem-->
<!--#elem-->
<font color="#999999">—</font>
<!--#endelem-->
<!--#endlist-->
