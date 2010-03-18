<div style="margin: 0 20 0 0">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
<td>
<p style="margin: 2 0 0 0"><a <!--#slot link='has_link'--> class="menu_pers"><nobr><!--#slot src='name'--> (<!--#slot src='login'-->)</nobr></a></p>
</td>
<!--#list src='menu'-->
<td><img style="margin: 12 10 8 10" src="/images/__backend/common/personal-sep.gif"></td>
<!--#sep-->
<td><img style="margin: 12 10 8 10" src="/images/__backend/common/personal-sep.gif"></td>
<!--#endsep-->
<!--#elem-->
<td>
<p style="margin: 2 0 0 0"><a class="menu_pers" href='<!--#slot src='url'-->'><nobr><!--#slot src='name'--></nobr></a></p>
</td>
<!--#endelem-->
<!--#endlist-->
</tr>
</table>
</div>


<!--#partsep-->

//------------------------------------------------------------------------------
//
//------------------------------------------------------------------------------
<!--#list src='_switch' name='has_link'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('system_access') && $_ds->getParam('login') !== 'root';
<!--#endcond-->
href="/_backend/system/access/users/<!--#slot src='login'-->/"
<!--#endelem-->
<!--#endlist-->