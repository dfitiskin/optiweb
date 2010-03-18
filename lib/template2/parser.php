<?php

class CTemplate2_Parser
{
    public $Lists = array();
    public $Funcs = array();

    function getSlotsList($_str)
    {
        Dump($_str);
    }

    function Prepare($_str)
    {
        if (preg_match_all ('(<!--#require src=[\'"`](\w+)[\'"`]-->)', $_str, $matches, PREG_SET_ORDER))
        {
            foreach($matches as $i => $match)
            {
                $fileText = $this->Builder->Manager->load($match[1].'.tpl');
                $_str = str_replace($match[0], $fileText, $_str);
            }
        }
        $_str = preg_replace('(<!--#require src=[\'"`](.+)[\'"`]-->)', '', $_str); 
        
        $_parts = explode('<!--#partsep-->', $_str);
        
        $Lists = array();
        $Funcs = array();

        if (count($_parts) > 1)
        {
            $_content = $this->Parse($_parts[1]);
            while (count($_content))
            {
                if (!is_array($_content[0]))
                {
                    array_shift($_content);
                    continue;
                }

                $_command = $_content[0];
                if ($_command[1] == 'list')
                {
                    $_obj = new CTemplate2_Parser_List();
                    $_obj->Builder = & $this->Builder;
                    $_obj->Parse($_content);

                    $_arr = array();
                    if ($_command[2])
                    {
                        for ($i=0;$i<sizeof($_command[3]);$i++)
                        {
                            $_arr[$_command[3][$i]] = $_command[4][$i];
                        }
                    }

                    $Lists[$_arr['name']] = array(
                        'type'   => 'list',
                        'src'    => isset($_arr['src'])?$_arr['src']:'',
                        'name'   => $_arr['name'],
                        'object' => & $_obj
                    );
                    unset($_obj);
                }
                else
                if ($_command[1] == 'func')
                {
                    $_obj = new CTemplate2_Parser_Func();
                    $_obj->Builder = & $this->Builder;
                    $_obj->Parse($_content);

                    $_arr = array();
                    if ($_command[2])
                    {
                        for ($i=0;$i<sizeof($_command[3]);$i++)
                        {
                            $_arr[$_command[3][$i]] = $_command[4][$i];
                        }
                    }

                    $Funcs[$_arr['name']] = array(
                        'type'   => 'func',
                        'src'    => isset($_arr['src'])?$_arr['src']:'',
                        'name'   => $_arr['name'],
                        'object' => & $_obj
                    );
                    unset($_obj);
                }
                else
                {
                    echo $_command[1];
                    break;
                }
            }
        }

        // Парсинг первой части
        $_content = $this->Parse($_parts[0]);
        $this->Template = new CTemplate2_Parser_Block();
        $this->Template->Builder = &$this->Builder;
        $this->Template->Parse($_content);
        $this->Lists = & $Lists;
        $this->Funcs = & $Funcs;
    }

    function &GetTemplate()
    {
        return $this->Template;
    }

    function &GetLists()
    {
        return $this->Lists;
    }

    function & Parse(& $_str)
    {
        
    	$_str = preg_replace('(<!--#(.*?(?:src|param|slot))=[\'"]([A-z\.]*)<!--#slot src=[\'"]([A-z\.]+)[\'"]-->([A-z\.]*)[\'"]-->)im', '<!--#\1=\'\2{\3}\4\'-->', $_str);
    	$precontent = preg_split('(<!--(?=#)(.+?)-->)im', $_str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $content = array();
        while(sizeof($precontent))
        {
            $str = $precontent[0];
            if ($str[0] == '#')
            {
                preg_match('/^#(block|slot|list|endlist|elem|endelem|cond|endcond|sep|endsep|alter|endalter|func|endfunc)\s*(.*)$/i', $str, $temp);
                if ($temp[2])
                {
                    preg_match_all('/(src|filter|link|name|func|param|list|slot)\s*=\s*[\'"]([^\'"]*)[\'"]/i',$temp[2],$temp2);
                    $temp[3] = $temp2[1];
                    $temp[4] = $temp2[2];
                }
                array_push($content, $temp);
            }
            else //if (strlen(trim($str)))
            {
                array_push($content, $str);
            }
            array_shift($precontent);
        }
        return $content;
    }

}


class CTemplate2_Parser_Block extends CTemplate2_Builder_Block
{
    function Parse(& $_content)
    {
        while (count($_content))
        {
            if (is_array($_content[0]))
            {
                $_command = $_content[0];

                switch($_command[1])
                {
                    case 'block':
                    case 'slot':
                    	$_arr = array('type' => $_command[1]);

                        if ($_command[2])
                        {
                            for ($i=0;$i<sizeof($_command[3]);$i++)
                            {
                                $_arr[$_command[3][$i]] = $_command[4][$i];
                            }
                        }
                        array_push($this->Content,$_arr);
                        array_shift($_content);
                    break;
                    case 'list':
                        $_obj = new CTemplate2_Parser_List();
                        $_obj->Builder = & $this->Builder;
                        $_obj->Parse($_content);
                        $_arr = array('type' => $_command[1]);
                        if ($_command[2])
                        {
                            for ($i=0;$i<sizeof($_command[3]);$i++)
                            {
                                $_arr[$_command[3][$i]] = $_command[4][$i];
                            }
                        }
                        $_arr['object'] = & $_obj;
                        if (isset($_arr['func']))
                        {
                            $_obj->Func = $_arr['func'];
                        }
                        array_push($this->Content, $_arr);
                        unset($_obj);
                    break;
                    default:
                        if (preg_match('/^(end.*|elem)/',$_command[1]))
                        {
                            return true;
                        }
                        else
                        {
                            $this->Error = 1;///
                            return false;
                        }
                    break;
                }
                        
            }
            else
            {
                array_push($this->Content, $_content[0]);
                array_shift($_content);
            }
        }
    }
}

class CTemplate2_Parser_List extends CTemplate2_Builder_List
{
    public $Func = null;

