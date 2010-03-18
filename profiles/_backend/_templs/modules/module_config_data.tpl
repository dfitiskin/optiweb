<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='data_config'>
<input type='hidden' name='action' value='upd'>
<table border='1'>

<!--#list src='header'-->
<tr>
	<td>
	</td>
<!--#elem-->
	<td>
		<input type='checkbox' name='del_col[]' value='<!--#slot src='name'-->'><!--#slot src='descr'-->
	</td>
<!--#endelem-->
</tr>
<!--#endlist-->

<!--#list src='data'-->
<!--#elem-->
    <tr>
	    <td>
	        <input type='checkbox' name='del_row[]' value='<!--#slot src='_current'-->'>
	    </td>
	<!--#list src='row'-->
    <!--#elem-->
    <!--#cond-->
   return $_ds->GetParam('type') == 's';
    <!--#endcond-->
	    <td>
	        <!--#slot src='value'-->
	    </td>
    <!--#endelem-->
    <!--#elem-->
	    <td>
	        <a href="<!--#slot src='_url'--><!--#slot src='_parent_current'-->/<!--#slot src='name'-->/">>>></a>
	    </td>
    <!--#endelem-->
    <!--#endlist-->
    </tr>
<!--#endelem-->
<!--#endlist-->
</table>
<input type='submit' value='Удалить'>
</form>

Добавить строку
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='data_config'>
<input type='hidden' name='action' value='addrow'>
<table>
<!--#list src='header'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('type') == 's';
<!--#endcond-->
<tr>
	<td>
		<!--#slot src='descr'-->
	</td>
	<td>
    	<input name='add[<!--#slot src='name'-->]'>
	</td>
</tr>
<!--#endelem-->
<!--#endlist-->
</table>
<input type='submit' value='Добавить'>
</form>

Добавить Колонку
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='data_config'>
<input type='hidden' name='action' value='addcol'>
<table>
<tr>
	<td>
    	Название
    </td>
	<td>
    	<input size = '50' name='name'>
    </td>
</tr>
<tr>
	<td>
    	Описание
    </td>
	<td>
    	<input size = '50' name='descr'>
    </td>
</tr>
<tr>
	<td>
    	Тип
    </td>
	<td>
    	<select name='type'>
        	<option value='s'>Скаляр
            <option value='a'>Массив
            <option value='d'>Данные
        </select>
    </td>
</tr>
</table>
<input type='submit' value='Добавить'>
</form>