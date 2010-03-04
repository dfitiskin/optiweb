<?php

class CTemplate_Builder
{
    public $Error = 0;
    public $Content = array();
    public $Parser = null;
    public $Manager = null;
    public $Template = null;

    function Prepare(&$_file)
    {
        $this->Parser = $this->Kernel->Link('template.parser');
        $_templ = $this->Manager->Load($_file);
        $this->TemplateName = $_file;

        $this->Parser->Builder = &$this;
        $this->Parser->Prepare($_templ);
        $this->Funcs = &$this->Parser->Funcs;
        $this->Lists = &$this->Parser->Lists;

        $this->Template = $this->Parser->GetTemplate();
    }

    function PrepareTpl(&$_templ)
    {
        $this->Parser = $this->Kernel->Link('template.parser');
        $this->Parser->Builder = &$this;
        $this->Parser->Prepare($_templ);
        $this->Funcs = &$this->Parser->Funcs;
        $this->Lists = &$this->Parser->Lists;

        $this->Template = $this->Parser->GetTemplate();
    }

    function SetManager(&$_manager)
    {
        $this->Manager = &$_manager;
    }


    function Execute(& $_ds)
    {
        $_result = $this->Template->Execute($_ds);
        return $_result;
    }
}

class CTemplate_Builder_Block
{
    public $Error = 0;
    public $Content = array();

    function Execute(&$_ds)
    {
        $_result = '';
        //echo('Start of Block  >>>> <br>');
        for($i = 0; $i < count($this->Content); $i++)
        {
            $_item = & $this->Content[$i];
            //Dump($_item);

            if (is_array($_item))
            {
                //echo('Part - <<<<< '.$item['type'].' >>>> <br>');
                switch($_item['type'])
                {
                    case 'block':
                        if (!isset($_item['src']))
                        {
                            $this->Error = true;
                            Error('Source not defined in block','Template');
                        }
                        else
                        {
                            global $Page;
                            $_content = $Page->GetBlockContent($_item['src']);
                            $_result .= $_content;
                        }
                    break;
                    case 'slot':
                        if (isset($_item['link']))
                        {
                            $_content = 'link !!!';
                            //global $_OBJECTS;
                            //if (isset($_OBJECTS['template']['lists'][$_item['link']]))
                            
                            if (isset($this->Builder->Lists[$_item['link']]))
                            {
                                $_list = & $this->Builder->Lists[$_item['link']];
                                $_src = isset($_item['src']) ? $_item['src'] : $_list['src'];
                                
                                $_child_ds = $_ds->GetChildDS($_src);
                                
                                
                                if (is_object($_child_ds))
                                {
                                    
                                    if ($_child_ds !== null)
                                    {
                                        $_content = $_list['object']->Execute($_child_ds);
                                    }
                                    else
                                    {
                                        Error('Undefined dataset source "'.$_src.'" in list "'.$_item['link'].'" of template file "'.$this->Builder->TemplateName.'" !','template');
                                    }
                                }
                                else
                                {
                                    $_content = '';
                                }
                            }
                            else
                            {
                                Error('Undefined hidden list "'.$_item['link'].'" of template file "'.$this->Builder->TemplateName.'" !','template');
                            }

                            if (isset($_item['filter']))
                            {
                                global $Kernel;
                                $Filter = &$Kernel->Link('services.filter',true);
                                $_content = $Filter->Filt($_content,$_item['filter']);
                            }
                            unset($_list, $_child_ds);
                        }
                        else
                        {
                            if (isset($_item['uplevel']))
                            {
                                $_content = $_ds->GetParentParam($_item['src'],$_item['uplevel']);
                            }
                            else
                            {
                                $_content = $_ds->GetParam($_item['src']);
                            }
                            if (isset($_item['filter']))
                            {
                                global $Kernel;
                                $Filter = &$Kernel->Link('services.filter',true);
                                $_content = $Filter->Filt($_content,$_item['filter']);
                            }
                        }

                        $_result .= $_content;
                    break;
                    case 'list':
                        $_child_ds = $_ds->GetChildDS($_item['src']);
                        if (is_object($_child_ds))
                        {
                            if ($_child_ds !== null)
                            {
                                $_result .= $_item['object']->Execute($_child_ds);
                            }
                            else
                            {
                                Error('Undefined dataset source "'.$_item['src'].'" in list of template file "'.$this->Builder->TemplateName.'" !','template');
                            }
                        }
                    break;
                }
            } else $_result .= $_item;
        }
        return $_result;
    }

}

