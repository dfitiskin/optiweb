<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings_field'>
<input type='hidden' name='action' value='upd_filter'>
<table>
<tr>
	<td>
    	Правила
    </td>
	<td>
    	<input size = '40' name='ruls' value='<!--#slot src='ruls'-->'>
    </td>
</tr>
</table>
<input type='submit' value='Обновить'>
</form>

<!--#list src='fields'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings_field'>
<input type='hidden' name='action' value='upd_fields'>
<table>
<tr>
	<td>
		Ошибка
    </td>
	<td>
    	Сообщение
    </td>
</tr>
<!--#elem-->
<tr>
	<td>
		<!--#slot src='descr'-->
    </td>
	<td>
    	<input size = "50" name="upd[<!--#slot src='name'-->]" value = "<!--#slot src='value'-->">
    </td>
</tr>
<!--#endelem-->
</table>
<input type='submit' value='Обновить'>
</form>
<!--#endlist-->