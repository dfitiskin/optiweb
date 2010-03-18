<?php
include '../config/spaw_control.config.php';
include $spaw_root.'class/lang.class.php';

$theme = empty($HTTP_POST_VARS['theme'])?(empty($HTTP_GET_VARS['theme'])?$spaw_default_theme:$HTTP_GET_VARS['theme']):$HTTP_POST_VARS['theme'];
$theme_path = $spaw_dir.'lib/themes/'.$theme.'/';

$l = new SPAW_Lang(empty($HTTP_POST_VARS['lang'])?$HTTP_GET_VARS['lang']:$HTTP_POST_VARS['lang']);

$l->setBlock('icon_insert');
$icons_in_row=4;
$imglib = $icons_lib;//$HTTP_POST_VARS['lib'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
  <title><?php echo $l->m('title')?></title>
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset()?>">
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path.'css/'?>dialog.css">
  <script language="javascript" src="utils.js"></script>

  <script language="javascript">
  <!--
	function getimg(obj) {
	    if(!obj.src) return;
	    window.returnValue = obj.src || "";
	    window.close();
	}

    function Init()
    {
      resizeDialogToContent();
    }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?php echo $l->getDir();?>">
  <script language="javascript">
  <!--
    window.name = 'imglibrary';
  //-->
  </script>

<div style="border: 1 solid Black; padding: 5 5 5 5;">

<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td valign="top" align="left"><b><?php echo $l->m('images')?>:</b></td>
</tr>
<tr>
  <td valign="top" align="left">
  <?php
    if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
    else
      $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];
    $d = @dir($_root.$imglib);
  ?>
  <table>
  <tr>
  <td>
  <!--  <select name="imglist" size="15" class="input" style="width: 150px;"
      ondblclick="selectClick();">-->
  <?php
    if ($d)
    {
      $i=1;
      while ($entry = $d->read()) {
        if (!is_dir($_root.$imglib.$entry))
        {
           if ($i>$icons_in_row) {
              $i=1;
              echo "</td></tr><tr><td>";
           }

           echo "<img class='icon' onclick='getimg(this)' src='".$spaw_base_url.$imglib.$entry."'></img>";
           $i++;
        }
      }
      $d->close();
    }
    else
    {
      $errors[] = $l->m('error_no_dir');
    }
  ?>

  </td>
  </tr>
  </table>
  </td>
</tr>
<tr>
  <td valign="top" align="left" >
  <input type="button" value="<?php echo $l->m('cancel')?>" class="bt" onclick="window.close();">
  </td>
</tr>
</table>
</div>

</body>
</html>