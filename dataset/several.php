<?php

class CDataset_Several extends CDataset_Abstract
{
    public $itemDs = array(); // Массив DataSet'ов
    public $currentDs = null; // Текущий DataSet
    public $current = 0;      // Текущий 
    
    /**
     *  Добавляем DataSet в начало
     *  @param DataSet $_ds
     */
    
    function addFist(& $_ds)
    {
        $_ds->Parent = & $this;
        array_unshift($this->itemDs, & $_ds);
    }
    
    /**
     *  Добавляем DataSet в конец
     *  @param DataSet $_ds
     */
    
    function addLast(& $_ds)
    {
        $_ds->Parent = & $this;
        array_push($this->itemDs, & $_ds);
    }
    
    
    function & GetChildDS($_name)
    {
        $_ds = null;
        if ($this->currentDs)
        {
            $_ds = & $this->currentDs->GetChildDS($_name);
        }
        else
        {
            $_ds = & parent::GetChildDS($_name);
        }
        return $_ds;
    }
    
    function GetParam($_name)
    {
        $_param = null;
        if ($this->currentDs)
        {
            $_param = $this->currentDs->GetParam($_name);
        }
        else
        {
            $_param = parent::GetParam($_name);
        }
        return $_param;    
    }
    
    
    function Refresh() 
    { 
        $this->current = 0;
        $this->currentDs = & $this->itemDs[$this->current];
        $this->currentDs->Refresh();
    }
    
    function Next()
    {
        $_res = false;
        if ($this->currentDs)
        {
            $_res = $this->currentDs->next();   
        }
        
        if (!$_res)
        {
            while ($this->nextDS() && ! $_res)
            {
                $_res = $this->currentDs->next();
            }
        }
        return $_res;
    }
    
    function nextDS()
    {
        $this->current++;
        if (isset($this->itemDs[$this->current]))
        {
            $this->currentDs = & $this->itemDs[$this->current];
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>