    function Parse(& $_content)
    {
        array_shift($_content);

        $this->Header = new CTemplate2_Parser_Block();
        $this->Header->Builder = &$this->Builder;
        $this->Header->Parse($_content);

        if ($_content[0][1] == 'elem')
            $this->Header->Error = 0;

        $_command = $_content[0];
        $_blanks = array();
        //echo('Start Elements >>>> <br>');
        while($_command[1] == 'elem' || $_command[1] == 'sep' || $_command[1] == 'alter')
        {
            //Dump($_command);
            $_blanks = array();

            switch($_command[1])
            {
                case 'elem':
                    $_elem = new CTemplate2_Parser_Elem();
                    $_elem->Builder = &$this->Builder;
                    $_elem->Parse($_content);
                    //Dump($_elem);
                    array_push($this->Elements, $_elem);
                    unset($_elem);
                break;
                case 'sep':
                    $_sep = new CTemplate2_Parser_Elem();
                    $_sep->Builder = &$this->Builder;
                    $_sep->Parse($_content);
                    //Dump($_sep);
                    array_push($this->Separator, $_sep);
                    unset($_sep);
                break;
                case 'alter':
                    $_alter = new CTemplate2_Parser_Elem();
                    $_alter->Builder = &$this->Builder;
                    $_alter->Parse($_content);
                    //Dump($_alter);
                    array_push($this->Alternative, $_alter);
                    unset($_alter);
                break;
            }

            if (sizeof($_content))
            {
                while (sizeof($_content) && !is_array($_content[0]) && !trim($_content[0]))
                    array_unshift($_blanks,array_shift($_content));
                if (!sizeof($_content)) break;
                $_command = $_content[0];
            }
        }

        while (sizeof($_blanks))
            array_unshift($_content,array_shift($_blanks));

        $_content = array_merge($_content,$_blanks);

        $this->Footer = new CTemplate2_Parser_Block();
        $this->Footer->Builder = &$this->Builder;
        $this->Footer->Parse($_content);

        if ($_content[0][1] == 'endlist')
        {
            array_shift($_content);
            return true;
        }
        else
        {
            $this->Error = 2;
            return false;
        }
    }
}

class CTemplate2_Parser_Elem extends CTemplate2_Parser_Block
{
    public $Condition = null;
    public $type = null;

    function Parse(& $_content)
    {
        array_shift($_content);

        if (!is_array($_content[0]))
            parent::Parse($_content);

        if ($_content[0][1] == 'cond')
        {
            $this->Condition = $_content[1];
            array_shift($_content);
            array_shift($_content);
            array_shift($_content);
        }


        if (!is_array($_content[0]) || !preg_match('/^(endelem|endsep|endalter)/',$_content[0][1]))
            parent::Parse($_content);

        //echo('Stop element <br>');
        if (preg_match('/^(endelem|endsep|endalter)/',$_content[0][1]))
        {
            //echo('Stop Success element <br>');
            $this->Error = 0;
            array_shift($_content);
            return true;
        }
        else
        {
            $this->Error = 3;
            return false;
        }
    }
}


class CTemplate2_Parser_Func extends CTemplate2_Builder_List
{
    public $Func = null;

    function Parse(& $_content)
    {
        array_shift($_content);

        $this->Header = new CTemplate2_Parser_Block();
        $this->Header->Builder = &$this->Builder;
        $this->Header->Parse($_content);

        if ($_content[0][1] == 'elem')
            $this->Header->Error = 0;

        $_command = $_content[0];
        $_blanks = array();
        //echo('Start Elements >>>> <br>');
        while($_command[1] == 'elem' || $_command[1] == 'sep' || $_command[1] == 'alter')
        {
            //Dump($_command);
            $_blanks = array();

            switch($_command[1])
            {
                case 'elem':
                    $_elem = new CTemplate2_Parser_Elem();
                    $_elem->Builder = &$this->Builder;
                    $_elem->Parse($_content);
                    //Dump($_elem);
                    array_push($this->Elements, $_elem);
                    unset($_elem);
                break;
                case 'sep':
                    $_sep = new CTemplate2_Parser_Elem();
                    $_sep->Builder = &$this->Builder;
                    $_sep->Parse($_content);
                    //Dump($_sep);
                    array_push($this->Separator, $_sep);
                    unset($_sep);
                break;
                case 'alter':
                    $_alter = new CTemplate2_Parser_Elem();
                    $_alter->Builder = &$this->Builder;
                    $_alter->Parse($_content);
                    //Dump($_alter);
                    array_push($this->Alternative, $_alter);
                    unset($_alter);
                break;
            }

            if (sizeof($_content))
            {
                while (sizeof($_content) && !is_array($_content[0]) && !trim($_content[0]))
                    array_unshift($_blanks,array_shift($_content));
                if (!sizeof($_content)) break;
                $_command = $_content[0];
            }
        }

        while (sizeof($_blanks))
            array_unshift($_content,array_shift($_blanks));

        $_content = array_merge($_content,$_blanks);

        $this->Footer = new CTemplate2_Parser_Block();
        $this->Footer->Builder = &$this->Builder;
        $this->Footer->Parse($_content);

        if ($_content[0][1] == 'endfunc')
        {
            array_shift($_content);
            return true;
        }
        else
        {
            $this->Error = 2;
            return false;
        }
    }
}

?>
