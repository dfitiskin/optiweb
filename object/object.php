<?php

class CObject_Object
{
    public $Kernel = null;
    public $params = array();
	public $Name = null;

    function get($param)
    {
        $param = strtolower($param);
        $nameParts = explode('.', $param);
        $result = $this;
        while ($result && $nameParts)
        {
            if (is_object($result))
            {
                $result = $result->getExt($nameParts);
            }
            elseif (is_array($result) && isset($result[0]))
            {
            }
            elseif (is_array($result) && count($result))
            {
                $rec = $result;
                $result = $this->Kernel->link('object.object');
                $result->setup($rec);

                $result = $result->getExt($nameParts);
            }
            else
            {
                $result = null;
            }
        }

        if (is_array($result) && isset($result[0]))
        {
        }
        elseif (is_array($result) && count($result))
        {
            $rec = $result;
            $result = $this->Kernel->link('object.object');
            $result->setup($rec);
        }
        return $result;
    }

    private function getExt(& $parts)
    {
        $firstName = array_shift($parts);
        if (method_exists($this, $method = 'get' . $firstName))
        {
            $value = $this->$method();
        }
        elseif (isset($this->params[$firstName]))
        {
            $value = $this->params[$firstName];
        }
        else
        {
            $value = $this->getVirtual($firstName);
        }
        return $value;
    }

    private function getVirtual($name)
    {
        $value = null;
        switch ($name)
        {
            case 'this':
                $value = $this;
            break;
        }
        return $value;
    }

    function set($name, $value)
    {
        $name = strtolower($name);
        $nameParts = explode('.', $name);
        $realName = array_pop($nameParts);
        $object = $this;

        if ($nameParts)
        {
            $object = $this->getExt($nameParts);
        }

        if (is_object($object))
        {
            $object->setExt($realName, $value);
        }
    }

    private function setExt($name, $value)
    {
        if (method_exists($this, $method = 'set' . $name))
        {
            $this->$method($value);
        }
        else
        {
            $this->params[$name] = $value;
        }
    }

    function setup($params)
    {
        foreach ($params as $name => $value)
        {
			if ($name != 'type')
			{
	            $this->set($name, $value);
	            unset($value);
			}
        }
    }

    function toString()
    {

    }

    function __call($function, $params)
    {
        $result = null;
        if (preg_match('(^set([a-zA-Z_-]+)$)', $function, $parts))
        {
            $param = $parts[1];
            $value = $params[0];
            $result = $this->set($param, $value);
        }
        elseif (preg_match('(^get([a-zA-Z_-]+)$)', $function, $parts))
        {
            $param = $parts[1];
            $result = $this->get($param);
        }
        return $result;
    }

    function __set($name, $value)
    {
        return $this->set($name, $value);    
    }

    function __get($name)
    {
        return $this->get($name);
    }
}
