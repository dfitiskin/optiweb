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
                'desc'        =>        '������� ����',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'personal',
                'desc'        =>        '������������ ����',
                'type'        =>        'b'
            ),            
        );

        $this->Templates = array(
            'main'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  '������� ����',
                    'type'  =>  'b'
                )
            ),
            'personal'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  '������������ ����',
                    'type'  =>  'b'
                )
            ),
            

/*        
            'menu'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  '����',
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
                    'desc'  =>  '���',
                ),
                array(
                    'name'        =>        'navtype1',
                    'type'        =>        'navtype_null',
                    'value'        =>        null,
                    'desc'  =>  '���1',
                ),
                array(
                    'name'      =>  'level',
                    'desc'      =>  '�������',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  '�������',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  3
                        ),
                        array(
                            'name'  =>  '������',
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