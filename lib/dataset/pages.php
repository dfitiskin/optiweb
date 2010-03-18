<?php

global $Kernel;
$Kernel->LoadLib('array','dataset');

class CDataset_Pages extends  CDataset_Array
{
    public $FirstPage = 1;
    public $LastPage = 1;
    public $PageRange = 100;

    function Refresh()
    {
        $this->updateBorders();
        $_data = array();
        for($i=$this->FirstPage;$i<=$this->LastPage;$i++)
        {
            $_data[] = array(
                'page'        =>        $i
            );
        }
        if (sizeof($_data)==1) $_data = array();
        $this->setData($_data);

        $this->Params['active_page'] = $this->ActivePage;
        $this->Params['max_page'] = ceil($this->RecordsCount/$this->RecordsOnPage);
        $this->Params['next_page'] = min($this->ActivePage+1,ceil($this->RecordsCount/$this->RecordsOnPage));
        $this->Params['prev_page'] = max($this->ActivePage-1,1);
        $this->Params['prev_page2'] = max($this->FirstPage-1,1);
        $this->Params['next_page2'] = min($this->LastPage+1,ceil($this->RecordsCount/$this->RecordsOnPage));
        parent::Refresh();
    }

    function setRecordsCount($_count)
    {
        $this->RecordsCount = $_count;
    }

    function setRange($_range)
    {
        $this->PageRange = $_range;
    }

    function updateBorders()
    {
        $this->FirstPage = $this->LastPage = ($this->ActivePage > 0) ? $this->ActivePage : 1;
        $_lborder = ceil($this->RecordsCount/$this->RecordsOnPage);

        if ($this->LastPage - $this->FirstPage != $this->PageRange-1)
        {
            $_w = $this->LastPage - $this->FirstPage - $this->PageRange + 1;
            if ($_w < 0)
            {
                $i = 1;
            }
            else
            {
                $i = -1;
            }

            while($_w != 0 && ($this->LastPage < $_lborder || $this->FirstPage > 1))
            {
                if ($_w != 0 && $this->LastPage < $_lborder)
                {
                    $this->LastPage+=$i;
                    $_w += $i;
                }

                if ($_w != 0 && $this->FirstPage > 1)
                {
                    $this->FirstPage -= $i;
                    $_w+=$i;
                }
            }
        }
    }

    function setActivePage($_page)
    {
        $this->ActivePage = $_page;
    }

    function setRecordsOnPage($_records)
    {
        $this->RecordsOnPage = $_records;
    }

    function getParam($_name)
    {
        switch($_name)
        {
            case 'is_active':
                return ($this->Items['page'] == $this->ActivePage);
            break;
            case 'first_rec':
                return ($this->Items['page']-1)*$this->RecordsOnPage+1;
            break;
            case 'last_rec':
                return min($this->Items['page']*$this->RecordsOnPage,$this->RecordsCount);
            break;
        }
        return parent::getParam($_name);
    }
}

?>