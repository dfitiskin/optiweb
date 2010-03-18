<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Параметры раздела:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 7">

<table border="0" width="90%" cellspacing="5" cellpadding="0">

<form method='post'>
<input type='hidden' name='object' value='struct'>
<input type='hidden' name='mode' value='node_manage'>
<input type='hidden' name='action' value='upd'>
<input type='hidden' name='id' value='<!--#slot src='id'-->'>
<!--#list src='_switch'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('level') > 1;
<!--#endcond-->
<tr>
   <td>
        <p style="margin: 7 0 0 0">Псевдоним:</p>
        <input class="inn" style="width:200" size = '59' name='upd[alias]' value="<!--#slot src='alias' filter='html'-->">
    </td>
</tr>
<!--#endelem-->
<!--#endlist-->
<tr>
   <td>
<p style="margin: 7 0 0 0">Короткое название:</p>
<input class="inn" style="width:200" size = '59' name='upd[name]' value="<!--#slot src='name' filter='html'-->"></td>
</tr>
<tr>
   <td>
<p style="margin: 7 0 0 0">Полное название:</p>
	<input class="inn" style="width:200" size = '59' name='upd[fullname]' value="<!--#slot src='fullname' filter='html'-->">
   </td>
</tr>
<tr>
   <td>
<p style="margin: 7 0 0 0">Ссылка:</p>
	<input class="inn" style="width:200" size = '59' name='upd[url]' value="<!--#slot src='url'-->">
   </td>
</tr>
<tr>
   <td>
	<p style="margin: 7 0 0 0">Навигация:&nbsp;<!--#slot src='navtypes' link='navtypes'-->
	<img style="cursor:hand" onclick="window.open('/_backend/struct/_navigation/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">
   </td>
</tr>

<tr>
   <td>
<p style="margin: 10 0 0 0"><input type='image' alt='Сохранить' src="/images/__backend/common/but-save.gif"></p>
   </td>
</tr>
</form>
<tr>
   <td>
   </td>
</tr>
</table>
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