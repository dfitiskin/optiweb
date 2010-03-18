<?php

class CHtmlEditor_Main
{
    var $MainJsSended = false;

    function Init()
    {
		$this->Name = 'htmleditor';
    }
    
    function Execute($_params)
    {
        $height = isset($_params['height']) ? $_params['height'] : "400";
        if (!isset($_params['state']))
        {
            $_params['state'] = true;
        }
        
        $_code = array();
        $_code[] = '<textarea style="height: ' . $height . 'px" class="inn" name="'.$_params['editor_name'].'" id="fld'.$_params['editor_name'].'">'.htmlspecialchars(stripslashes($_params['htmldoc'])).'</textarea>';

        if (!isset($_params['nowysiwyg']) || !$_params['nowysiwyg'])
        {
            if (!isset($this->Kernel->MainJsSended) || !$this->Kernel->MainJsSended)
            {
                global $User;
                $profile = $User->GetCurrentProfile('alias');
                
                
                $_code[] = '<script language="javascript" type="text/javascript" src="/scripts/htmleditor3/tiny_mce.js"></script>';
                $_code[] = '<script language="javascript" type="text/javascript" src="/scripts/htmleditor3/htmleditor3.js"></script>';
                
                $_code[] = '<script language="Javascript" type="text/javascript" src="/scripts/editarea1/edit_area_full.js"></script>';
                //$_code[] = '<link rel="stylesheet" type="text/css" href="/profiles/' . $profile . '/_templs/content.css" />';
                $this->Kernel->MainJsSended = true;
            }
            
            $_x = "'fld".$_params['editor_name']."'";
            $_check = "this.value=(this.value=='html mode' ? 'design mode' : 'html mode')";
            
            if ($_params['state'])
            {
                $_code[] = '<input type="button" value="html mode" onclick="toggleEditor('.$_x.'); '.$_check.'; return false;" />';
                $_code[] = '<script  language="javascript" type="text/javascript">';
                $_code[] = 'toggleEditor("fld'.$_params['editor_name'].'");';
                $_code[] = '</script>';
            }
            else
            {
                $_code[] = '<input type="button" value="design mode" onclick="toggleEditor('.$_x.'); '.$_check.'; return false;" />';
            }
        }
        return implode("\r\n", $_code);
    }            
}

?>
