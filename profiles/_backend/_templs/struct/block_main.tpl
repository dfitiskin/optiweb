<div class="gray_block">

<p class="frame_head"><nobr>Параметры раздела:</nobr></p>
<div class="clear"></div>


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

        <p style="margin: 7 0 0 0">Псевдоним:</p>
        <input class="inn" style="width:200" size = '59' name='upd[alias]' value="<!--#slot src='alias' filter='html'-->">

<!--#endelem-->
<!--#endlist-->

        <p style="margin: 7 0 0 0">Короткое название:</p>
        <input class="inn" size = '59' name='upd[name]' value="<!--#slot src='name' filter='html'-->">

        <p style="margin: 7 0 0 0">Полное название:</p>
        <input class="inn" size = '59' name='upd[fullname]' value="<!--#slot src='fullname' filter='html'-->">

        <p style="margin: 7 0 0 0">Навигация:&nbsp;<!--#slot src='navtypes' link='navtypes'-->
        <img style="cursor:hand" onclick="window.open('/_backend/struct/_navigation/',null,'width=600,height=500,menubar=0,scrollbars=1,resizable=1')" alt="Редактировать" src="/images/__backend/struct/edit.gif" width="16" height="14">

        <p style="margin: 10 0 0 0"><input type='image' alt='Сохранить' src="/images/__backend/common/but-save.gif"></p>

</form>

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





<!--#list name='link_node'-->
<select name='upd[link]'>
<option value=''>Не задана
<!--#elem-->
<!--#slot link='sublist'-->
<!--#endelem-->
</select>
<!--#endlist-->


<!--#list src="sublist" name='sublist'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type') == 0;
<!--#endcond-->
<option <!--#slot link='selected'--> value='<!--#slot src='_url'-->'><!--#slot src='str_offset'-->><!--#slot src='name'--></option>
<!--#slot link='sublist'-->
<!--#endelem-->
<!--#endlist-->


<!--#list src="_switch" name='selected'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('node_link') == $_ds->getParam('_url');
<!--#endcond-->
selected
<!--#endelem-->
<!--#endlist-->