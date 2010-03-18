<a href='<!--#slot src='_url'-->../'>Switch to users</a>
<!--#list src='groups'-->
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='objectname' -->'>
<input type='hidden' name='mode' value='<!--#slot src='mode' -->'>
<input type='hidden' name='action' value='del'>
<table align='center'>
<tr>
   <td>
      Навазние
   </td>
</tr>
<!--#elem-->
<tr>
   <td>
     <input type='checkbox' name='kill[]' value="<!--#slot src='id' -->"><!--#slot src='groupname' -->
   </td>
</tr>
<!--#endelem-->
<!--#alter-->
Профилей нету
<!--#endalter-->
<tr>
  <td align='center'>
   <input type='submit' value='Удалить'>
  </td>
</tr>
</form>
</table>
<!--#endlist-->




<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='objectname' -->'>
<input type='hidden' name='mode' value='<!--#slot src='mode' -->'>
<input type='hidden' name='action' value='add'>
<table align='center'>
<tr>
   <td>
	 Название
   </td>
   <td>
	 <input type='text' name='add[groupname]' value='<!--#slot src='groupname' -->'>
   </td>
</tr>
<tr>
  <td colspan='2' align='center'>
   <input type='submit' value='Добавить'>
  </td>
</tr>
</table>
</form>

<!--#list src='warnings'-->
<table align='center'>
<!--#elem-->
<tr>
   <td>
	<!--#slot src='message'-->

   </td>
</tr>
<!--#endelem-->
</table>
<!--#endlist-->