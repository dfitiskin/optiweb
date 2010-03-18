<table border="0" width="100%" cellspacing="0" cellpadding="15">
  <tr>
    <td width="100%">
<h2>Редактирование типов шаблонов</h2>
<!--#list src='types'-->
<div class="gray_block">
<p class="frame_head"><nobr>Типы навигаций:</nobr></p>
<div class="clear"></div>

<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='types'>
<input type='hidden' name='action' value='del'>
<table width="100%">
	<tr>
	    <td width="5%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
	    <td width="45%"><p style="margin: 0; color: gray">Название:</td>
	    <td width="50%"><p style="margin: 0; color: gray">Псевдоним:</td>
	</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#CCCCCC" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
	<td><input type='checkbox' name='del[]' value='<!--#slot src='id'-->'></td>
	<td><nobr><b><!--#slot src='name'--></b><font color="#CCCCCC">&nbsp;&rarr;</font></nobr></td>
	<td><nobr><!--#slot src='alias'--></nobr></td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
</table>
<input style="margin: 10 0 0 5" type='image' alt="Удалить" src="/images/__backend/common/but-del.gif">
</form>

</div>
<!--#endlist-->

<div style="height:20"></div>
<div class="gray_block">
<p class="frame_head"><nobr>Новый тип шаблона:</nobr></p>
<div class="clear"></div>

<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='types'>
<input type='hidden' name='action' value='add'>

<!--#slot src='warnings' link='warnings'-->
<table width="60%">
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Псевдоним:</b></nobr></td>
<td><input class="inn" name='add[alias]' value="<!--#slot src='alias'-->"></td>
</tr>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Название:</b></nobr></td>
<td><input class="inn" name='add[name]' value="<!--#slot src='name'-->"></td>
</tr>
<tr>
<td></td>
<td><input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
</tr>
</form>
</table>

</div>

<center>
<img style="margin: 14 0 0 0" type='image' style="cursor:hand" onclick="window.close()" alt="Закрыть" src="/images/__backend/common/but-close.gif">
</center>

</td>
  </tr>
</table>
<!--#partsep-->

//------------------------------------------------------------------------------------------------
//  Список предупреждений
//------------------------------------------------------------------------------------------------
<!--#list name='warnings'-->
<div style="margin: 0 0 0 0">
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