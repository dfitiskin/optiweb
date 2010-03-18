
<div class="gray_block">
<p class="frame_head"><nobr>Установленные модули:</nobr></p>
<div class="clear"></div>


<form method='post'>
<input type='hidden' name='object' value='modules'>
<input type='hidden' name='mode' value='setup'>
<input type='hidden' name='action' value='uninst'>
<table>
<tr>
    <td width="5%"><img style="margin-left:5" src="/images/__backend/common/mpic-del.gif"></td>
    <td width="35%"><p style="margin: 0; color: gray">Псевдоним:</p></td>
    <td width="60%"><p style="margin: 0; color: gray">Название:</p></td>
</tr>
<!--#list src='tree'-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#sep-->
<tr>
<td bgcolor="#CCCCCC" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endsep-->
<!--#elem-->
<tr>
    <td width="5%"><input class="checked" type='checkbox' name='del[]' value='<!--#slot src="id"-->'></td>
    <td width="35%"><nobr><a href="<!--#slot src='_url'--><!--#slot src='alias'-->/"><!--#slot src='alias'--></a></nobr></td>
    <td width="60%"><!--#slot src='name'--></td>
</tr>
<!--#endelem-->
<tr>
<td bgcolor="#AAAAAA" style="padding:0" colspan="3"><img src="/images/1.gif" width="1" height="1"></td>
</tr>
<!--#endlist-->
</table>

<input type='submit' value='Удалить'>
</form>

</div>

<div style="height:20"></div>

<div class="gray_block">
<p class="frame_head"><nobr>Установка модуля:</nobr></p>
<div class="clear"></div>


<form method='post'>
  <input type='hidden' name='object' value='modules'>
  <input type='hidden' name='mode' value='setup'>
  <input type='hidden' name='action' value='inst'>

<table width="100%">
<tr>
    <td width="1%"><b>Псевдоним (лат.):</b></td>
    <td width="99%"><input class="checked" class="inn" name='setup[alias]'></td>
</tr>
<tr>
    <td width="1%"><b>Имя:</b></td>
    <td width="99%"><input class="checked" class="inn" name='setup[name]'></td>
</tr>
<tr>
    <td><b>Параметры:</b></td>
    <td>
<table width="100%">
    <tr>
        <td width="50%">
        <input type='hidden'   name='setup[interactive]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[interactive]' value='1' id="interactive">&nbsp;<b><label for="interactive">Интерактив</label></b></nobr><br>
        <input type='hidden'   name='setup[config]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[config]' value='1' id="config">&nbsp;<b><label for="config">Конфигурация</label></b></nobr><br>
        <input type='hidden'   name='setup[service]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[service]' value='1' id="service">&nbsp;<b><label for="service">Сервис</label></b></nobr><br>
        <input type='hidden'   name='setup[blocklink]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[blocklink]' value='1' id="blocklink">&nbsp;<b><label for="blocklink">Блоки</label></b></nobr><br>
        <input type='hidden'   name='setup[nodelink]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[nodelink]' value='1' id="nodelink">&nbsp;<b><label for="nodelink">Узлы</label></b></nobr><br>
        </td>
        <td width="50%">
        <input type='hidden'   name='setup[templates]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[templates]' value='1' id="templates">&nbsp;<b><label for="templates">Шаблоны</label></b></nobr><br>
        <input type='hidden'   name='setup[multiversion]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[multiversion]' value='1' id="multiversion">&nbsp;<b><label for="multiversion">Версии</label></b></nobr><br>
        <input type='hidden'   name='setup[planner]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[planner]' value='1' id="planner">&nbsp;<b><label for="planner">Планировщик</label></b></nobr><br>
        <input class="checker" type='hidden'   name='setup[export]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[export]' value='1' id="export">&nbsp;<b><label for="export">Экспорт</label></b></nobr><br>
        <input type='hidden'   name='setup[replication]' value='0'>
        <nobr><input class="checker" type='checkbox' name='setup[replication]' value='1' id="replication">&nbsp;<b><label for="replication">Репликация</label></b></nobr><br>
        </td>
    </tr>
</table>
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td><input type='submit' value='Установить'></td>
</tr>
</table>
</form>

</div>
