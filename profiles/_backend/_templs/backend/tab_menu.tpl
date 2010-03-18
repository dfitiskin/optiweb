<!--#list src='tabmenu'-->
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
  	<td>
	<img src="/images/__backend/common/tabmenu-a-sep.gif" width="1" height="26"></td>
<!--#sep-->
	<td>
	<img src="/images/__backend/common/tabmenu-a-sep.gif" width="1" height="26"></td>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('mode') != $_ds->GetParam('activemode');
<!--#endcond-->
	<td background="/images/__backend/common/tabmenu-d-b.gif">
	<p style="margin: 0 20 3 20"><a class="tabmenu" href='<!--#slot src='baseurl'--><!--#slot src='alias'--><!--#slot src='url'-->'><!--#slot src='name'--></a></p>
	</td>	
<!--#endelem-->
<!--#elem-->
	<td background="/images/__backend/common/tabmenu-a-b.gif" bgcolor="#2D94FF">
	<p style="margin: 0 20 3 20"><a class="tabmenua" href='<!--#slot src='baseurl'--><!--#slot src='alias'--><!--#slot src='url'-->'><!--#slot src='name'--></a></p>
	</td>
<!--#endelem-->
	<td>
	<img src="/images/__backend/common/tabmenu-a-sep.gif" width="1" height="26"></td>
</tr>
</table>
<!--#endlist-->
