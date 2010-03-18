<div style="margin: 0 0 10 4; width:90%">
<a href='..'>Профили</a>
<span>&nbsp;&rarr;&nbsp;</span>
<!--#slot src='name'-->
</div>

<div class="gray_block">
<p class="frame_head"><nobr>Параметры профиля:</nobr></p>
<div class="clear"></div>
    
<table>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Псевдоним:</b></nobr></td>
<td><p style="margin: 4 0"><b><!--#slot src='alias' --></b></td>
</tr>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Название:</b></nobr></td>
<td><input class="inn" name='upd[name]' value="<!--#slot src='name'-->"></td>
</tr>
<tr>
<td></td>
<td><input style="margin: 5 0 0 0" type='image' alt="Обновить" src="/images/__backend/common/but-refresh.gif"></td>
</tr>
</form>
</table>


<div style="height:30"></div>

<div class="gray_block">
<p class="frame_head" style="color:gray"><nobr>Доступ:</nobr></p>
<div class="clear"></div>

<table>


  <tr>
    <td style="padding: 10">

<!--#list src='access'-->


<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='object'-->'>
<input type='hidden' name='mode' value='property'>
<input type='hidden' name='action' value='del'>
<table border=0 style="margin-left:10">
<tr>
    <td width="1%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td width="49%"><p style="margin: 0; color: gray">Хост:</td>
    <td width="50%"><p style="margin: 0; color: gray">Путь:</td>
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
	<td>
		<input type='checkbox' name='kill[]' value="<!--#slot src='id' -->">
	</td>
	<td>
	 <!--#slot src='hostname' -->
	</td>
	<td>
	  <!--#slot src='rootdir' -->
	</td>
</tr>
<!--#endelem-->
<!--#alter-->
<p style="margin: 0 4 0 15; color:gray">Нет ни одного хоста</p>
<!--#endalter-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
<td colspan="3"><input style="margin: 10 0 0 0" type='image' alt="Удалить" src="/images/__backend/common/but-del.gif"></td>
</tr>
</table>
</form>




<!--#endlist-->
<div style="height:10"></div>

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="1%">
      <hr size="1" width="10" color="#CCCCCC">
    </td>
    <td width="1%"><p style="margin: 0 5; color:gray"><b><nobr>Создание нового хоста:</nobr></b></td>
    <td width="98%">
      <hr size="1" color="#CCCCCC">
    </td>
  </tr>
</table>

<div style="height:10"></div>

<table width="60%">
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='object'-->'>
<input type='hidden' name='mode' value='property'>
<input type='hidden' name='action' value='add'>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Хост:</b></nobr></td>
<td><input class="inn" name='add[hostname]' value="<!--#slot src='hostname' -->"></td>
</tr>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Путь:</b></nobr></td>
<td><input class="inn" name='add[rootdir]' value='<!--#slot src='rootdir' -->'></td>
</tr>
<tr>
<td></td>
<td><input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
</tr>
</form>
</table>

</td>
	</tr>
	
	
	
<tr>
   <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
 </tr>
  

<tr>
    <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
  </tr>
</table>
<!--#list src='warnings'-->
<table>
<!--#elem-->
<tr>
   <td>
	<!--#slot src='message'-->

   </td>
</tr>
<!--#endelem-->
</table>

</div>
<!--#endlist-->

</div>


