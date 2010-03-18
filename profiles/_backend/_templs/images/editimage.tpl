

<table border="0" width="100%" cellspacing="0" cellpadding="10">
<tr><td colspan="2" valign="to">
<h2 style="margin:0">Редактирование изображения</h2>
</td></tr>

<tr>
<td width="1%" style="padding-left: 20" valign="top">
<table bgcolor="#FFFFFF" style="border-collapse:collapse" border="1" width="100%" cellpadding="20" bordercolor="#808080" cellspacing="0">
  <tr>
    <td width="100%"><img src='http://optiweb.extra.web/_terminal/image/accomodate/500/500<!--#slot src='image_path'-->'></td>
  </tr>
</table>


</td>
    
<td width="99%" valign="top">

<form method='post'>
<input type='hidden' name='object' value='images'>
<input type='hidden' name='mode' value='property'>
<input type='hidden' name='action' value='edit'>
<table cellpadding="3">
<tr>
    <td>Имя файла:</td>
    <td><b><!--#slot src='filename'--></b></td>
</tr>
<tr>
    <td>Ширина изображения:</td>
    <td><b><!--#slot src='width'--></b>&nbsp;px</td>
</tr>

<tr>
    <td>Высота изображения:</td>
    <td><b><!--#slot src='height'--></b>&nbsp;px</td>
</tr>
<tr>
    <td>
        <select name='filemng[action]'>
            <option value='rename'>Новое имя файла
            <option value='copy'>Сделать копию с именем
        </select>
    </td>
    <td>
        <input size='18' name='filemng[newname]' value='<!--#slot src='filename'-->'>
    </td>
</tr>

<tr>
    <td>
        Изменить размер:
    </td>
    <td>
        <select name='image[action]' id="sizes" onclick="newsize();">
            <option value=''>Выберите размер
            <option value='100x100'>100x100
            <option value='200x200'>200x200
            <option value='400x300'>400x300
            <option value='640x480'>640x480
            <option value='800x600'>800x600
            <option value='other'>Другой..
        </select>
    </td>
</tr>
<tr>
    <td><span id="width_title">Ширина:</span><input id="wid" size='4' name = 'image[width]'></td>
    <td><span id="height_title">Высота:</span><input id="hei" size='4' name = 'image[height]'></td>
<tr>
<td colspan='2'><hr size="1" color="#CCCCCC"></td>
</tr>
<tr>
    <td colspan='2'>
        <input type='image' alt="Применить" src="/images/__backend/common/but-apply.gif">
	<img style="cursor:hand; margin-left:10" onclick="window.close();" alt="Закрыть" src="/images/__backend/common/but-close.gif">
    </td>
</tr>
</table>
</form>
</td>
  </tr>
</table>

<script>
<!--
function newsize() 
{
if(document.all('sizes').value=='other') 
	{
		document.all('wid').disabled = false;
		document.all('hei').disabled = false;
		document.all('width_title').style.color = '#000000';
		document.all('height_title').style.color = '#000000';		

	} 
	else 
	{
		document.all('wid').disabled = true;
		document.all('hei').disabled = true;
		document.all('width_title').style.color = '#AAAAAA';
		document.all('height_title').style.color = '#AAAAAA';
		
		switch (document.all('sizes').value) {
			case '': w=''; h=''; break;
			case '100x100': w=100; h=100; break;
			case '200x200': w=200; h=200; break;
			case '400x300': w=400; h=300; break;
			case '640x480': w=640; h=480; break;
			case '800x600': w=800; h=600; break;
		};
		document.all('wid').value = w;
		document.all('hei').value = h;		
	}
};

document.all('wid').disabled = true;
document.all('hei').disabled = true;
document.all('width_title').style.color = '#AAAAAA';
document.all('height_title').style.color = '#AAAAAA';
-->
</script>