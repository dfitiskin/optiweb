<?

//------------------------------------------------------------------------------
// Module : System
// Lib    : CMS Debug
// Ver    : 0.1 beta
// Date   : 25.09.2004
// Desc   : Функции для отладки системы
//------------------------------------------------------------------------------

function Dump(&$_var,$_max_level = 10,$_level = 0)
{
    if ($_level == 0) echo "<pre>\n";

    $_type = gettype($_var);
    $_sublist = false;
    $_descr =   ucfirst($_type);
    $_val = $_var;
    switch ($_type)
    {
        case "array":
            $_descr = "Array[".sizeof($_var)."]";
            $_sublist = true;
        break;
        case "object":
            //!
            if (get_class($_var) != 'ckernel') $_sublist = true;
        break;
        case "string":
            $_descr = "";
            $_val = htmlspecialchars($_var);
            $_val = str_replace("\n\n","\n",$_val);
            if (strlen($_val)<100) $_val = str_replace("\n","",$_val);
            $_val = "\"<i>".$_val."</I>\"";
        break;
        case "resource":
        break;
        case "NULL":
            $_val = "NULL";
        break;
        case "double":
        break;
        case "boolean":
            if ($_var) $_val = "True";
            else $_val = "False";
        break;
        case "integer":
        break;
        default:
            echo("!!!>>>".$_type);
        break;
    }
    if ($_sublist)
    {
        echo "$_descr\n";
        $_level++;
        foreach($_var as $k => $v)
        {
            if ($k === "Kernel") continue;
            if ($k === "Parent") continue;
            if (is_array($v) && $k === "GLOBALS") continue;
            echo("<br>");
            echo str_repeat("&nbsp;", $_level * 2);
            echo "<b>" . htmlspecialchars($k) . "</b> => ";
            if ($_level <= $_max_level) Dump($v, $_max_level, $_level);
            if (is_object($v) || is_array($v))
            {
                for($i = 0; $i < $_level * 2 ; $i++) echo "&nbsp;";
                echo "<= <b>/".htmlspecialchars($k)."</b>\n";
            }
        }
        $_level--;
    }
    else echo($_val."\n");

    if ($_level == 0) echo("</pre><br>\n");
}

?>