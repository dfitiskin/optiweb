<?php

//------------------------------------------------------------------------------
// Module : System
// Class  : CMS Planner
// Ver    : 0.1 beta
// Date   : 25.09.2004
// Desc   : Планировщик
//------------------------------------------------------------------------------

class CSystem_Planner
{
    public $Tables = null;             // Таблицы

//------------------------------------------------------------------------------
// Инициализация планировщика
//------------------------------------------------------------------------------
    function Init()
    {
        $this->Tables = array(
            'modules'        =>        'be_modules',
            'pmodules'       =>        'be_planner_modules',
            'sessions'       =>        'be_planner_sessions'
        );
        $this->SessionTime = 40;
    }

//------------------------------------------------------------------------------
// Выполнение планировщика
//------------------------------------------------------------------------------
    function Execute()
    {
        $DbManager = &$this->Kernel->Link('database.manager');
        $DbManager->Select(
            $this->Tables['modules'].' m,'.$this->Tables['pmodules'].' p',
            'm.alias,p.priority as rate',
            'm.id = p.mid and p.blocked = 0 ',
            'order by rate'
        );
        while (($_rec = $DbManager->getNextRec()) && ($this->getTimeOut()>0))
        {
            $Object = &$this->Kernel->Link($_rec['alias'].'.planner');
            $Object->Execute($this->getTimeOut());
        }
        echo($this->getTimeOut().'|'.$this->Kernel->getWorkTime());
    }

//------------------------------------------------------------------------------
// Получение времени выполнения планировщика
//------------------------------------------------------------------------------
    function getTimeOut()
    {
        return $this->SessionTime - $this->Kernel->getWorkTime();
    }

}

?>