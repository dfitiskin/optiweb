<?php

loadlib('State.State');

class CState_Edit extends CState_State
{
    public function dataset($ds, $storage)
    {
        $ds->set('controller', $this->this);
        $ds->set('formmode', $this->objectName);
        if (! $this->form)
		{
		    $form = $storage->findOne($this->objectName, $this->objectFilters);
		}
		else
		{
		    $form = $this->form;
		}
		$ds->set('formaction', 'edit');
		$ds->set('form', $form);
    }
}