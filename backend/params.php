<?

global $Kernel;
$Kernel->LoadLib('modparams','backend');
class CBackend_Params extends CBackend_ModParams
{

    function Init()
    {
        $this->ModesList  = array(
            'main'      => 0,
            'personal'  => 1,
        );

        $this->Modes = array(
            array(
                'name'        =>        'main',
                'desc'        =>        'Главное меню',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'personal',
                'desc'        =>        'Персональное меню',
                'type'        =>        'b'
            ),            
        );

        $this->Templates = array(
            'main'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  'Главное меню',
                    'type'  =>  'b'
                )
            ),
            'personal'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  'Персональное меню',
                    'type'  =>  'b'
                )
            ),
            

/*        
            'menu'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  'Меню',
                    'type'        =>        'u'
                )
            ),
*/            
        );

        $this->Params = array(
            'menu'                =>        array(
            ),
            'personal'            =>        array(
            ),
            

/*
            'menu'                =>        array(
                array(
                    'name'        =>        'navtype',
                    'type'        =>        'navtype',
                    'value'        =>        null,
                    'desc'  =>  'Тип',
                ),
                array(
                    'name'        =>        'navtype1',
                    'type'        =>        'navtype_null',
                    'value'        =>        null,
                    'desc'  =>  'Тип1',
                ),
                array(
                    'name'      =>  'level',
                    'desc'      =>  'Уровень',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Текущий',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  'Первый',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  'Второй',
                            'value' =>  3
                        ),
                        array(
                            'name'  =>  'Третий',
                            'value' =>  4
                        )
                    )
                ),
            ),
*/
        );
    }

}
?>