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
                'desc'        =>        'Меню',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'tree',
                'desc'        =>        'Дерево',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'hierarchy',
                'desc'        =>        'Иерархия',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'header',
                'desc'        =>        'Заголовок раздела',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'alias',
                'desc'        =>        'Псевдоним раздела',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'info',
                'desc'        =>        'Информация о разделе',
                'type'        =>        'b'
            ),
            array(
                'name'        =>        'regsel',
                'desc'        =>        'Выбор регионов',
                'type'        =>        'b'
            ),
        );

        $this->Templates = array(
            'menu'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  'Меню',
//                    'file'        =>        'menu_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'tree'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  'Дерево разделов',
//                    'file'        =>        'tree_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'hierarchy'        =>        array(
                array(
                    'name'        =>        'main',
                    'desc'  =>  'Иерархия',
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
                    'desc'  =>  'Главный',
//                    'file'        =>        'info_main.tpl',
                    'type'        =>        'u'
                )
            ),
            'regsel'        =>        array(
                array(
                    'name'  =>  'main',
                    'desc'  =>  'Главный',
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
                    'desc'  =>  'Тип',
                ),
                array(
                    'name'        =>        'navtype1',
                    'type'        =>        'navtype_null',
                    'value'        =>        null,
                    'desc'  =>  'Тип1',
                ),
                array(
                    'name'        =>        'navtype2',
                    'type'        =>        'navtype_null',
                    'value'        =>        null,
                    'desc'  =>  'Тип2',
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
            'tree'        =>        array(
                array(
                    'name'   =>  'navtype',
                    'type'   =>  'navtype',
                    'value'  =>  null,
                    'desc'   =>  'Тип',
                ),
                array(
                    'name'      =>  'level',
                    'desc'          =>  'Уровень',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Первый',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  'Второй',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  'Третий',
                            'value' =>  3
                        ),
                    )
                ),
                array(
                    'name'      =>  'base_url',
                    'desc'          =>  'Базой адрес',
                    'type'      =>  'sel',
                    'value'                =>        1,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Корень',
                            'value' =>  0
                        ),
                        array(
                            'name'  =>  'Текущий',
                            'value' =>  1
                        ),
                    )
                )
            ),
            'hierarchy'        =>        array(
                array(
                    'name'      =>  'order',
                    'desc'      =>  'Порядок',
                    'type'      =>  'sel',
                    'value'     =>  1,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Прямой',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  'Обратный',
                            'value' =>  -1
                        ),
                    )
                ),
            ),
            'header'        =>        array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  'Уровень',
                    'type'      =>  'sel',
                    'value'                =>        2,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Текущий',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  'Первый',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  'Второй',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  'Третий',
                            'value' =>  3
                        )
                    )
                )
            ),
            'alias'        =>        array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  'Уровень',
                    'type'      =>  'sel',
                    'value'                =>        2,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Текущий',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  'Первый',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  'Второй',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  'Третий',
                            'value' =>  3
                        )
                    )
                )
            ),
            'info'  =>  array(
                array(
                    'name'      =>  'level',
                    'desc'          =>  'Уровень',
                    'type'      =>  'sel',
                    'value'     =>  2,
                    'values'    =>  array(
                        array(
                            'name'  =>  'Текущий',
                            'value' =>  -1
                        ),
                        array(
                            'name'  =>  'Первый',
                            'value' =>  1
                        ),
                        array(
                            'name'  =>  'Второй',
                            'value' =>  2
                        ),
                        array(
                            'name'  =>  'Третий',
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