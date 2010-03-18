<form method='post'>
<input type='hidden' name='object' value='struct'>
<input type='hidden' name='mode' value='page_edit'>
<input type='hidden' name='action' value= 'static'>

<table width="100%" height="100%" cellspacing="10" cellpadding="0">
<tr>
<td><h1 class="contentname"><!--#slot src='name'--></h1></td>
</tr>
<tr height="1%">
        <td>
        Содержание страницы:
        </td>
</tr>
<tr height="95%">
    <td>
        <!--#slot src='editor'-->
    </td>
</tr>
<tr height="1%">
    <td>
      <p style="margin: 7 0 7 0" align=center>
      <input type='image' name="save" alt="Сохранить" src="/images/__backend/common/but-save.gif">      
      </p>
    </td>
</tr>


<tr height="1%">
    <td>

    </td>
</tr>
<tr height="1%">
    <td>
    <h3>SEO-настройки страницы</h3>
    title:
    </td>
</tr>
<tr height="1%">
    <td>
        <input class="inn" name='title' value="<!--#slot src='title'-->">
    </td>
</tr>
<tr height="1%">
    <td>
    Ключевые слова:
    </td>
</tr>
<tr height="1%">
    <td>
        <input class="inn" name='keywords' value="<!--#slot src='keywords'-->">
    </td>
</tr>
<tr height="1%">
    <td>
    Описание:
    </td>
</tr>
<tr height="1%">
    <td>
        <textarea class="inn" rows='3' cols='30' name='descript'><!--#slot src='descript'--></textarea>
    </td>
</tr>

<tr height="1%">
    <td>
      <p style="margin: 7 0 7 0" align=center>
      <input type='image' name="save" alt="Сохранить" src="/images/__backend/common/but-save.gif">      
      </p>
    </td>
</tr>
</form>
</table>