<?php

loadlib('state.state');

class CState_Add extends CState_State
{
    public function dataset($ds, $storage)
    {
        $ds->set('controller', $this->this);
        $ds->set('formmode', $this->objectName);
		$ds->set('formaction', 'add');
		$ds->set('form', $this->form);
    }
}