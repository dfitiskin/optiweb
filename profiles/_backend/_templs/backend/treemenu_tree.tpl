<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img src="/images/1.gif" width="20" height="1"></td>
    <td width="99%">
<!--#list src='libs'-->
<table border="0" width="100%" bordercolor="#C0C0C0" cellspacing="0" cellpadding="10" style="border-collapse:collapse">
  <tr>
    <td width="100%">
<table border="0" cellspacing="0" cellpadding="0">
<!--#elem-->
<!--#cond-->
return $_ds->getParam('opened_lib') != $_ds->getParam('alias');
<!--#endcond-->
<tr>
  <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src='/images/__backend/struct/folder-o.gif' width="18" height="18"></td>
    <td width="99%">&nbsp;<a style="text-decoration:none; color:black;"  href='<!--#slot src='_url'--><!--#slot src='alias'-->/'><nobr><!--#slot src='name'--></nobr></a></td>
  </tr>
</table>
  </td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
  <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src='/images/__backend/struct/folder-c.gif' width="18" height="18"></td>
    <td width="99%">&nbsp;<a <!--#slot src='_switch' link='active_lib'--> href='<!--#slot src='_url'--><!--#slot src='alias'-->/'><nobr><!--#slot src='name'--></nobr></a></td>
  </tr>
</table>
  </td>
</tr>
	    <form method='post'>
	    <input type='hidden' name='object' value='<!--#slot src='object_name'-->'>
	    <input type='hidden' name='mode' value='dirs'>
	    <input type='hidden' name='action' value='del'>

<tr>
	<td>
    	<!--#slot src='dirtree' link='dirs'-->
	</td>
</tr>
	    </form>
<!--#endelem-->
</table>
	</td>
  </tr>
</table>
<!--#endlist-->
<!--#slot link='adddir'-->
</td>
</tr>
</table>
<!--#partsep-->

<!--#list src='_switch' name='adddir'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('opened_lib');
<!--#endcond-->
<table class="outer">
<tr>
<td>
<div class="gray_block2">
    <p class="frame_head"><nobr>Создание папки:</nobr></p>
    <div class="clear"></div>
	<form method='post'>
	<input type='hidden' name='object' value='<!--#slot src='object_name'-->'>
	<input type='hidden' name='mode' value='dirs'>
	<input type='hidden' name='action' value='make'>
	<p style="margin: 0 0 0 0">Название:</p>
       	<input name='dirname' class="inn">
        <input type='image' style="margin: 10 0 0 0" name='ins' alt="Добавить" src="/images/__backend/common/but-add.gif">
	</form>

</div>
</td>
</tr>
</table>
<!--#endelem-->
<!--#endlist-->



//------------------------------------------------------------------------------------------------
//  Рекурсивный список дирректорий
//------------------------------------------------------------------------------------------------
<!--#list src='subdir' name='dirs'-->
<!--#elem-->
<!--#cond-->
return !$_ds->getParam('_is_opened');
<!--#endcond-->
<table width=100% border="0" bordercolor="green" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
<tr>
 <td width="1%" background="/images/__backend/struct/line-back.gif"><!--#slot src='_switch' link='corner'--></td>
 <td width="99%">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><a <!--#slot src='_switch' link='active'-->  href='<!--#slot src='_url'-->'><!--#slot src='_switch' link='node_open'--></a></td>
    <td width="97%">&nbsp;<a <!--#slot src='_switch' link='active'--> href='<!--#slot src='_url'-->'><nobr><!--#slot src='dirname'--></nobr></a></td>
    <td width="1%"><input class="checker" style="margin: 0 0 0 4" type='image' name='del[<!--#slot src='root_dir'--><!--#slot src='dirname'-->]' onclick="return confirm('Вы хотите удалить <!--#slot src='dirname' filter='html'--> ?')" src="/images/__backend/struct/del.gif" width=11 height=11></td>
  </tr>
</table>
 </td>