class CTemplate_Builder_List
{
    public $Elements = array();
    public $Header = null;
    public $Footer = null;
    public $Separator = array();
    public $Alternative = array();
    public $Func = null;

    function Execute(& $_ds)
    {
        $_result = '';

        //$_lst = & $_ds;
        $_ds->Refresh();
        

        $_was_elem = false;
        $_has_elem = $_ds->HasElements();

        if (!$_has_elem)
        {
            if (sizeof($this->Alternative)==1)
            {
                return $this->Alternative[0] ? $this->Alternative[0]->Execute($_ds) : '';
            }
            else
            {
                for ($i=0;$i<sizeof($this->Alternative);$i++)
                {
                    if ($this->Alternative[$i]->Condition != null)
                    {
                        if (eval($this->Alternative[$i]->Condition))
                        {
                            return $this->Alternative[$i]->Execute($_ds);
                        }
                    }
                    else
                    {
                        return $this->Alternative[$i]->Execute($_ds);
                    }
                }
                return '';
            }
        }
        else
        {
            $_result .= $this->Header ? trim($this->Header->Execute($_ds),"\n\r\t") : '';

            $_ds->Next();

            do
            {
                $_curr_no = -1;
                $_curr_sep = -1;

                if ($this->Func)
                {
                    //global $_OBJECTS;
                    //if (isset($_OBJECTS['template']['funcs'][$this->Func]))
                    if (isset($this->Builder->Funcs[$this->Func]))
                    {
                        $_func = $this->Builder->Funcs[$this->Func];
                        $_curr_no = eval($_func);
                    }
                    //$_curr_no = eval($_sel_func);
                }
                else
                {
                    for ($j = 0; $j < sizeof($this->Elements); $j++)
                    {
                        //Dump($this->Elements[$j]->Condition);
                        if ($this->Elements[$j]->Condition === null)
                        {
                            $_curr_no = $j;
                            break;
                        }
                        elseif (eval($this->Elements[$j]->Condition))
                        {
                            $_curr_no = $j;
                            break;
                        }
                    }
                }

                if ($_curr_no != -1 && isset($this->Elements[$_curr_no]))
                {
                    $_elem_obj = & $this->Elements[$_curr_no];
                    
                    if ($_was_elem)
                    {
                        if ($this->Separator != null)
                        {
                            for ($i=0;$i<sizeof($this->Separator);$i++)
                            {
                                if ($this->Separator[$i]->Condition == null)
                                {
                                    $_curr_sep = $i;
                                    break;
                                }
                                elseif (eval( $this->Separator[$i]->Condition))
                                {
                                    $_curr_sep = $i;
                                    break;
                                }
                            }

                            if ($_curr_sep>-1)
                            {
                                $_result .= $this->Separator[$_curr_sep] ? trim($this->Separator[$_curr_sep]->Execute($_ds),"\n\r\t") : '';
                            }
                        }
                    }

                    $_step = $_elem_obj->Execute($_ds);
                    //Dump($_step);
                    $_result .= trim($_step,"\n\r\t");
                    unset($_elem_obj);
                    $_was_elem = true;
                }
            }
            while ($_ds->next());
            $_result .= $this->Footer ? trim($this->Footer->Execute($_ds),"\n\r\t") : '';
        }
        return $_result;
    }
}

?>