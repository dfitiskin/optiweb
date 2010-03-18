<!--#list src='configs'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='lib_mng'>
<input type='hidden' name='action' value='del'>
<table>
<!--#elem-->
<tr>
	<td>
     <input type='checkbox' name='del[]' value='<!--#slot src='folder'-->'>
	 <a href='<!--#slot src='_url'--><!--#slot src='folder'-->/'><!--#slot src='descr'-->
    </td>
</tr>
<!--#endelem-->
</table>
<input type='submit' value='Удалить'>
</form>
<!--#endlist-->
<hr>
<!--#list src='services'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='lib_mng'>
<input type='hidden' name='action' value='del'>
<table>
<!--#elem-->
<tr>
	<td>
    <input type='checkbox' name='del[]' value='<!--#slot src='folder'-->'>
	<a href='<!--#slot src='_url'--><!--#slot src='folder'-->/'><!--#slot src='descr'-->
    </td>
</tr>
<!--#endelem-->
</table>
<input type='submit' value='Удалить'>
</form>
<!--#endlist-->

<hr>

<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='lib_mng'>
<input type='hidden' name='action' value='add'>
<table>
<tr>
	<td>
		Тип
    </td>
	<td>
		<select name='add[type]'>
        <option value=''>Конфигурация
        <option value='_inter'>для Интрактива
        <option value='_services'>для Сервиса
        </select>
    </td>
</tr>
<tr>
	<td>
		Название
    </td>
	<td>
		<input name='add[filename]'>
    </td>
</tr>
<tr>
	<td>
		Описание
    </td>
	<td>
		<input name='add[descr]'>
    </td>
</tr>
</table>
<input type='submit' value='Добавить'>
</form>
