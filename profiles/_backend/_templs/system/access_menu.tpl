
<div class="gray_block">
    <p class="frame_head"><nobr>������� ����:</nobr></p>
    <div class="clear"></div>
    <!--#list src='menu'-->
<table width="100%" border=0>
<form method='post'>
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='menu'>
<input type='hidden' name='action' value='upd'>
<tr>
    <td width="1%"><p style="margin: 0; color: gray">�</td>
    <td width="70%"><p style="margin: 0; color: gray">��������:</td>
    <td width="20"><p style="margin: 0; color: gray"><nobr>������:</nobr></td>
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
   <td align='right'><!--#slot src='_current'-->.</td>
   <td><!--#slot src='name' --></td>
   <td>
        <select size="1" name="upd[<!--#slot src='id'-->]">
            <option value="0">�����</option>
            <option value="1" <!--#slot src='is_perms'-->>������������</option>
        </select>
   </td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="5"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<tr>
<td colspan="6">
<input style="margin: 10 0 0 0" type='image' name="del" alt="���������" src="/images/__backend/common/but-save.gif">
</td>
</tr>

<!--#endlist-->

</form>
</table>

</div>