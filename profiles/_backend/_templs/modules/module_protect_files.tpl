<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='file_descr'>
<input type='hidden' name='action' value='upd'>
<input type='hidden' name='type' value='lib'>
<table>
<tr>
	<td>
    	Название
    </td>
	<td>
		<!--#slot src='_lib'-->
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

<hr>

<!--#list src='folder'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='file_mng'>
<input type='hidden' name='action' value='del'>
<table>
<!--#elem-->
<tr>
	<td>
    <input type='checkbox' name='del[]' value='<!--#slot src='filename'-->'>
	<a href='<!--#slot src='_url'--><!--#slot src='filename'-->/'><!--#slot src='descr'--></a>
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
<input type='hidden' name='mode' value='file_mng'>
<input type='hidden' name='action' value='add'>
<table>
<tr>
	<td>
		Тип
    </td>
	<td>
		<select name='add[type]'>
        <option value='c'>Конфигурация
        <option value='w'>Предупреждения
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
