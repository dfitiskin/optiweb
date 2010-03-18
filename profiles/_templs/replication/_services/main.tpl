<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Параметры репликации:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>

<form method='post' enctype = "multipart/form-data">
<input type='hidden' name='object' value='<!--#slot src='_object'-->'>
<input type='hidden' name='mode' value='main'>
<input type='hidden' name='action' value='save'>
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 5">

<table width="90%">
<tr>
        <td width="30%">Имя хоста:</td>
        <td width="70%"><input name="trans[host]" value="<!--#slot src="host"-->"></td>
</tr>
<tr>
        <td width="30%">Пароль:</td>
        <td width="70%"><input name="trans[password]" value="<!--#slot src="password"-->"></td>
</tr>
<tr>
        <td width="30%">Время репликации:</td>
        <td width="70%"><input name="trans[datetime]" value="<!--#slot src="datetime"-->"></td>
</tr>
<tr>
        <td></td>
        <td><input type='image' alt="Обновить" src="/images/__backend/common/but-refresh.gif"></td>
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

<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-1.gif" width="9" height="17"></td>
    <td width="98%" background="/images/__backend/common/frame-ub.gif"><p class="frame_head"><nobr>Начать репликацию:</nobr></td>
    <td width="1%"><img border="0" src="/images/__backend/common/frame-2.gif" width="9" height="17"></td>
  </tr>
  <tr>

<form action="<!--#slot src="_url"-->transver/">
    <td width="1%" style="background-position:left; background-repeat:repeat-y" background="/images/__backend/common/gray.gif"></td>
    <td width="98%" style="padding: 5">

<table width="90%">
<tr>
    <td width="30%">Дата последней репликации:</td>
    <td width="70%"><!--#slot src="lastrepl"--></td>
</tr>
<tr>
    <td>Тип репликации:</td>
    <td>
            <select name="type" style="width:100%">
                <option value="small">Обновленная информация
                <option value="all">Вся информация
                <option value="full">Вся информация и ПО
            </select>
    </td>
</tr>
<tr>
    <td>Дополнительные параметры:</td>
    <td>
        <input name="from" type="radio" value="date"> с указанной даты<br>
        <input name="from" type="radio" value="last" checked> с момента последней репликации<br>
        <input name="from" type="radio" value="today"> за сегодня<br>
    </td>
</tr>
<tr>
        <td></td>
        <td><input type='image' alt="Обновить" src="/images/__backend/common/but-ok.gif"></td>
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