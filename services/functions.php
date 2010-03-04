<?

function ExtKeys($_arr, $_prefix = null, $_postfix = null)
{
    $_res = array();
    if (!$_prefix && !$_postfix)
    {
        $_res = $_arr;
    }
    elseif (is_array($_arr))
    {
        foreach($_arr as $_key => $_val)
        {
            $_res[$_prefix.$_key.$_postfix] = $_val;
        }
    }
    return $_res;
}

function loadlib($_complex_class_name)
{
    global $Kernel;
    
    list ($_group_name, $_lib_name) = explode('.', $_complex_class_name);
    
    if (!$_lib_name)
    {
        $_lib_name = $_group_name;
        $_group_name = 'system';
    }
    
    $Kernel->loadlib($_lib_name, $_group_name);
}

function magicQuotesSuck(&$a)
{
    if (is_array($a))
    {
        foreach ($a as $k => $v)
        {
            if (is_array($v))
            {
                magicQuotesSuck($a[$k]);
            }
            else
            {
                $a[$k] = stripslashes($v);
            }
        }
    }
}

function ow_ucfirst($_string)
{
    if (strlen($_string))
    {
        $_string = ow_strtolower($_string);    
        $_string{0} = ow_strtoupper($_string{0});
    }
    return $_string;
}

function ow_strtolower($_string)
{
    $_high = 'ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÜÛÚİŞß';
    $_low  = 'àáâãäå¸æçèéêëìíîïğñòóôõö÷øùüûúışÿ';

    return strtr($_string, $_high, $_low);
}

function ow_strtoupper($_string)
{
    $_high = 'ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÜÛÚİŞß';
    $_low  = 'àáâãäå¸æçèéêëìíîïğñòóôõö÷øùüûúışÿ';

    return strtr($_string, $_low, $_high);
}