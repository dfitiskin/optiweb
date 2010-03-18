<?php

loadlib('object.object');

class CObject_List extends CObject_Object
{
    var $items = array();
    var $current = 0;
    var $count = 0;

    function add($item)
    {
        $item->set('list', $this);
        $this->items[] = $item;
    }

    function getVirtual($name)
    {
        switch ($name)
        {
            case 'first?':
                $value = $this->isFirst();
            break;
            case 'last?':
                $value = $this->isLast();
            break;
            case 'even?':
                $value = $this->getKey() % 2 == 0;
            break;
            case 'odd?':
                $value = $this->getKey() % 2 == 1;
            break;
            case 'empty?':
                $value = !$this->hasElements();
            break;
            default:
                $value = parent::getVirtual($name);
            break;
        }
        return $value;
    }

    function getItemByKey($key)
    {
        $realKey = $key - 1;
        $result = null;
        if (isset($this->items[$realKey]))
        {
            $result = $this->items[$realKey];
        }
        return $result;
    }

    function getKey()
    {
        return $this->current;
    }

    function getLength()
    {
        return count($this->items);
    }

    function getCurrent()
    {
        return $this->getItemByKey($this->getKey());
    }

    function getNext()
    {
        $item = $this->getItemByKey($this->getKey() + 1);
        return $item;
    }

    function getPrev()
    {
        return $this->getItemByKey($this->getKey() - 1);
    }

    function isLast($key = null)
    {
        $key = $key ? $key : $this->getKey();
        return $this->getLength() <= $key;
    }

    function isFirst($key = null)
    {
        $key = $key ? $key : $this->getKey();
        return $key == 1;
    }

    function nextItem()
    {
        $result = false;
        if (!$this->isLast())
        {
            $this->current += 1;
            $result = $this->getCurrent();
        }
        return $result;
    }

    function refresh()
    {
        $this->current = 0;
    }

    function hasElements()
    {
        return $this->getLength() > 0;
    }

    /* 
        Новые функции
    */

    function reverse()
    {
        $this->refresh();
        $this->items = array_reverse($this->items);
    }

    function itemByStep($step = 0) 
    {
        return $this->getItemByKey($this->getKey() + $step);
    }

    function merge($list)
    {
        $arr = $this;
        foreach ($list->items as $item)
        {
            $arr->add($item);
        }
        $this->refresh();
        return $arr;
    }

    function createNumList($count)
    {
        for ($i=1; $i<=$count; $i++)
        {
            $object = $this->Kernel->link('object.object');
            $object->set("name",$i);
            $this->add($object);
        }
    }

    function removeById($id)
    {
        $items = $this->Kernel->link('object.list');
        while ($item = $this->nextItem())
        {
            if($item->get('id') != $id)
            {
                $items->add($item);
            }
        }
        $this->items = $items->items;
        $this->refresh();
        
        /*foreach ($this->items as $item)
        {
            print "<br><b>Оставшиеся элементы=".$item->get('id')."</b><br>";
        }
        $this->refresh();*/
    }
}
