<?php

class CDataset_abstract
{
    public $Type = 'abstract';

    public $Childs = array();
    public $Parent = null;

    public $Params = array();
    public $Items = array();
    public $Current = null;
    public $RecsCount = 0;

    function refresh() 
    { 
    }

    function getChildDS($_name)
    {
        $_ds = null;
        if (isset($this->Childs[$_name]))
        {
           $_ds = &$this->Childs[$_name];
        }
        elseif ($_name == '_switch')
        {
            $_ds = $this->Kernel->Link('dataset.abstract');
            $_ds->Name = '_switch';
            $_ds->Childs = $this->Childs;
            $_ds->Params = $this->Params;
            $_ds->Items = $this->Items;
            $_ds->Current = $this->Current;
            $_ds->RecsCount = $this->RecsCount ? $this->RecsCount : 1;
            $this->AddChildDS('_switch',$_ds);
        }
        return $_ds;
    }

    function addChildDS($_name, $_ds)
    {
        if (!isset($this->Childs[$_name]))
        {
            $this->Childs[$_name] = $_ds;
            if (is_object($_ds)) 
            {
                $_ds->Parent = $this;
            }
            return true;
        }
        return false;
    }

    function setParent($_ds)
    {
        $this->Parent = $_ds;
        return true;
    }

    function Next()
    {
        return false;
    }

    function isLast()
    {
        return ($this->Current >= $this->RecsCount);
    }

    function getParam($_name)
    {
        $result = null;
        switch ($_name)
        {
            case '_current':
                $result = $this->Current;
            break;
            case '_count':
                $result = $this->RecsCount;
            break;
            case '_parent_current': // Deprecated. Please use the parent.current;
                $this->Parent->getParam('_current');
            break;
            case 'parent':
                $result = $this->Parent;
            break;
            default:
                if (isset($this->Items[$_name]))
                {
                    $result = $this->Items[$_name];
                }
                elseif (isset($this->Params[$_name])) 
                {
                    $result = $this->Params[$_name];
                }
                elseif (false !== strpos($_name, '.'))
                {
                    $nameParts = explode('.', $_name);
                    $target = $this;
                    for ($level = 0, $levels = count($nameParts); $level < $levels &&  (null !== $target); $level++)
                    {
                        $name = $nameParts[$level];
                        if (is_object($target))
                        {
                            if ($target instanceof CDataset_Abstract)
                            {
                                $target = $target->getParam($name);
                            }
                            elseif (method_exists($target, 'getParam'))
                            {
                                $target = $target->getParam($name);
                            }
                            elseif (method_exists($target, 'get'))
                            {
                                $target = $target->get($name);
                            }
                            elseif (isset($target->$name))
                            {
                                $target = $target->$name;
                            }
                            else
                            {
                                $target = null;
                            }
                        }
                        elseif (is_array($target) && isset($target[$name]))
                        {
                            $target = $target[$name];
                        }
                        else
                        {
                            $target = null;
                        }
                    }
                    $result = $target;
                }
                elseif (isset($this->Parent)) 
                {
                    $result = $this->Parent->getParam($_name);
                }
            break;
        }
        return $result;
    }

    function getParentParam($_name, $_uplevel = 1)
    {
        if ($_uplevel)
        {
            $_uplevel--;
            return ($this->Parent) ? $this->Parent->getParentParam($_name,$_uplevel) : null;
        }
        else
        {
            return $this->getParam($_name);
        }
    }

    function hasElements()
    {
        if ($this->RecsCount)
        {
            return true;
        }
        return false;
    }

    function setParams($_data)
    {
        $this->Params = $_data;
    }

    function addParams($_data, $_prefix = null)
    {
        $this->Params = array_merge(
            $this->Params,
            extKeys($_data, $_prefix)
        );
    }
    
    function addParam($_name, $_value)
    {
        $this->Params[$_name] = $_value;    
    }    

    function getXML()
    {
        return null;
    }
}
