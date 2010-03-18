<form method='post'>
	<input type='hidden' name='object' value='templates'>
	<input type='hidden' name='mode' value='main'>
	<input type='hidden' name='action' value='edit'>
	<table width="100%" height="100%" cellspacing="10" cellpadding="0" bgcolor="#E3E0DB">
		<tr>
			<td>
				Название: <b><!--#slot src='filename'--></b> (<!--#slot src='_switch' link='lib'-->)
			</td>
		</tr>
		<tr>
    		<td>
    			Описание:
    		</td>
		</tr>
		<tr>
    		<td>
    			<input onfocus="document.all('save').disabled=false" style="width:100%" name='desript' value='<!--#slot src='descript'-->'>
    		</td>
		</tr>
		<tr>
    		<td>
    			Содержание:
    		</td>
		</tr>
				
		<tr height="97%">
    		<td>
    			<textarea style="width: 100%;height: 100%;font-size: 12px;font-family:courier new,serif" onfocus="document.all('save').disabled=false" name='content'><!--#slot src='content' filter='html'--></textarea>
    		</td>
		</tr>
		<tr height="1%">
    		<td>
      			<p style="margin: 7 0 7 0" align=center>
      				<input type='submit' name="save" disabled="true" value='Сохранить'>&nbsp;&nbsp;&nbsp;
      				<input type='button' onclick="window.close()" value='Закрыть'>
      			</p>
    		</td>
		</tr>
	</table>
</form>
<!--#partsep-->

<!--#list name='lib'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam("library") == "_general";
<!--#endcond-->
Общая библиотека
<!--#endelem-->
<!--#elem-->
Библиотека профиля
<!--#endelem-->
<!--#endlist-->