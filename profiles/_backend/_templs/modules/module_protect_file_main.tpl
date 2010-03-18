<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='file_descr'>
<input type='hidden' name='action' value='upd'>
<input type='hidden' name='type' value='file'>
<table>
<tr>
	<td>
    	Название
    </td>
	<td>
		<!--#slot src='_file'-->
    </td>
</tr>
<tr>
	<td>
    	Описание
    </td>
	<td>
		<input name = 'descr' value = '<!--#slot src='descr'-->'>
    </td>
</tr>
</table>
<input type='submit' value='Обновить'>
</form>

<!--#slot src='editor'-->

