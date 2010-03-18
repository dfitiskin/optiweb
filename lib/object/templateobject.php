<?php

loadlib('object.object');

class CObject_TemplateObject extends CObject_Object
{
    function setParent(& $_obj)
    {
        $this->setParent($_obj);
    }

    function getParam($_name)
    {
        $_value = $this->get($_name);

        if (is_object($_value))
        {
            $_value = $_value->toString();
        }

        return $_value;
    }

    function & getChildDs($_name)
    {
        $_value = null;
        switch ($_name)
        {
            case '_switch':
                $_value = & $this->Kernel->link('dataset.switch');
                $_value->setParent($this);
            break;
            default:
		        $_value = & $this->_object->get($_name);

		        if (is_object($_value))
		        {
		            $_obj = & $this->Kernel->link('object.templateobject');
		            $_obj->setObject($_value);
		            $_value = & $_obj;
		        }
		        else
		        {
		            $_value = & $this->Kernel->link('dataset.abstract');
		        }
            break;
        }
        return $_value;
    }

    function refresh()
    {
    }

    function next()
    {
    }

    function isLast()
    {
        if ($this->_object->listable())
        {
            return $this->_object->isLast();
        }
        else
        {
            return true;
        }
    }

    function hasElements()
    {
        if ($this->_object->listable())
        {
            return $this->_object->hasElements();
        }
        else
        {
            return false;
        }
    }
}