</tr>
</table>
<!--#endelem-->
<!--#elem-->
<table width=100% border="0" bordercolor="green" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
<tr>
 <td width="1%" background="/images/__backend/struct/line-back.gif"><!--#slot src='_switch' link='corner'--></td>
 <td width="99%">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><a <!--#slot src='_switch' link='active'-->  href='<!--#slot src='_url'-->'> <!--#slot src='_switch' link='node_open'--></a></td>
    <td width="97%"><a <!--#slot src='_switch' link='active'-->  href='<!--#slot src='_url'-->'> &nbsp;123<nobr><!--#slot src='dirname'--></nobr></a></td>
    <td width="1%"><input style="margin: 0 0 0 4" type='image' name='del[<!--#slot src='root_dir'--><!--#slot src='dirname'-->]' onclick="return confirm('Вы хотите удалить <!--#slot src='dirname' filter='html'--> ?')" src="/images/__backend/struct/del.gif" width=11 height=11></td>
  </tr>
</table>
 </td>
</tr>
</table>
<div style="display:block;">
<table width=100% border="0" bordercolor="green" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
<tr>
<td width="1%" <!--#slot src='_switch' link='back'-->><img src="/images/__backend/struct/1.gif" width=18 height=1></td>
<td width="99%">
    <!--#slot link='dirs'-->
    </td>
</tr>
</table>
</div>
<!--#endelem-->
<!--#endlist-->



//------------------------------------------------------------------------------------------------
// Определение активной библиотеки
//------------------------------------------------------------------------------------------------
<!--#list name='active_lib'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('active_lib');
<!--#endcond-->
style="text-decoration:none; color:black; font-weight: bold;"
<!--#endelem-->
<!--#elem-->
style="text-decoration:none; color:black;"
<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Определение активного пункта
//------------------------------------------------------------------------------------------------
<!--#list name='active'-->
<!--#elem-->
<!--#cond-->
return !$_ds->GetParam('_is_active');
<!--#endcond-->
style="text-decoration:none; color:black;"
<!--#endelem-->
<!--#elem-->
style="text-decoration:none; color:black; font-weight: bold;"
<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
// Закрытый или открытый список подразделов
//------------------------------------------------------------------------------------------------
<!--#list name='node_open'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_is_active');
<!--#endcond-->
<img style="cursor:hand" border="0" src='/images/__backend/struct/folder-c.gif' width="18" height="18">
<!--#endelem-->
<!--#elem-->
<img style="cursor:hand" border="0" src='/images/__backend/struct/folder-o.gif' width="18" height="18">
<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
// Закрытый или открытый список подразделов
//------------------------------------------------------------------------------------------------
<!--#list name='style'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_is_active');
<!--#endcond-->style="display:block;"<!--#endelem-->
<!--#elem-->style="display:none"<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
// Уголки в линиях
//------------------------------------------------------------------------------------------------


<!--#list name='corner'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_is_terminal');
<!--#endcond-->
<img border="0" src="/images/__backend/struct/c-none-<!--#slot src='_switch' link='node_type'-->.gif" width="18" height="18">
<!--#endelem-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_is_active');
<!--#endcond-->
<img style="cursor:hand" border="0" src="/images/__backend/struct/c-minus-<!--#slot src='_switch' link='node_type'-->.gif" width="18" height="18">
<!--#endelem-->
<!--#elem-->
<img style="cursor:hand" border="0" src="/images/__backend/struct/c-plus-<!--#slot src='_switch' link='node_type'-->.gif" width="18" height="18">
<!--#endelem-->
<!--#endlist-->


//------------------------------------------------------------------------------------------------
// Определение типа папочки
//------------------------------------------------------------------------------------------------
<!--#list name='node_type'-->
<!--#elem--><!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current') && $_ds->GetParam('_current')==1;
<!--#endcond-->corner<!--#endelem-->
<!--#elem--><!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current');
<!--#endcond-->corner<!--#endelem-->
<!--#elem-->cross<!--#endelem-->
<!--#endlist-->

//------------------------------------------------------------------------------------------------
// Послежний или не последний элемент (для фоновой линии)
//------------------------------------------------------------------------------------------------
<!--#list name='back'-->
<!--#elem-->
<!--#cond-->
return $_ds->GetParam('_count')==$_ds->GetParam('_current');
<!--#endcond-->
bgcolor="white"
<!--#endelem-->
<!--#elem-->
background="/images/__backend/struct/line-back.gif"
<!--#endelem-->
<!--#endlist-->