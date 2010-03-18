<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Предупреждение:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">

<b style="color: #F00">Внимание! Для создания структуры необходимо создать профиль.</b>

    </td>
    <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
  </tr>
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-3.gif" width="9" height="4"></td>
    <td width="98%" style="background-position:bottom; background-repeat:repeat-x" background="/images/__backend/common/gray.gif"></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-4.gif" width="9" height="4"></td>
  </tr>
</table>

<!--#partsep-->


<!--#list name='navtypes'-->
<select name='upd[menu]'>
<option selected value=''>Не задана
<!--#elem-->
<!--#cond-->
return $_ds->getParam('alias') != $_ds->getParam('menu');
<!--#endcond-->
<option value='<!--#slot src='alias'-->'><!--#slot src='name'-->
<!--#endelem-->
<!--#elem-->
<option selected value='<!--#slot src='alias'-->'><!--#slot src='name'-->
<!--#endelem-->
</select>
<!--#endlist-->