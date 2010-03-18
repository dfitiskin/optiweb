<!--#slot src='general' link='files'-->
<!--#slot src='profile' link='files'-->
<!--#list src='modes'-->
<div class="gray_block">
<p class="frame_head"><nobr>Создание шаблона:</nobr></p>
<div class="clear"></div>

<table cellpadding="3">
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Тип:</b></nobr></td>
  <td colspan="2">
        <select name='type'>
            <!--#elem-->
                <option value='<!--#slot src="alias"-->'><!--#slot src="name"-->
            <!--#endelem-->
<!--#alter-->
<b style="color: #F00">Нет доступных типов блоков для добавления.</b>
<!--#endalter-->
        </select>
    <img style="cursor:hand" onclick="window.open('<!--#slot src='_url'-->_types/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
  </td>
</tr>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Версия:</b></nobr></td>
  <td><input class="inn" name='filename'></td>
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

<!--#endlist-->


<!--#partsep-->

<!--#list name='files'-->
<div class="gray_block">
<p class="frame_head"><nobr><!--#slot src='lib_name'-->:</nobr></p>
<div class="clear"></div>

<table border="0" >
<form method='post'>
<input type='hidden' name='object' value='templates'>
<input type='hidden' name='mode' value='main'>
<input type='hidden' name='action' value='del'>
<input type='hidden' name='lib' value='<!--#slot src='library'-->'>
<tr>
    <td><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td colspan="2"><p style="margin: 0; color: gray">Тип:</td>
    <td><p style="margin: 0; color: gray">Версия:</td>
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
    <td width="30%"><p style="margin:0 5 0 0"><a target='_blank' onclick="window.open('/_backend/templates/_edit/<!--#slot src='object'-->/_<!--#slot src='library'-->/<!--#slot src='filename'-->/',0,'width=600,height=500,menubar=0,scrollbars=1,resizable=1');return false;" href='#'><b><nobr><!--#slot src='mode'--></nobr></b></a></td>
    <td width="20%"><font color="666666"><b><!--#slot src='name'--></b></font></td>
    <td width="48%"><!--#slot src='descript'--></td>
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