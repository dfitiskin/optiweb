<!--#list src='fields'-->
<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings'>
<input type='hidden' name='action' value='del'>
<table>
<!--#elem-->
<tr>
	<td>
    <input type='checkbox' name='del[]' value='<!--#slot src='name'-->'>
	<a href='<!--#slot src='_url'--><!--#slot src='name'-->/'><!--#slot src='descr'--> (<!--#slot src='name'-->)</a>
	</td>
</tr>
<!--#endelem-->
</table>
<input type='submit' value='�������'>
</form>
<!--#endlist-->


<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='warnings'>
<input type='hidden' name='action' value='add'>
<table>
<tr>
	<td>
    	��������
    </td>
	<td>
    	<input size = '50' name='add[name]'>
    </td>
</tr>
<tr>
	<td>
    	��������
    </td>
	<td>
    	<input size = '50' name='add[descr]'>
    </td>
</tr>
</table>
<input type='submit' value='��������'>
</form>



<a href='<!--#slot src='_url'-->_other/'>������ ��������������</a>