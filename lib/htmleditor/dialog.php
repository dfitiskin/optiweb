<?

class CHTMLEditor_Dialog
{

        function Init()
    {
            $this->Dialogs = array(
                'colorpicker' => array(
                    'template'        =>        array(
                        'file'        =>        'dlg_colorpicker.tpl'
                ),
                'lang_alias'        =>        'colorpicker'
            ),
                'table' => array(
                    'template'        =>        array(
                        'file'        =>        'dlg_table.tpl'
                ),
                'lang_alias'        =>        'table_prop'
            ),
                'td' => array(
                    'template'        =>        array(
                        'file'        =>        'dlg_table_cell.tpl'
                ),
                'lang_alias'        =>        'table_cell_prop'
            ),
                'tr' => array(
                    'template'        =>        array(
                        'file'        =>        'dlg_table_row.tpl'
                ),
                'lang_alias'        =>        'table_row_prop'
            ),
                'hr' => array(
                    'template'        =>        array(
                        'file'        =>        'dlg_hr.tpl'
                ),
                'lang_alias'        =>        'hr_prop'
            ),
            'confirm'        => array(
                'template'  =>  array(
                        'file'      =>  'dlg_confirm.tpl'
                ),
                'lang_alias'        =>        null
            ),
            'hlink'        => array(
                'template'  =>  array(
                        'file'      =>  'dlg_hlink.tpl'
                ),
                'lang_alias'        =>        'hlink_prop'
            ),
            'specialchar'        => array(
                'template'  =>  array(
                        'file'      =>  'dlg_specialchar.tpl'
                ),
                'lang_alias'        =>        'specialchar'
            ),
            'images'        => array(
                'template'  =>  array(
                        'file'      =>  'dlg_images.tpl'
                ),
                'dataset'                =>        'htmleditor.images_dlg',
                'lang_alias'        =>        'image_insert'
            ),
            'imageprop'        => array(
                'template'  =>  array(
                        'file'      =>  'dlg_imageprop.tpl'
                ),
                'lang_alias'        =>        'image_prop'
            ),

        );
        $this->Name = 'htmleditor';
    }

    function Execute($_params)
    {
        $_dialog_name = array_shift($_params);
        $_lang = array_shift($_params);
        $_theme = array_shift($_params);

        if (isset($this->Dialogs[$_dialog_name]))
        {
            $_dir = GEN_DATA_PATH.$this->Name.'/';
            $_labels = include($_dir.'lang/'.$_lang.INC_CFG_EXT);
            $_dialog_params = $this->Dialogs[$_dialog_name];
            $_message = null;

            if (isset($this->Dialogs[$_dialog_name]['dataset']))
            {
                $_lang_alias = $this->Dialogs[$_dialog_name]['lang_alias'];

                $DLG = &$this->Kernel->Link($this->Dialogs[$_dialog_name]['dataset']);
                $_link_params = array($_dialog_name,$_lang,$_theme);
                $DLG->InitParams($_link_params,$_params);
                $_dialog_params['template'] = $DLG->getTemplate();
                $_ds = $DLG->getDS();
            }
            else
            {
                $_lang_alias = isset($_params[0])?$_params[0]:$this->Dialogs[$_dialog_name]['lang_alias'];
                $_message = isset($_params[1])?$_params[1]:null;
                $_ds = &$this->Kernel->Link('dataset.abstract');
            }

            $_ds_params = $_labels[$_lang_alias];

            if ($_message)
            {
                $_ds_params['message'] = $_ds_params[$_message];
            }

            $_ds_params['lang'] = $_lang;
            $_ds_params['theme'] = $_theme;
            $_ds_params['theme_url'] = GEN_DATA_URL.$this->Name.'/themes/'.$_theme.'/';
            $_ds->addParams($_ds_params);

            $TplManager = &$this->Kernel->Link('template.manager',true);
            $_result = $TplManager->Execute($_ds,$_dialog_params['template'],$this->Name);
        }
        else $_result = 'Диалога нету !';

        return $_result;
    }
}

?>