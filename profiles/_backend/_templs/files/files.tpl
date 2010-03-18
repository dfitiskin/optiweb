<!--#list src='images' -->
<div class="gray_block">
<p class="frame_head"><nobr>ФАЙЛЫ:</nobr></p>

<div class="clear"></div>

<table width="100%" border=0>
<form method='post' id="unarcform">
<input type='hidden' name='object' value='files'>
<input type='hidden' name='mode' value='files'>
<input type='hidden' name='action' value='unarchive'>
<input type='hidden' id="arcfilename" name='filename' value=''>
</form>
<form method='post'>
<input type='hidden' name='object' value='files'>
<input type='hidden' name='mode' value='files'>
<input type='hidden' name='action' value='upd'>
<tr>
    <td><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td colspan="2"><p style="margin: 0; color: gray">Имя:</td>
    <td><p style="margin: 0; color: gray">Размер:</td>
    <td><p style="margin: 0; color: gray">Изменен:</td>
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
<!--#cond-->
return $_ds->getParam("is_exists");
<!--#endcond-->
<tr>
    <td width="1%" id="t<!--#slot src='_current'-->"><input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}" type='checkbox' name='files[]' value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->'></td>
    <td width="1%"><img style="margin-right:3" src="/images/__backend/files/file-s-<!--#slot src='ext'-->.gif"></td>
    <td width="50%"><!--#slot src='name'-->.<!--#slot src='ext'--><!--#slot src='_switch' link='archive'--></td>
    <td width="20%"><!--#slot src='size_in_kb'-->&nbsp;Kb</td>
    <td width="28%"><!--#slot src='date'-->&nbsp;&nbsp;<!--#slot src='time'--></td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
    <td id="t<!--#slot src='_current'-->"><input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}"  type='checkbox' name='files[]' value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->'></td>
    <td><img style="margin-right:3" src="/images/__backend/files/file-s-other.gif"></td>
    <td><!--#slot src='name'-->.<!--#slot src='ext'--></td>
    <td><!--#slot src='size_in_kb'-->&nbsp;Kb</td>
    <td><!--#slot src='date'-->&nbsp;&nbsp;<!--#slot src='time'--></td>
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

<!--#endlist-->
<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>Загрузить файлы:</nobr></p>
<div class="clear"></div>

	<form method='post' enctype= "multipart/form-data">
    	<input type='hidden' name='object' value='files'>
	<input type='hidden' name='mode' value='files'>
	<input type='hidden' name='action' value='upload'>
<table border="0" width="100%" cellspacing="0" cellpadding="7">
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input style="margin: 0 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
    <td width="50%"></td>
  </tr>
  </form>
</table>

</div>
<!--#partsep-->

<!--#list name='archive' -->
<!--#elem-->
<!--#cond-->
return $_ds->getParam("ext") == "zip";
<!--#endcond-->
&nbsp;<span style="font: 80%; cursor:hand; color:blue; text-decoration:underline;" onclick="if (confirm('Разархивировать файл \'\'<!--#slot src='name'-->.<!--#slot src='ext'-->\'\'?')) {document.all('arcfilename').value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->';document.all('unarcform').submit();}" onmouseover="" onmouseout="">(Разархивировать)</span>
<!--#endelem-->
<!--#endlist-->