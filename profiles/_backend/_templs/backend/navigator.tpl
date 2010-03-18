<table width="100%" border=0>
<tr>
<td width="1%"><img border=0 src="/images/__backend/1.gif" width="15" height="1"></td>
<td width="96%">
<!--#list src='mainlist'-->
<table width="90%" border="0">
<tr>
<!--#sep-->
<!--#cond-->
return $_ds->GetParam('_current') % 4 == 1 && $_ds->GetParam('perms') != 2;
<!--#endcond-->
</tr>
<tr>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return ($_ds->GetParam('active') == $_ds->GetParam('alias')) && $_ds->GetParam('perms') != 2;
<!--#endcond-->
<td>
<table border="0" bgcolor='#E1F2FF' width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%"><img src="/images/__backend/common/<!--#slot src='alias'-->.gif"></td>
    <td width="99%"><a class="nav_link_a" href="/_backend/<!--#slot src='alias'-->/"><!--#slot src='name'--></a></td>
  </tr>
</table>
</td>
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('perms') != 2;
<!--#endcond-->
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%"><img src="/images/__backend/common/<!--#slot src='alias'-->.gif"></td>
    <td width="99%"><a class="nav_link" href="/_backend/<!--#slot src='alias'-->/"><!--#slot src='name'--></a></td>
  </tr>
</table>
</td>
<!--#endelem-->
</tr></table>
<!--#endlist-->
</td>
    <td width="1%">
            <table border="0" width="100%" cellspacing="7" cellpadding="0">
              <tr>
                <td width="100%" bgcolor="#FFFFFF"><!--<img border=0 src="/images/__backend/common/profile-sep.gif" width="1" height="51">--></td>
              </tr>
            </table>
    </td>
    <td width="1%">
        <!--#slot link='profiles'-->
    </td>
<td width="1%"><img border=0 src="/images/__backend/1.gif" width="15" height="1"></td>
</tr>
</table>

<!--#partsep-->

<!--#list src='sublist' name='sublist' -->
        <table border="0" width="100%" cellspacing="0" cellpadding="3">
           <!--#elem-->
                 <!--#cond-->
                return ($_ds->GetParam('active') == $_ds->GetParam('alias'));
              <!--#endcond-->
                <tr>
                   <td>
<table border="0" bgcolor='#E1F2FF' width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%"><img src="/images/__backend/common/<!--#slot src='alias'-->.gif"></td>
    <td width="99%"><a class="nav_link_a" href='<!--#slot src='url'-->'><!--#slot src='name'--></a></a></td>
  </tr>
</table>
                    </td>
                </tr>
           <!--#endelem-->
           <!--#elem-->
                <tr>
                   <td>
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%"><img src="/images/__backend/common/<!--#slot src='alias'-->.gif"></td>
    <td width="99%"><a class="nav_link" href='<!--#slot src='url'-->'><!--#slot src='name'--></a></td>
  </tr>
</table>
                    </td>
                </tr>
           <!--#endelem-->
        </table>
<!--#endlist-->

<!--#list src='profiles' name='profiles' -->
      <form method='post'>
      <input type='hidden' name='object' value='backend'>
      <input type='hidden' name='mode' value='auth'>
      <input type='hidden' name='action' value='change_profile'>
      <br />
      <table border=0 cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2"><p style="margin: 0 0 4 0; color:#4F7693">Профиль:</p></td>
        </tr>
        <tr>
        <td>
         <select name='profile' style="width:120;">
           <!--#elem-->
                 <!--#cond-->
                return $_ds->GetParam('active');
              <!--#endcond-->
            <option value="<!--#slot src='id' -->" SELECTED><!--#slot src='name' -->
           <!--#endelem-->
           <!--#elem-->
            <option value="<!--#slot src='id' -->"><!--#slot src='name' -->
           <!--#endelem-->
        </select>
        </td>
        <td><input style="margin: 0 0 0 3" type='image' alt="Применить" src="/images/__backend/common/button-ok1.gif"></td>
      </tr>
      </table>
      </form>
<!--#endlist-->