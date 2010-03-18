<html>
<head>
	<meta http-equiv="Pragma" content="no-cache">
  <title><!--#slot src='title'--></title>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <script language="javascript" src="/scripts/htmleditor/utils.js"></script>
  <link rel="stylesheet" type="text/css" href="<!--#slot src='theme_url'-->css/imgdlg.css">
</head>

<body>

<SCRIPT type="text/javascript">

function LoadImage(url)
{

	if (top.window.opener && top.window.opener.addImgCode)
	{		
		top.window.opener.addImgCode(url);
	}
	top.window.close();
}
</SCRIPT>

<table border="0" width="100%" cellspacing="0" cellpadding="10" bgcolor="white">
  <tr>
    <td width="1%" valign="top">

<!--#list src='libs' name = "libs"-->
	<table border="0" width="100%" bordercolor="#C0C0C0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
	  <tr>
	    <td width="100%">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
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

	</td>
	<td width="98%" valign="top">

	<!--#slot link='files'-->

	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
	    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Загрузить изображения:</nobr></td>
	    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
	  </tr>
	  <tr>
	    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
	    <td width="98%" style="padding: 5">
	    <form method='post' enctype= "multipart/form-data">
	    <input type='hidden' name='object' value='htmleditor'>
	    <input type='hidden' name='mode' value='files'>
	    <input type='hidden' name='action' value='upload'>
	    <input type='hidden' name='unarchive' value='1'>
	<table border="0" width="100%" cellspacing="0" cellpadding="7">
	  <tr>
	    <td width="50%"><input class="inn" type='file' name='files[]'></td>
	    <td width="50%"><input class="inn" type='file' name='files[]'></td>
	  </tr>
	  <tr>
	    <td width="50%"><input style="margin: 0 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
	    <td width="50%"></td>
	  </tr>
	  </form>
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

	</td>
<td valign="top" width="1%">
    	<img id='preview' src='/data/htmleditor/dialog/spacer.gif'>
    	<div style="width:150px" />
</td>
</tr>
</table>

</body>
</html>
<!--#partsep-->



<!--#list src='images' name='files'-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Изображения:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 10">

<table width="100%" border=0>
<form method='post'>
<input type='hidden' name='object' value='htmleditor'>
<input type='hidden' name='mode' value='files'>
<input type='hidden' name='action' value='upd'>
<tr>
    <td><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td colspan="2"><p style="margin: 0; color: gray">Имя:</td>
    <td><p style="margin: 0; color: gray">Размер:</td>
    <td><p style="margin: 0; color: gray">Изменен:</td>
</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam("is_exists");
<!--#endcond-->
<tr>
    <td width="1%" id="t<!--#slot src='_current'-->"><input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}" type='checkbox' name='files[]' value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->'></td>
    <td width="1%"><img style="margin-right:3" src="/images/__backend/files/file-s-<!--#slot src='ext'-->.gif"></td>
    <td width="50%"><a target='_blank'  onclick='LoadImage("<!--#slot src='curr_url'--><!--#slot src='filename'-->"); return false;' href="<!--#slot src='curr_url'--><!--#slot src='filename'-->"><!--#slot src='name'-->.<!--#slot src='ext'--></a></td>
    <td width="20%"><!--#slot src='size_in_kb'-->&nbsp;Kb</td>
    <td width="28%"><!--#slot src='date'-->&nbsp;&nbsp;<!--#slot src='time'--></td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
<td style="padding:0" colspan="5"><input style="margin: 10 0 0 0" type='image' name="del" alt="Удалить" src="/images/__backend/common/but-del.gif"></td>
</tr>
</form>
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
<div style="height:20"></div>
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
    <td width="1%"><input style="margin: 0 0 0 4" type='image' name='del[<!--#slot src='root_dir'--><!--#slot src='dirname'-->]' onclick="return confirm('Вы хотите удалить <!--#slot src='dirname' filter='html'--> ?')" src="/images/__backend/struct/del.gif" width=11 height=11></td>
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
    <td width="97%"><a <!--#slot src='_switch' link='active'-->  href='<!--#slot src='_url'-->'> &nbsp;<nobr><!--#slot src='dirname'--></nobr></a></td>
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