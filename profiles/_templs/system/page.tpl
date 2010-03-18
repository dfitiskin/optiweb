<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" type="text/css" href="/profiles/_backend/_templs/site.css" />
<link rel="stylesheet" type="text/css" href="/profiles/_backend/_templs/main.css" />
<link rel="stylesheet" type="text/css" href="/profiles/_backend/_templs/calendar.css" />
</head>
<body style="background:url('/images/__backend/common/p_back.gif') #446d8c">

<div class="wrapper">
<div class="titler">
	<img src="/images/__backend/common/w_h_title.gif" alt="Optiweb" class="optiweb" />
	<div class="personal"></div>
</div>
<table border="0" cellspacing="0" cellpadding="0" class="firstable">
  <tr>
    <td width="100%" align=center>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" colspan="3" bgcolor="white" style="background-position:left; background-repeat:repeat-y">
            <div class="content">
                <form method="POST">
                    <fieldset>
                        <input type="hidden" name="object" value="system" />
                        <input type="hidden" name="mode" value="config" />
                        <input type="hidden" name="action" value="save" />                                        
                        <dl>
                            <dt><label for="dbhost">MySql host</label></dt>
                            <dd><input name="db[host]" value="<!--#slot src='db.host'-->"/></dd>
                        </dl>
                        <dl>
                            <dt><label for="dbname">MySql Database Name</label></dt>
                            <dd><input name="db[dbname]" value="<!--#slot src='db.dbname'-->"/></dd>
                        </dl>
                        <dl>
                            <dt><label for="dbuser">MySql Username</label></dt>
                            <dd><input name="db[user]" value="<!--#slot src='db.user'-->"/></dd>
                        </dl>
                        <dl>
                            <dt><label for="dbpass">MySql password</label></dt>
                            <dd><input name="db[pass]" value="<!--#slot src='db.pass'-->"/></dd>
                        </dl>
                        <button type="submit">Save config</button>
                    </fieldset>
                </form>
                
                <form method="POST">
                    <fieldset>
                        <input type="hidden" name="object" value="system" />
                        <input type="hidden" name="mode" value="database" />
                        <input type="hidden" name="action" value="backup" />                                        
                        <button type="submit">Backup database</button>
                    </fieldset>
                </form>
                
                <!--#list src='dbVersions'-->
                <form method="POST">
                    <fieldset>
                        <input type="hidden" name="object" value="system" />
                        <input type="hidden" name="mode" value="database" />
                        <input type="hidden" name="action" value="restore" />
                        <dl>
                            <dt><label for="dbpass">Backup version</label></dt>
                            <dd>
                                <select name='backup[file]'>
                                    <!--#elem-->
                                        <option value="<!--#slot src='value'-->">Копия от <!--#slot src='name' filter='date|H:i, d X Y'--></option>
                                    <!--#endelem-->
                                    <!--#alter-->
                                        Нет досутпных версий для восстановления.
                                    <!--#endalter-->
                                </select>
                            </dd>
                        </dl>
                                                                
                        <button type="submit">Restore database</button>
                    </fieldset>
                </form>
                <!--#endlist-->
            </div>
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<div class="bottom">
</div>
</div>
</body>
</html>
