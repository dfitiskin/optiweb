<!--#list src='images' -->
<!--#elem-->
<!--#cond-->
return $_ds->getParam("is_exists");
<!--#endcond-->
<div style="width: 200px; height: 300px; float: left; padding: 5px; margin-right: 20px; overflow: hidden;">
    <div style="width: 200px; height: 200px;">
        <a href="<!--#slot src='edit_url'--><!--#slot src='filename'-->">
            <img src="http://optiweb.extra.web/_terminal/image/cut/200/200<!--#slot src='root_url'--><!--#slot src='filename'-->" border="0"/>
        </a>
    </div>
    <a href="<!--#slot src='edit_url'--><!--#slot src='filename'-->"><!--#slot src='filename'--></a><br />
    Размер <!--#slot src='size_in_kb'-->&nbsp;Kb<br />
    Создан <!--#slot src='date'--> <!--#slot src='time'-->
</div>
<!-- 
    <!--#slot src='curr_dir'--><!--#slot src='filename'-->
    <td width="1%"><img style="margin-right:3" src="/images/__backend/files/file-s-<!--#slot src='ext'-->.gif"></td>
    <!--#slot src='edit_url'--><!--#slot src='filename'-->
    <!--#slot src='name'-->.<!--#slot src='ext'-->
    <!--#slot src='size_in_kb'-->&nbsp;Kb
    <!--#slot src='date'-->
    <!--#slot src='time'-->
 -->
<!--#endelem-->
<!--#endlist-->
<br style="clear: both;" />
<br/>
<br/>
<br/>
<br/>
<br/>
<!--#list src='images' -->
<table border="0" width="90%" cellspacing="0" cellpadding="0">
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
<input type='hidden' name='object' value='images'>
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
    <td width="50%"><a target='_blank' onclick="window.open('<!--#slot src='edit_url'--><!--#slot src='filename'-->',0,'width=550,height=300,menubar=0,scrollbars=1,resizable=1'); return false" href='<!--#slot src='edit_url'--><!--#slot src='filename'-->'><!--#slot src='name'-->.<!--#slot src='ext'--></a></td>
    <td width="20%"><!--#slot src='size_in_kb'-->&nbsp;Kb</td>
    <td width="28%"><!--#slot src='date'-->&nbsp;&nbsp;<!--#slot src='time'--></td>
</tr>
<!--#endelem-->
<!--#elem-->
<tr>
    <td id="t<!--#slot src='_current'-->"><input onclick="if(!this.checked) {document.all('t<!--#slot src='_current'-->').bgColor = 'transparent';} else {document.all('t<!--#slot src='_current'-->').bgColor='#F9595E'}"  type='checkbox' name='files[]' value='<!--#slot src='curr_dir'--><!--#slot src='filename'-->'></td>
    <td><img style="margin-right:3" src="/images/__backend/files/file-s-other.gif"></td>
    <td><!--#slot src='name'-->.<!--#slot src='ext'--></td>
    <td><!--#slot src='size_in_kb'-->&nbsp;Kb</td>
    <td><!--#slot src='date'-->&nbsp;&nbsp;<!--#slot src='time'--></td>
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
<!--#endlist-->
<div style="height:20"></div>
<div class="gray_block">
<p class="frame_head"><nobr>Загрузить изображения:</nobr></p>
<div class="clear"></div>
	<form method='post' enctype= "multipart/form-data">
    	<input type='hidden' name='object' value='images'>
	<input type='hidden' name='mode' value='files'>
	<input type='hidden' name='action' value='upload'>
        <input type='hidden' name='unarchive' value='1'>
<table border="0" width="100%" cellspacing="0" cellpadding="7">
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
    <td width="50%"><input class="inn" type='file' name='files[]'></td>
  </tr>
  <tr>
    <td width="50%"><input style="margin: 0 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
    <td width="50%"></td>
  </tr>  
</table>
</form>
</div>

