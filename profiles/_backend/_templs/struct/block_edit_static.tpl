<form method='post'>
<input type='hidden' name='object' value='struct'>
<input type='hidden' name='mode' value='block_edit'>
<input type='hidden' name='action' value= 'upd'>
<input type='hidden' name='block' value= '<!--#slot src='name'-->'>
<input type='hidden' name='scope' value= '<!--#slot src='scope'-->'>

<table width="100%" height="100%" cellspacing="10" cellpadding="0">
<tr height="1%">
	<td colspan="2"><h2>Редактирование блока <b><!--#slot src='name'--></b></h2></td>
</tr>
<tr height="1%">
	<td>
	Название: <b><!--#slot src='name'--></b>
	</td>
</tr>
<tr height="1%">
    <td>
    Описание: <input class="inn" style="width:350" name='descr' value='<!--#slot src='descr'-->'>
    </td>
</tr>
<tr height="95%">
    <td><!--#slot src='editor'--></td>
</tr>
<tr height="1%">
    <td>
      <p style="margin: 7 0 7 0" align=center>
      <input type='image' name="save" alt="Сохранить" src="/images/__backend/common/but-save.gif">
      &nbsp;&nbsp;&nbsp;
      <img type='image' style="cursor:hand" onclick="window.close()" alt="Закрыть" src="/images/__backend/common/but-close.gif">
      </p>
    </td>
</tr>
</table>
</form>