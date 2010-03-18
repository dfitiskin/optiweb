<table width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
<table>
<!--#list src='tree'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('active');
<!--#endcond-->
<tr>
  <td width="1%">
  <p>&raquo;</p>
  </td>
  <td width="99%">
        <p><a href="/_backend/modules/<!--#slot src='alias'-->/"><nobr><b><!--#slot src='name'--></b></nobr></a></p>
  </td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
  <td width="1%">
  <p><font color="#AAAAAA">&raquo;</font>
  </td>
  <td width="99%">
        <p><a href="/_backend/modules/<!--#slot src='alias'-->/"><nobr><!--#slot src='name'--></nobr></a></p>
  </td>
</tr>
<!--#endelem-->
<!--#endlist-->

<!--#list src='_switch'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('current') != '_setup';
<!--#endcond-->
<tr>
  <td width="1%">
  <p><font color="#AAAAAA">&raquo;</font></p>
  </td>
  <td width="99%">
  <p><a href='/_backend/modules/_setup/'>Установка</a></p>
  </td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
  <td width="1%">
  <p>&raquo;</p>
  </td>
  <td width="99%">
  <p><a href='/_backend/modules/_setup/'><b>Установка</b></a></p>
  </td>
</tr>
<!--#endelem-->
<!--#endlist-->
</table>
  </td>
</tr>
</table>
