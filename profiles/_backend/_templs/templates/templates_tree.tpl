<!--#list src='groups'-->
<table border="1" width="100%" bordercolor="#C0C0C0" cellspacing="0" cellpadding="10" style="border-collapse:collapse">
  <tr>
    <td width="100%">
<table>
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('active') ==  $_ds->GetParam('alias');
<!--#endcond-->
<tr>
  <td width="1%">&raquo;</td>
  <td width="99%">
        <nobr><!--#slot src='name'--></nobr>
  </td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
  <td width="1%">
  </td>
  <td width="99%">
        <a href="/_backend/templates/_list/<!--#slot src='alias'-->/"><nobr><!--#slot src='name'--></nobr></a>
  </td>
</tr>
<!--#endelem-->
</table>
  </td>
</tr>
</table>
<!--#endlist-->