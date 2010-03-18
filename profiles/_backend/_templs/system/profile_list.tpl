
<div class="gray_block">
<p class="frame_head"><nobr>Профили:</nobr></p>
<div class="clear"></div>
   
<!--#list src='profiles'-->



<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='object'-->'>
<input type='hidden' name='mode' value='manage'>
<input type='hidden' name='action' value='del'>

<table>
<tr>
    <td width="1%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td width="49%"><p style="margin: 0; color: gray">Название:</td>
    <td width="50%"><p style="margin: 0; color: gray">Псевдоним:</td>
</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#EEEEEE" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
        <td>
                <input type='checkbox' name='kill[]' value="<!--#slot src='id' -->">
        </td>
        <td>
          <a href='<!--#slot src='_url' --><!--#slot src='alias' -->/'><!--#slot src='name' --></a>
        </td>
        <td>
          <!--#slot src='alias' -->
        </td>
</tr>
<!--#endelem-->
<!--#alter-->
<table width="100%" border=0>
<tr>
    <b style="text-align: center; color: #F00;">В системе нет профилей</b>
</tr>
</table>

</td>
    <td width="1%" style="background-position:right; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
  </tr>

</table>

<div style="height:20"></div>

<!--#endalter-->

<tr>
<td>
<input style="margin: 10 0 0 0" type='image' alt="Удалить" src="/images/__backend/common/but-del.gif"></td>
</tr>

</table>

</form>


</div>

<div style="height:20"></div>

<!--#endlist-->

<div class="gray_block">
<p class="frame_head"><nobr>Создание нового профиля:</nobr></p>
<div class="clear"></div>

<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='object'-->'>
<input type='hidden' name='mode' value='manage'>
<input type='hidden' name='action' value='add'>

<table>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Действие:</b></nobr></td>
  <td>
        <select name='source'>
        <option value='new'>Новый
        <!--#slot link='options'-->
        </select>
  </td>
</tr>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Псевдоним:</b></nobr></td>
  <td><input class="inn" name='add[alias]' value="<!--#slot src='alias' -->"></td>
</tr>
<tr>
  <td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>Название:</b></nobr></td>
  <td><input class="inn" name='add[name]' value='<!--#slot src='name' -->'></td>
</tr>
<tr>
<td></td>
<td><input style="margin: 5 0 0 0" type='image' alt="Добавить" src="/images/__backend/common/but-add.gif"></td>
</tr>

</table>
</form>

</div>

<!--#list src='warnings'-->
<table>
<!--#elem-->
<tr>
   <td>
        <!--#slot src='message'-->

   </td>
</tr>
<!--#endelem-->
</table>
<!--#endlist-->

<!--#partsep-->

<!--#list src='profiles' name='options'-->
<!--#elem-->
<option value="copy_<!--#slot src='id'-->"> Копировать из профиля "<!--#slot src='name'-->"
<!--#endelem-->
<!--#endlist-->