<!--#list src='versions'-->
<div class="gray_block">
<p class="frame_head"><nobr>������ ������:</nobr></p>
<div class="clear"></div>

<form method="post">
<input type='hidden' name='object' value='inter'>
<input type='hidden' name='mode' value='version'>
<input type='hidden' name='action' value='del'>
<table width="100%">
	<tr>
	    <td width="5%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
	    <td width="35%"><p style="margin: 0; color: gray">��������:</td>
	    <td width="30%"><p style="margin: 0; color: gray">���������:</td>
	    <td width="15%"><p style="margin: 0; color: gray">���:</td>
	    <td width="15%"><p style="margin: 0; color: gray">����:</td>
	</tr>
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#CCCCCC" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
   <td><input type='checkbox' name='del[]' value='<!--#slot src='id'-->'></td>
   <td><nobr><b><!--#slot src='name'--></b></nobr></td>
   <td><nobr><!--#slot src='alias'--></nobr></td>
   <td><nobr><!--#slot link='type'--></nobr></td>
   <td><nobr><!--#slot src='langName'--></nobr></td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
</table>
<input style="margin: 10 0 0 5" type='image' alt="�������" src="/images/__backend/common/but-del.gif">
</form>

</div>

<!--#endlist-->


<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>�������� ������:</nobr></p>
<div class="clear"></div>

  <form method="post">
<input type='hidden' name='object' value='inter'>
<input type='hidden' name='mode' value='version'>
<input type='hidden' name='action' value='add'>

<!--#slot src='warnings' link='warnings'-->
<table>
<tr>
<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>���������:</b></nobr></td>
<td><input class="inn" name="add[alias]" value="<!--#slot src='alias'-->"></td>
</tr>
<tr>
	<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>��������:</b></nobr></td>
	<td><input class="inn" name="add[name]" value="<!--#slot src='name'-->"></td>
</tr>
<tr>
	<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>���:</b></nobr></td>
	<td>
		<select name="add[type]" >
			<option value='0'>���������
			<option value='1'>�����
		</select>
	</td>
</tr>
<tr>
	<td align="right"><p style="margin: 0 4 0 0; color:gray"><nobr><b>����:</b></nobr></td>
	<td>
	<!--#list src='languages'-->
		<select name="add[lang]" >
		<!--#elem-->
			<option value="<!--#slot src='code'-->"><!--#slot src='name'--></option>
		<!--#endelem-->
		</select>
	<!--#endlist-->
	
	</td>
</tr>
<tr>
<td></td>
<td><input style="margin: 5 0 0 0" type='image' alt="��������" src="/images/__backend/common/but-add.gif"></td>
</tr>
</table>

</form>

</div>

<!--#partsep-->

//------------------------------------------------------------------------------------------------
//  ������ ��������������
//------------------------------------------------------------------------------------------------
<!--#list name='warnings'-->
<div style="margin: 0 0 0 100">
<table>
<!--#elem-->
<tr><td valign="top"><img style="margin-top:1" src="/images/__backend/common/error_pic.gif"></td>
<td valign="top">
<p style="margin: 0 0 5 0"><!--#slot src='message'--></p>
</td></tr>
<!--#endelem-->
</table>
</div>
<!--#endlist-->

<!--#list src='_switch' name='type'-->
<!--#elem-->
<!--#cond-->
return $_ds->getParam('type');
<!--#endcond-->
�����
<!--#endelem-->
<!--#elem-->
���������
<!--#endelem-->
<!--#endlist-->