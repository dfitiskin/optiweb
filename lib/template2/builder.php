<?php

class CTemplate2_Builder
{
    public $Error = 0;
    public $Content = array();
    public $Parser = null;
    public $Manager = null;
    public $Template = null;

    function Prepare(&$_file)
    {
        $this->Parser = $this->Kernel->Link('template2.parser');
        $_templ = $this->Manager->Load($_file);
        $this->TemplateName = $_file;

        $this->Parser->Builder = &$this;
        $this->Parser->Prepare($_templ);
        $this->Funcs = &$this->Parser->Funcs;
        $this->Lists = &$this->Parser->Lists;
        $this->Template = $this->Parser->GetTemplate();
    }

    function PrepareTpl($_templ)
    {
        $this->Parser = $this->Kernel->Link('template2.parser');
        $this->Parser->Builder = & $this;
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
        $_result = $this->Template->Execute($_ds, $_ds);
        return $_result;
    }
}

class CTemplate2_Builder_Block
{
    public $Error = 0;
    public $Content = array();
    public $globalDs = null; 
    
    function Execute($_ds, $parentDs = null)
    {
        $_result = '';
        for($i = 0; $i < count($this->Content); $i++)
        {
            $_item = $this->Content[$i];

            if (is_array($_item))
            {
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
								
                                if ('_switch' == $_src)
                                {
                                    $_child_ds = $this->Builder->Kernel->link('object.list');
                                    $_child_ds->add($_ds);
                                }
                                else
                                {
                                    
                                    $_child_ds = $_ds->get($_src);
                                    if ($_child_ds)
                                    {
                                        $_child_ds->set('parent', $_ds);
                                    }
                                }


                                if (is_object($_child_ds) && ($_child_ds instanceof CObject_List))
                                {

                                    if ($_child_ds !== null)
                                    {
                                        $_content = $_list['object']->Execute($_child_ds, $parentDs);
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
                        if (isset($_item['func']))
                        {
                            $_content = 'func !!!';

	                        if (isset($this->Builder->Funcs[$_item['func']]))
                            {
	                            
                                $_list = & $this->Builder->Funcs[$_item['func']];
                                $_src = isset($_item['src']) ? $_item['src'] : $_list['src'];

                                if ('_switch' == $_src)
                                {
                                    $_child_ds = $this->Builder->Kernel->link('object.list');
                                    $_child_ds->add($_ds);
                                }
                                else
                                {
                                    $_child_ds = $_ds->get($_src);
                                    $_child_ds->set('parent', $_ds);
                                }
                                if (isset($_item['param']) && $_child_ds)
                                {
                                	if (preg_match('(([A-z0-9\.]*){([A-z0-9\.]+)}([A-z0-9\.]*))', $_item['param'], $match))
		                            {
		                            	$_item['param'] = $match[1].$_ds->get($match[2]).$match[3];
		                            }
		                            
		                            $_child_ds->set('param', $_item['param']);
                                }
                                if (isset($_item['slot']) && $_child_ds)
                                {
                                	if (preg_match('(([A-z0-9\.]*){([A-z0-9\.]+)}([A-z0-9\.]*))', $_item['slot'], $match))
		                            {
		                            	$_item['slot'] = $match[1].$_ds->get($match[2]).$match[3];
		                            }                                    
                                	$slot = $_ds->get($_item['slot']);
                                    $_child_ds->set('slot', $slot);
                                }
                                

                                if (is_object($_child_ds) && ($_child_ds instanceof CObject_List))
                                {

                                    if ($_child_ds !== null)
                                    {
                                        $_content = $_list['object']->Execute($_child_ds, $parentDs);
                                    }
                                    else
                                    {
                                        Error('Undefined dataset source "'.$_src.'" in func "'.$_item['func'].'" of template file "'.$this->Builder->TemplateName.'" !','template');
                                    }
                                }
                                else
                                {
                                    $_content = '';
                                }
			                }
                            else
                            {
                                Error('Undefined hidden list "'.$_item['func'].'" of template file "'.$this->Builder->TemplateName.'" !','template');
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
                        								                        								
                        	
                        	if (preg_match('(([A-z0-9\.]*){([A-z0-9\.]+)}([A-z0-9\.]*))', $_item['src'], $match))
                            {
                            	$_item['src'] = $match[1].$_ds->get($match[2]).$match[3];
                            }
                            
                            $_content = $_ds->get($_item['src']);
                            if ($parentDs && ($_content === null || $_content === ''))
                            {
                                $_content = $parentDs->get($_item['src']);
                            }
                            if (is_object($_content))
                            {
                                $_content = 'object->'. $_content->Name;
                            }

                            if (isset($_item['filter']))
                            {
                                global $Kernel;
                                $Filter = &$Kernel->Link('services.filter',true);
                                $_content = $Filter->Filt($_content,$_item['filter']);
                            }
                            //$_item['src'] = '';
                        }

                        $_result .= $_content;
                    break;
                    case 'list':
                        if ('_switch' == $_item['src'])
                        {
                            $_child_ds = $this->Builder->Kernel->link('object.list');
                            $_child_ds->add($_ds);
                        }
                        else
                        {
                            $_child_ds = $_ds->get($_item['src']);
                            if($_child_ds)
                            {
                                $_child_ds->set('parent', $_ds);
                            }
                            else
                            {
                                echo 'Error: null list "'.$_item['src'].'"  <br />'; 
                            }
                        }
                        if (is_object($_child_ds) && ($_child_ds instanceof CObject_List))
                        {
                            if ($_child_ds !== null)
                            {
                                $_result .= $_item['object']->Execute($_child_ds, $parentDs);
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

class CTemplate2_Builder_List
{
    public $Elements = array();
    public $Header = null;
    public $Footer = null;
    public $Separator = array();
    public $Alternative = array();
    public $Func = null;

    function Execute($list, $parentDs = null)
    {
        $_result = '';

        //$_lst = & $_ds;
        $list->refresh();


        $_was_elem = false;
        $_has_elem = $list->get('length');

        if (!$_has_elem)
        {
            if (sizeof($this->Alternative)==1)
            {
                return $this->Alternative[0] ? $this->Alternative[0]->Execute($list, $parentDs) : '';
            }
            else
            {
                for ($i=0;$i<sizeof($this->Alternative);$i++)
                {
                    if ($this->Alternative[$i]->Condition != null)
                    {
                        if (eval($this->Alternative[$i]->Condition))
                        {
                            return $this->Alternative[$i]->Execute($list, $parentDs);
                        }
                    }
                    else
                    {
                        return $this->Alternative[$i]->Execute($list, $parentDs);
                    }
                }
                return '';
            }
        }
        else
        {
            $_result .= $this->Header ? trim($this->Header->Execute($list, $parentDs),"\n\r\t") : '';
            
            while ($elem = $list->nextItem())
            {
            	//$elem->params = array_merge($elem->params, $parentDs->params);
                $elem->params['list']->params = array_merge($elem->params['list']->params, $parentDs->params);
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
                                $_result .= $this->Separator[$_curr_sep] ? trim($this->Separator[$_curr_sep]->Execute($elem),"\n\r\t") : '';
                            }
                        }
                    }

                    $_step = $_elem_obj->Execute($elem, $parentDs);
                    $_result .= trim($_step,"\n\r\t");
                    unset($_elem_obj);
                    $_was_elem = true;
                }
            }
            $_result .= $this->Footer ? trim($this->Footer->Execute($list, $parentDs),"\n\r\t") : '';
        }
        return $_result;
    }
}

?>
