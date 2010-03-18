<?

global $Kernel;
$Kernel->LoadLib('modparams','backend');
class CNavigation_Params extends CBackend_ModParams
{

    function Init()
    {
        $this->ModesList  = array(
            'menu'       =>   0,
            'tree'       =>   1,
            'hierarchy'  =>   2,
            'header'     =>   3,
            'alias'      =>   4,
            'info'       =>   5,
            'regsel'     =>   6,
        );

        $this->Modes = array(
            array(
                'name'        =>        'menu',
                'desc'        =>        '����',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'tree',
                'desc'        =>        '������',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'hierarchy',
                'desc'        =>        '��������',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'header',
                'desc'        =>        '��������� �������',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'alias',
                'desc'        =>        '��������� �������',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'info',
                'desc'        =>        '���������� � �������',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'regsel',
                'desc'        =>        '����� ��������',
                'type'        =>        'b'
            ),
        );

        $this->Templates = array(
            'menu'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  '����',
//                    'file'        =>        'menu_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'tree'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  '������ ��������',
//                    'file'        =>        'tree_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'hierarchy'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  '��������',
//                    'file'        =>        'hierarchy_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'header'        =>        array(
            ),
            'alias'        =>        array(
            ),
            'info'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  '�������',
//                    'file'        =>        'info_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'regsel'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  '�������',
                    'file'  =>  'regsel_main.tpl',
                    'type'  =>  'u'
                )
            ),

        );

        $this->Params = array(
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
                    'name'        =>        'navtype2',
                    'type'        =>        'navtype_null',
                    'value'        =>        null,
                    'desc'  =>  '���2',
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
            'tree'        =>        array(
                array(
                    'name'   =>  'navtype',
                    'type'   =>  'navtype',
                    'value'  =>  null,
                    'desc'   =>  '���',
                ),
                array(
                    'name'      =>  'level',
                    'desc'          =>  '�������',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  '������',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  3
                        ),
                    )
                ),
                array(
                    'name'      =>  'base_url',
                    'desc'          =>  '����� �����',
                    'type'      =>  'sel',
                    'value'                =>        1,
                    'values'    =>  array(
                        array(
                            'name'  =>  '������',
                            'value' =>  0
                        ),
                        array(
                            'name'  =>  '�������',
                            'value' =>  1
                        ),
                    )
                )
            ),
            'hierarchy'        =>        array(
                array(
                    'name'      =>  'order',
                    'desc'      =>  '�������',
                    'type'      =>  'sel',
                    'value'     =>  1,
                    'values'    =>  array(
                        array(
                            'name'  =>  '������',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  '��������',
                            'value' =>  -1
                        ),
                    )
                ),
            ),
            'header'        =>        array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  '�������',
                    'type'      =>  'sel',
                    'value'                =>        2,
                    'values'    =>  array(
                        array(
                            'name'  =>  '�������',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  3
                        )
                    )
                )
            ),
            'alias'        =>        array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  '�������',
                    'type'      =>  'sel',
                    'value'                =>        2,
                    'values'    =>  array(
                        array(
                            'name'  =>  '�������',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  3
                        )
                    )
                )
            ),
            'info'  =>  array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  '�������',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  '�������',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  '������',
                            'value' =>  3
                        )
                    )
                )
            ),
            'regsel'        =>        array(
            ),
        );
    }

}
?>