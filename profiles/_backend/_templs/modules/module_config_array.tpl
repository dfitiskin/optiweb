<!--#list src='config'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='config'>
<input type='hidden' name='action' value='upd'>

<table>
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('type') == 's';
<!--#endcond-->
<tr>
	<td>
	<input type='checkbox' name='del[]' value='<!--#slot src='name'-->'> <!--#slot src='descr'--> (<!--#slot src='name'-->) <input size = '50' name='upd[<!--#slot src='name'-->][value]' value='<!--#slot src='value'-->'>
    </td>
</tr>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('type') == 'a';
<!--#endcond-->
<tr>
	<td>
	<input type='checkbox' name='del[]' value='<!--#slot src='name'-->'>  <a href='<!--#slot src='_url'--><!--#slot src='name'-->/'> <!--#slot src='descr'--> (<!--#slot src='name'-->)</a>
    </td>
</tr>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('type') == 'd';
<!--#endcond-->
<tr>
	<td>
	<input type='checkbox' name='del[]' value='<!--#slot src='name'-->'>  <a href='<!--#slot src='_url'--><!--#slot src='name'-->/'> <!--#slot src='descr'--> (<!--#slot src='name'-->)</a>
    </td>
</tr>
<!--#endelem-->

</table>
<input type='submit' value='���������'>
</form>
<!--#endlist-->

<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='config'>
<input type='hidden' name='action' value='add'>
<table>
<tr>
	<td>
    	��������
    </td>
	<td>
    	<input size = '50' name='name'>
    </td>
</tr>
<tr>
	<td>
    	��������
    </td>
	<td>
    	<input size = '50' name='descr'>
    </td>
</tr>
<tr>
	<td>
    	���
    </td>
	<td>
    	<select name='type'>
        	<option value='s'>������
            <option value='a'>������
            <option value='d'>������
        </select>
    </td>
</tr>
</table>
<input type='submit' value='��������'>
</form>
