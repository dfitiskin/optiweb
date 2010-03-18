<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="width:20" /></td>
    <td style="padding:10 0 20 0">
<!--#slot src='tree' link='list'-->
</td>
    <td></td>
  </tr>
</table>

<!--#partsep-->
<!--#list name='list'-->
<table border="0" width="100%" cellspacing="0" cellpadding="3">
<tr>
<td width="1%"></td>
<td width="99%"></td>
</tr>
<!--#elem-->
<!--#cond-->
return $_ds->getParam('_active') !==  $_ds->getParam('alias');
<!--#endcond-->
<tr>
<td valign="top">
<p><font color="#AAAAAA">&raquo;</font>
</td>
<td>
<p><a href='<!--#slot src='_url'--><!--#slot src='alias'-->/'><!--#slot src='name'--></a>
</td>
</tr>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('is_sublist');
<!--#endcond-->
<tr>
<td valign="top">
<p>&raquo;<b>
</td>
<td>
<p><a href='<!--#slot src='_url'--><!--#slot src='alias'-->/'><b><!--#slot src='name'--></b></a></b>
</td>
</tr>
<tr>
<td colspan="2">
<div style="padding-left:10">
<!--#slot src='sublist' link='list'-->
</div>
</td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
<td valign="top">
<p>&raquo;<b>
</td>
<td>
<a href='<!--#slot src='_url'--><!--#slot src='alias'-->/'><b><!--#slot src='name'--></b></a></b>
</td>
</tr>
<!--#endelem-->
</table>
<!--#endlist-->