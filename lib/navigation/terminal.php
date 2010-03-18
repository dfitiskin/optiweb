<?php

class CNavigation_Terminal
{
    function Init()
    {
        $this->Tables = array(
            'tree'                        =>        'be_tree',
        );
    }

    function checkPassword()
    {
        return true;
    }

    function Execute($_params)
    {
        if ($_params)
        {
            $_mode = array_shift($_params);


            switch ($_mode)
            {
                case 'tree':
                    $this->ShowTree($_params);
                break;
            }
        }
        else
        {
            $_menu = isset($_params[0]) ? $_params[0] : 'main';
            $_level = isset($_params[1]) ? $_params[1] : 2;

            $DbManager = &$this->Kernel->Link('database.manager',true);
            $DbManager->Select(
                    $this->Tables['tree'],
                '*',
                sprintf(
                    'level=%d AND menu="%s"',
                    $_level,
                    $_menu
                ),
                'ORDER BY sort'
            );

            $_url = '';
            $_name = '';
            $_num = '';
            while($_rec = $DbManager->getNextRec())
            {
                if ($_url) $_url .= ',';
                if ($_name) $_name .= ',';
                $_url .= '/'.$_rec['alias'].'/';
                $_name .= $_rec['fullname'];
            }

            $_data = 'url='.$_url.'&name='.$_name;
            $_data = iconv("WINDOWS-1251","UTF-8",$_data);
            echo($_data);
        }
    }

    function ShowTree($_params)
    {
        $Tree = & $this->Kernel->Link('navigation.tree');
        $_params = array(
            'mode'      => 'tree',
            'navtype'   => isset($_params[0]) ? $_params[0] : 'main',
            'level'     => 1,
        );

        $_templs = array(
            'main'  => array(
                'file'  =>  'tree_xml.tpl',
            ),

        );

        header('Content-Type: text/xml');
        echo $Tree->Execute($_params, $_templs);
    }
}

?>