<form method='post'>
    <input type='hidden' name='object' value='images'>
    <input type='hidden' name='mode' value='imageedit'>
    <input type='hidden' name='action' value='edit'>
<table>
<tr>
    <td>
    	��������
    </td>
    <td>
		<!--#slot src='filename'-->
    </td>
</tr>
<tr>
    <td>
    	������ ��������
    </td>
    <td>
		<!--#slot src='width'-->
    </td>
</tr>

<tr>
    <td>
    	������ ��������
    </td>
    <td>
		<!--#slot src='height'-->
    </td>
</tr>
<tr>
    <td>
        <select name='filemng[action]'>
            <option value='rename'>�������� ��������
            <option value='copy'>�����������
        </select>
    </td>
    <td>
        <input size='10' name='filemng[newname]' value='<!--#slot src='filename'-->'>
    </td>
</tr>

<tr>
    <td>
        �������� ������:
    </td>
    <td>
        <select name='image[action]'>
            <option value=''>
            <option value='100x100'>100X100
            <option value='200x200'>200x200
            <option value='400x300'>400x300
            <option value='640x480'>640x480
            <option value='800x600'>800x600                                    
            <option value='other'>������..
        </select>
    </td>
</tr>
<tr>
    <td>
        ������:<input size='4' name = 'image[width]'>
    </td>
    <td>
        ������:<input size='4' name = 'image[height]'>
    </td>
</tr>
<tr>
    <td colspan='2'>
        <input type='submit' value=' ��������� '>
    </td>
</tr>
</table>
</form>

<img src='<!--#slot src='image_path'-->'>