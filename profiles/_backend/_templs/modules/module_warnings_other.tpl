<!--#list src='warnings'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings_other'>
<input type='hidden' name='action' value='upd'>
<table>
<tr>
	<td>
		������
    </td>
	<td>
    	���������
    </td>
</tr>
<!--#elem-->
<tr>
	<td>
    	<input type='checkbox' name="del[]" value = "<!--#slot src='name'-->">
		<!--#slot src='descr'-->
    </td>
	<td>
    	<input size = "50" name="upd[<!--#slot src='name'-->]" value = "<!--#slot src='value'-->">
    </td>
</tr>
<!--#endelem-->
</table>
<input type='submit' value='��������'>
</form>
<!--#endlist-->

<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings_other'>
<input type='hidden' name='action' value='add'>
<table>
<tr>
	<td>
		��������
    </td>
	<td>
    	��������
    </td>
</tr>
<tr>
	<td>
        <input name="add[name]" >
    </td>
	<td>
    	<input name="add[descr]">
    </td>
</tr>
</table>
<input type='submit' value='��������'>
</form>