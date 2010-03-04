<?php

if(!defined('CRLF'))
{
    define('CRLF', chr(13).chr(10));
}

class CDataBase_mysql
{
    public $DBLink = null;
    public $QCount = 0;
    public $Config = null;
    public $Resource = null;
//  public $CurrentD =B;

    public $DI_Header = array();
    public $DI_Buffer = array();
    public $DI_Length = array();

        //----------------------------------------------------------------
        //                Открывает соединение с БД
        //----------------------------------------------------------------
    function Init()
    {
            $this->DBLink = null;
            $this->Config = $this->Kernel->LoadConfig('system','database');
    }

    function OpenDB()
    {
        if ($this->DBLink == null)
        {
            if ( !( $this->DBLink = @mysql_connect($this->Config['host'],$this->Config['user'],$this->Config['pass']) ) )
            {
                user_error('Can not link to DataBase');
            }
            else
            {
                $_row = mysql_get_server_info($this->DBLink);
                $match = explode('.', $_row);
                if (!isset($row))
                {
                    $this->intMySQLVersion = 32332;
                    $this->strMySQLVersion = '3.23.32';
                }
                else
                {
                    $this->intMySQLVersion = (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2]));
                    $this->strMySQLVersion = $row;
                }
            }

            if ( !mysql_select_db($this->Config['dbname'], $this->DBLink) )
            {
                user_error('Error in DataBase');
            }
            //$this->Query('SET NAMES cp1251;');
        }
    }

        //----------------------------------------------------------------
        //        Возвращает кол-во записей из внутреннего дескриптора выборки
        //----------------------------------------------------------------
        function getNumRows($_res = null)
        {
            if ($this->Resource === false)
            {
                Error('Mysql resource not setted','mysql');
                return false;
            }

            return $_res ? mysql_num_rows($_res) : mysql_num_rows($this->Resource);
        }

        function getNumFields($_res = null)
        {
            if ($this->Resource === false)
            {
                Error('Mysql resource not setted','mysql');
                return false;
            }

            return $_res ? mysql_num_fields($_res) : mysql_num_fields($this->Resource);
        }

        function sqlAddSlashes($_string = '', $_like = FALSE, $_crlf = FALSE)
        {
            if ($_like)
            {
                $_string = str_replace('\\', '\\\\\\\\', $_string);
            }
            else
            {
                $_string = str_replace('\\', '\\\\', $_string);
            }

            if ($_crlf)
            {
                $_string = str_replace("\n", '\n', $_string);
                $_string = str_replace("\r", '\r', $_string);
                $_string = str_replace("\t", '\t', $_string);
            }

            $_string = str_replace('\'', '\'\'', $_string);

            return $_string;
        }

        function backquote($_name, $_doit = true)
        {
            if ($_doit && !empty($_name) && $_name != '*')
            {
                if (is_array($_name))
                {
                     $result = array();
                     foreach ($_name as $_key => $_val)
                     {
                         $result[$_key] = '`' . $_val . '`';
                     }
                     return $result;
                }
                else
                {
                    return '`' . $_name . '`';
                }
            }
            else
            {
                return $_name;
            }
        }




        function getAffectedRows($_res = null)
        {
            if ($this->Resource === false)
            {
                Error('Mysql resource not setted','mysql');
                return false;
            }

            return is_resource($_res)           ? mysql_affected_rows($_res) :
                   is_resource($this->Resource) ? mysql_affected_rows($this->Resource):
                                                  mysql_affected_rows();
        }

        //----------------------------------------------------------------
        //        Возвращает кол-во записей из таблицы $_table удовлетворяющих
        //        условию $_exp
        //----------------------------------------------------------------
        function GetRecsCount($_table, $_exp = false, $_view_sql = false)
        {
            $this->Select($_table, "count(*) as count", $_exp, null, $_view_sql);
            $_row = $this->GetNextRec();
            return $_row['count'];
        }

        /**
         * Возвращает кол-во записей из таблицы $_table удовлетворяющих условию
         * $_exp
         *
         * @param string table Таблица или вид
         * @param string field Поле
         * @param string exp Условие отбора
         * @param boolean view_sql Вывести SQL запрос, для отладки
         *
         * @return integer Кол-во элементов, удовлетворяющих запросу
         */
        //
        //
        //----------------------------------------------------------------
        function GetRecsCountExt($_table, $_field, $_exp = false, $_view_sql = false)
        {
            $this->Select($_table, "count(".$_field.") as count", $_exp, null, $_view_sql);
            $_row = $this->GetNextRec();
            return $_row['count'];
        }


        //----------------------------------------------------------------
        //                Возвращает последний добавленный индиыикатор кортежа (id)
        //----------------------------------------------------------------
        function GetLastID()
        {
            $this->OpenDB();
            return mysql_insert_id($this->DBLink);
        }

        //----------------------------------------------------------------
        //        Вовращает очередной кортеж из внутреннего или внешнего
        // дескриптора
        //----------------------------------------------------------------
        function GetNextRec($_res = null, $assoc = true)
        {
            if ($assoc)
            {
                $fetchMethod = 'mysql_fetch_assoc';
            }
            else
            {
                $fetchMethod = 'mysql_fetch_row';
            }
        
            return $_res ? $fetchMethod($_res) : ($this->Resource ? $fetchMethod($this->Resource) : trigger_error("argument is not a valid MySQL result resource", E_USER_NOTICE));
        }

        function getNextRecID($_res = null)
        {
            return $_res?mysql_fetch_row($_res):mysql_fetch_row($this->Resource);
        }

        //----------------------------------------------------------------
        //                Выполняет запрос $_sql
        //----------------------------------------------------------------
        function &Query($_sql,$_view_sql = false)
        {
            $this->OpenDB();
            $this->QCount++;
            if ($_view_sql) echo($_sql);
            if (!($this->Resource = mysql_query($_sql,$this->DBLink)))
            {
                if (!$_view_sql)
                {
                    user_error("SQL запрос: ".$_sql, E_USER_NOTICE);
                }
                Error(iconv('KOI8-R', 'CP1251', mysql_error($this->DBLink)),'mysql');
            }
            return $this->Resource;
        }


        //----------------------------------------------------------------
        //                Производит выборку данных(полей $_fields) из таблицы $_table
        //        с условием $_exp и параметрами $_opt
        //----------------------------------------------------------------
        function &Select($_table, $_fields = '*', $_cond = null, $_params = null,$_view_sql = false)
        {
                if ($_cond) $_cond = " WHERE ".$_cond;
                $_sql = "SELECT ".$_fields." FROM ".$_table." ".$_cond." ".$_params." ;";
//print $_sql.'<br>';
                $this->Query($_sql,$_view_sql);
                return $this->Resource;
        }

        //----------------------------------------------------------------
        //                Вставляет кортеж(и) в таблицу $_table со значениями $_values
        //        в поля $_fields
        //----------------------------------------------------------------
        function &Insert($_table, $_values, $_fields = false, $_view_sql = false)
        {
                if ( $_fields !== false) $_fields = "(".$_fields.")";
                $_sql = "INSERT INTO ".$_table.$_fields."  VALUES(".$_values.");";
        $this->Query($_sql,$_view_sql);
                return $this->Resource;
        }

        //----------------------------------------------------------------
        // Заменяет или добавляет новый кортеж(и) в таблицу $_table со значениями $_values
        // в поля $_fields
        //----------------------------------------------------------------
        function &Replace($_table, $_values, $_fields = false, $_view_sql = false)
        {
            if ( $_fields !== false)
            {
                $_fields = "(".$_fields.")";
            }
            $_sql = "REPLACE INTO ".$_table.$_fields."  VALUES(".$_values.");";
            $this->Query($_sql,$_view_sql);
            return $this->Resource;
        }


        //----------------------------------------------------------------
        //        Удаляет кортежи из таблицы $_table удовлетворяющие условию
        //        $_exp
        //----------------------------------------------------------------
        function &Delete($_table, $_exp = false,$_view_sql = false)
        {
                if ($_exp !== false) $_exp = " WHERE ".$_exp;
                $_sql = "DELETE FROM ".$_table." ".$_exp." ;";
        $this->Query($_sql,$_view_sql);
                return $this->Resource;
        }

        //----------------------------------------------------------------
        //                Обновляет кортеж(и) из таблицы $_table удовлетворяющие
        //        условию $_exp с помощью присваиваний $_eqs
        //----------------------------------------------------------------
        function &Update($_table, $_eqs, $_exp = false, $_view_sql = false)
        {
            if ($_exp !== false)
            {
                $_exp = " WHERE ".$_exp;
            }

            $_sql = "UPDATE ".$_table." SET ".$_eqs." ".$_exp." ;";
            $this->Query($_sql,$_view_sql);
            return $this->Resource;
        }

    //----------------------------------------------------------------
        //                Выбирает кортежи из таблицы $_table удовлетворяющие условию
        //        принадлежности атрибута $_field одному из значений массива
        // $_items
        //----------------------------------------------------------------
        function SelectValues($_table,$_field,$_items,$_fields='*',$_expadd = null, $_params = null, $_view_sql=false)
        {

            $_exp  = "";
              foreach($_items as $k=>$v)
                {
                        if ($_exp!="") $_exp.=",";
                        $_exp.="$v";
                }
                $_exp = $_field." IN (".$_exp.")";
        if ($_expadd) $_exp = $_expadd.' and '.$_exp;

                return sizeof($_items)?$this->Select($_table, $_fields, $_exp, $_params, $_view_sql):false;
        }

    //----------------------------------------------------------------
        //                Вставляет кортеж в таблицу $_table со значениями и полями
        //        из массива $_items
        //----------------------------------------------------------------
        function &InsertValues($_table,$_items,$_view_sql = false)
        {
                $_values = "";
                $_fields = "";

            foreach($_items as $k=>$v)
                {
                        if ($_values != "") $_values.=",";
                        if ($_fields != "") $_fields.=",";
            if (!preg_match('/^(now|date)\\(\\)/is',$v)) $v = "'".$v."'";
                        $_values.="$v";
                        $_fields.=$k;
                }
                return $this->Insert($_table,$_values,$_fields,$_view_sql);
        }

        //----------------------------------------------------------------
        // Вставляет кортеж в таблицу $_table со значениями и полями
        // из массива $_items
        //----------------------------------------------------------------
        function &ReplaceValues($_table,$_items,$_view_sql = false)
        {
            $_values = "";
            $_fields = "";

            foreach($_items as $k=>$v)
            {
                if ($_values != "") $_values.=",";
                if ($_fields != "") $_fields.=",";
                if (!preg_match('/^(now|date)\\(\\)/is',$v)) $v = "'".$v."'";
                $_values.="$v";
                $_fields.=$k;
            }
            return $this->Replace($_table,$_values,$_fields,$_view_sql);
        }

    //----------------------------------------------------------------
        //                Удаляет кортежи из таблицы $_table удовлетворяющие условию
        //        принадлежности атрибута $_field одному из значений массива
        // $_items
        //----------------------------------------------------------------
        function DeleteValues($_table,$_field,$_items,$_exp_add=null,$_view_sql=false)
        {

            $_exp  = "";
              foreach($_items as $k=>$v)
                {
                        if ($_exp!="") $_exp.=",";
                        $_exp.="$v";
                }
                $_exp = $_field." IN (".$_exp.")";
        if ($_exp_add) $_exp .= ' AND '.$_exp_add;

                return sizeof($_items)?$this->Delete($_table, $_exp,$_view_sql):false;
        }

        //----------------------------------------------------------------
        //                Обновляет кортеж(и) из таблицы $_table удовлетворяющие
        //        условию $_exp с помощью присваиваний из массива $_items
        //----------------------------------------------------------------
        function UpdateValues($_table,$_items,$_exp = false,$_view_sql=false)
        {
            $_eqs = false;
            foreach($_items as $_key => $_value)
            {
                if ($_eqs)
                {
                    $_eqs .= ", ";
                }

                if (!preg_match('/^(now|date)[^\(]*\([^\)]*\)|null/is',$_value))
                {
                    $_value = "'".$_value."'";
                }
                $_eqs .= $_key."=".$_value;
            }
            return $_eqs ? $this->Update($_table,$_eqs,$_exp,$_view_sql) : false;
        }


       function UpdateSetOf($_table,$_eqs,$_field,$_items, $_view_sql=false)
       {
           $_exp  = "";
             foreach($_items as $k=>$v)
               {
                       if ($_exp!="") $_exp.=",";
                       $_exp.="$v";
               }
               $_exp = $_field." IN (".$_exp.")";

               return sizeof($_items)?$this->Update($_table,$_eqs,$_exp,$_view_sql):false;

       }

        function UpdateSetOfValues($_table,$_values,$_field,$_items, $_view_sql=false)
        {
            $_exp  = "";
              foreach($_items as $k=>$v)
                {
                        if ($_exp!="") $_exp.=",";
                        $_exp.="$v";
                }
                $_exp = $_field." IN (".$_exp.")";

                return sizeof($_items)?$this->UpdateValues($_table,$_values,$_exp,$_view_sql):false;

        }

        //----------------------------------------------------------------
        // Очищает все таблицы из значений массива $_tables
        //----------------------------------------------------------------
        function ClearTables($_tables)
        {
                foreach ($_tables as $v) $this->Delete($v);
        }

        //----------------------------------------------------------------
        // Вставка массива заначений с буффером
        //----------------------------------------------------------------
    function setSIHeader($_name,$_table,$_fields = null ,$_length = 500,$_delayed = false)
    {
        $this->DI_Header[$_name] = 'insert ';
        if ($_delayed) $this->DI_Header[$_name] .= ' delayed ';
        $this->DI_Header[$_name] .= ' into '.$_table;
        if ($_fields) $this->DI_Header[$_name] .= '('.$_fields.')';
             $this->DI_Header[$_name] .= ' values';
        $this->DI_Length[$_name] = $_length;
    }

    function addSIBuffer($_name,$_items)
    {
            $_values = '';
            foreach($_items as $k=>$v)
                {
                        if ($_values != "") $_values.=",";
            if (!preg_match('/^(now|date)[^\(]\([^\)]*\)/is',$v)) $v = "'".$v."'";
                        $_values.="$v";
                }

                if (!isset($this->DI_Buffer[$_name]))
                $this->DI_Buffer[$_name] = '';
        $this->DI_Buffer[$_name] .= strlen($this->DI_Buffer[$_name])?',('.$_values.')':'('.$_values.')';

        if (strlen($this->DI_Buffer[$_name])>$this->DI_Length[$_name])
                $this->flushSIBuffer($_name);
    }

    function flushSIBuffer($_name)
    {
            if (isset($this->DI_Buffer[$_name]) && $this->DI_Buffer[$_name])
        {
                        $this->Query($this->DI_Header[$_name].$this->DI_Buffer[$_name]);
                $this->DI_Buffer[$_name] = null;
        }
    }

    function getSIBufferSize($_name)
    {
                return isset($this->DI_Buffer[$_name])?strlen($this->DI_Buffer[$_name]):null;
    }

    function InsertCVS($_table,&$_text,$_fields = null)
    {
        $_max_fields = 999;
        if ($_fields)
        {
            $_temp = explode(',',$_fields);
            $_max_fields = sizeof($_temp);
        }

        $_name = 'cvs_insert';
        $this->setSIHeader($_name,$_table,$_fields,500,true);
        $_arr = preg_split("/\"[\r\n]+/",$_text);

        for ($i=0;$i<sizeof($_arr);$i++)
        {
            if (strlen($_arr[$i]))
            {
                $_tmp = explode("\";\"",$_arr[$i]);
                $_tmp[0] = substr($_tmp[0],1,strlen($_tmp[0]));

                if (sizeof($_tmp)>1)
                {
                    $_index = sizeof($_tmp)-1;
                    if (strlen($_tmp[$_index]) == 0)
                    {
                        $_tmp[$_index] = "";
                    }
                    else
                    {
                        if ($i == sizeof($_arr) - 1 && $_tmp[$_index][strlen($_tmp[$_index])-1] == '"')
                        {
                            $_tmp[$_index] = substr($_tmp[$_index],0,strlen($_tmp[$_index])-1);
                        }
                    }
                }

                $_items = array();
                for ($j=0;$j<sizeof($_tmp);$j++)
                {
                    if ($_max_fields == $j)
                    {
                        break;
                    }
                    $_items[$j] = addslashes(stripslashes($_tmp[$j]));
                }
                $this->addSIBuffer($_name,$_items);
            }
        }
        $this->flushSIBuffer($_name);
    }

    function getCVS($_table,$_fields = '*')
        {
                $_cvs = "";
            $this->Select($_table,$_fields);
            while ($_rec = $this->getNextRec())
            {
                        $_row = "";
            $_fl = false;
                        foreach($_rec as $k=>$v)
                        {
                                if ($_fl) $_row .= ";";
                                $_row .= '"'.addslashes($v).'"';
                                $_fl = true;
                        }
            if ($_cvs) $_cvs .= "\n";
                        $_cvs .= $_row;
            }
            return $_cvs;
        }

/*
    27 июня 2004 года
    Назначение:  создает SQL запрос на создание таблицы по имени таблицы
    Применение: копирование структур таблицы из одной БД в другую
*/

    function getTableStructure($table_from, $table_to, $remove = false)
    {
        if(empty($table_from)) return false;

        $schema_create = '';

        if($remove)
        {
            $schema_create .= 'DROP TABLE IF EXISTS `' . $table_to . '`;';
            $schema_create .= "\r\n";
        }

        $schema_create .= 'CREATE TABLE `' . $table_to . '` (';

        $local_query   = 'SHOW FIELDS FROM `' . $table_from . '`';
        $result        = $this->Query($local_query);
        while ($row = $this->getNextRec($result))
        {
            $schema_create     .= '`' . $row['Field'] . '` ' . $row['Type'];
            if (isset($row['Default']) && $row['Default'] != '')
            {
                $schema_create .= ' DEFAULT \'' . addslashes($row['Default']) . '\'';
            }
            if ($row['Null'] != 'YES')
            {
                $schema_create .= ' NOT NULL';
            }
            if ($row['Extra'] != '')
            {
                $schema_create .= ' ' . $row['Extra'];
            }
            $schema_create     .= ', ';
        } // end while

        $schema_create         = ereg_replace(', $', '', $schema_create);

        $local_query = 'SHOW KEYS FROM `' . $table_from . '`';
        $result2 = $this->Query($local_query);
        if(!$result2)
        {
            Dump($table_from);
        }
        $index = array();
        while ($row = $this->getNextRec($result2))
        {
            $kname    = $row['Key_name'];
            $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
            $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

            if ($kname != 'PRIMARY' && $row['Non_unique'] == 0)
            {
                $kname = "UNIQUE|$kname";
            }
            if ($comment == 'FULLTEXT')
            {
                $kname = 'FULLTEXT|$kname';
            }
            if (!isset($index[$kname]))
            {
                $index[$kname] = array();
            }
            if ($sub_part > 1)
            {
                $index[$kname][] = '`'.$row['Column_name'] . '`(' . $sub_part . ')';
            }
            else
            {
                $index[$kname][] = '`'.$row['Column_name'].'`';
            }
        } // end while

        while (list($x, $columns) = @each($index))
        {
            $schema_create     .= ',';
            if ($x == 'PRIMARY')
            {
                $schema_create .= ' PRIMARY KEY (';
            }
            else if (substr($x, 0, 6) == 'UNIQUE')
            {
                $schema_create .= ' UNIQUE ' . substr($x, 7) . ' (';
            }
            else if (substr($x, 0, 8) == 'FULLTEXT')
            {
                $schema_create .= ' FULLTEXT ' . substr($x, 9) . ' (';
            }
            else
            {
                $schema_create .= ' KEY ' . $x . ' (';
            }
            $schema_create     .= implode($columns, ', ') . ')';
        } // end while

        $schema_create .= ');';

        return $schema_create;
    }

    function getFieldsMeta($_res = null)
    {
        if ($this->Resource === false)
        {
            Error('Mysql resource not setted','mysql');
            return false;
        }
        $_res = is_resource($_res) ? $_res : $this->Resource;

        $fields       = array();
        $num_fields   = mysql_num_fields($_res);
        for ($i = 0; $i < $num_fields; $i++)
        {
            $fields[] = mysql_fetch_field($_res, $i);
        }
        return $fields;
    }

    function getFieldsFlags($_res = null)
    {
        if ($this->Resource === false)
        {
            Error('Mysql resource not setted','mysql');
            return false;
        }
        $_res = is_resource($_res) ? $_res : $this->Resource;

        $flags       = array();
        $num_fields   = mysql_num_fields($_res);
        for ($i = 0; $i < $num_fields; $i++)
        {
            $flags[] = mysql_field_flags($_res, $i);
        }
        return $flags;
    }

    function exportTable($_table_from, $_table_to, $_type = 'normal', $_max_rows = -1)
    {
        switch($_type)
        {
            default:
                $_sql_type       = 'insert';
                $_ignore         = false;
                $_delayed        = true;
                $_showcol        = true;
                $_extended_ins   = true;
                $_hexforbinary   = false;
                $_drop_table     = true;
            break;
        }

        $_dump = array();
/*
        if($_drop_table)
        {
            $_dump[] = $this->getTableStructure($_table_from, $_table_to, true);
        }
*/

        if($_max_rows != -1)
        {
            $_all_rows = $this->getRecsCount($_table_from);
            for($_begin = 0; $_begin <= $_all_rows; $_begin += $_max_rows)
            {
                $_res = $this->Select($_table_from, '*', '1', 'limit '.$_begin.', '.$_max_rows);
                $_dump[] = $this->exportSQL($_table_to, $_res, $_sql_type, $_ignore, $_delayed, $_showcol, $_extended_ins, $_hexforbinary);
            }
        }
        else
        {
            $_res = $this->Select($_table_from);
            $_dump[] = $this->exportSQL($_table_to, $_res, $_sql_type, $_ignore, $_delayed, $_showcol, $_extended_ins, $_hexforbinary);
        }
        return $_dump;
    }

    function exportSQL($_table, $_res = null, $_sql_type = null, $_ignore = false, $_delayed = false, $_showcol = false, $_extended_ins = false, $_hexforbinary = false)
    {
        if ($this->Resource === false)
        {
            Error('Mysql resource not setted','mysql');
            return false;
        }
        $_res = is_resource($_res) ? $_res : $this->Resource;

        if(is_resource($_res))
        {
            $_fields_cnt = $this->getNumFields($_res);
            $_fields_meta = $this->getFieldsMeta($_res);
            $_field_flags = $this->getFieldsFlags($_res);

            for ($j = 0; $j < $_fields_cnt; $j++)
            {
                $_field_set[$j] = $_fields_meta[$j]->name;
            }

            switch($_sql_type)
            {
                case 'update':
                    $_schema_insert = 'UPDATE ';
                    if($_ignore)
                    {
                        $_schema_insert .= 'IGNORE ';
                    }
                    $_schema_insert .= $_table . ' SET ';
                break;
                case 'replace':
                    $_schema_insert = 'REPLACE';
                    if ($_delayed)
                    {
                        $_schema_insert .= ' DELAYED';
                    }
                    $_schema_insert .= ' INTO ' . $_table;
                    if ($_showcol)
                    {
                        $_schema_insert .= ' (' . implode(', ', $_field_set) . ')';
                    }
                    $_schema_insert .= ' VALUES (';
                break;
                default:
                    $_schema_insert = 'INSERT';
                    if ($_delayed)
                    {
                        $_schema_insert .= ' DELAYED';
                    }
                    if ($_ignore)
                    {
                        $_schema_insert .= ' IGNORE';
                    }
                    $_schema_insert .= ' INTO ' . $_table;
                    if ($_showcol)
                    {
                        $_schema_insert .= ' (' . implode(', ', $_field_set) . ')';
                    }
                    $_schema_insert .= ' VALUES (';
                break;
            }

            $_search       = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
            $_replace      = array('\0', '\n', '\r', '\Z');
            $_current_row  = 0;
            $_separator    = ($_extended_ins) ? ',' : ';';

            $_export = array();
            while ($_rec = $this->getNextRecID($_res))
            {
                $_current_row++;
                $_values = array();
                for ($j = 0; $j < $_fields_cnt; $j++)
                {
                    // NULL
                    if (!isset($_rec[$j]) || is_null($_rec[$j]))
                    {
                        $_values[]     = 'NULL';
                    // a number
                    // timestamp is numeric on some MySQL 4.1
                    }
                    elseif ($_fields_meta[$j]->numeric && $_fields_meta[$j]->type != 'timestamp')
                    {
                        $_values[] = $_rec[$j];
                    // a binary field
                    // Note: with mysqli, under MySQL 4.1.3, we get the flag
                    // "binary" for a datetime (I don't know why)
                    }
                    else if (stristr($_field_flags[$j], 'BINARY') && $_hexforbinary && $_fields_meta[$j]->type != 'datetime')
                    {
                        // empty blobs need to be different, but '0' is also empty :-(
                        if (empty($_rec[$j]) && $_rec[$j] != '0')
                        {
                            $_values[] = '\'\'';
                        }
                        else
                        {
                            $_values[] = '0x' . bin2hex($_rec[$j]);
                        }
                    // something else -> treat as a string
                    }
                    else
                    {
                        $_values[] = '\'' . str_replace($_search, $_replace, $this->sqlAddSlashes($_rec[$j])) . '\'';
                    }
                }

                // should we make update?
                if ($_sql_type && $_sql_type == 'update')
                {
                    $_insert = $_schema_insert;
                    $_parts = array();
                    for ($i = 0; $i < $_fields_cnt; $i++)
                    {
                        $_parts[] = $_field_set[$i] . ' = ' . $_values[$i];
                    }
                    $_insert .= implode(', ', $_parts);
                    $_insert .= ' WHERE ' . $this->getUvaCondition($_res, $_fields_cnt, $_fields_meta, $_field_flags, $_rec);

                }
                else
                {
                    // Extended inserts case
                    if ($_extended_ins)
                    {
                        if ($_current_row == 1)
                        {
                            $_insert  = $_schema_insert . implode(', ', $_values) . ')';
                        }
                        else
                        {
                            $_insert  = '(' . implode(', ', $_values) . ')';
                        }
                    }
                    // Other inserts case
                    else
                    {
                        $_insert      = $_schema_insert . implode(', ', $_values) . ')';
                    }
                }

                $_export[] = $_insert;
            }
            if(sizeof($_export)) return implode($_separator . CRLF, $_export);
        }
    }

    function getUvaCondition($_res, $_fields_cnt, $_fields_meta, $_fields_flags, $_rec)
    {
        $primary_key              = '';
        $unique_key               = '';
        $uva_nonprimary_condition = '';

        for ($i = 0; $i < $_fields_cnt; ++$i)
        {
            $_field_flags = $_fields_flags[$i];
            $_meta        = $_fields_meta[$i];
            // do not use an alias in a condition
            $_column_for_condition = $_meta->name;

            // to fix the bug where float fields (primary or not)
            // can't be matched because of the imprecision of
            // floating comparison, use CONCAT
            // (also, the syntax "CONCAT(field) IS NULL"
            // that we need on the next "if" will work)
            if ($meta->type == 'real')
            {
                $_condition = ' CONCAT(' . $this->backquote($_column_for_condition) . ') ';
            }
            else
            {
                // string and blob fields have to be converted using
                // the system character set (always utf8) since
                // mysql4.1 can use different charset for fields.
                if ( $this->intMySQLVersion >= 40100 && ($_meta->type == 'string' || $_meta->type == 'blob'))
                {
                    $_condition = ' CONVERT(' . $this->backquote($_column_for_condition) . ' USING utf8) ';
                }
                else
                {
                    $_condition = ' ' . $this->backquote($_column_for_condition) . ' ';
                }
            }

            if (!isset($_rec[$i]) || is_null($_rec[$i]))
            {
                $_condition .= 'IS NULL AND';
            }
            else
            {
                if ($_meta->type == 'blob'
                    // hexify only if this is a true not empty BLOB
                     && stristr($_field_flags, 'BINARY')
                     && !empty($_rec[$i]))
                {
                    // use a CAST if possible, to avoid problems
                    // if the field contains wildcard characters % or _
                    if ($this->intMySQLVersion < 40002)
                    {
                        $_condition .= 'LIKE 0x' . bin2hex($_rec[$i]). ' AND';
                    }
                    else
                    {
                        $_condition .= '= CAST(0x' . bin2hex($_rec[$i]). ' AS BINARY) AND';
                    }
                }
                else
                {
                    $_condition .= '= \'' . $this->sqlAddslashes($_rec[$i], FALSE, TRUE) . '\' AND';
                }
            }
            if ($_meta->primary_key > 0)
            {
                $_primary_key .= $_condition;
            }
            else if ($_meta->unique_key > 0)
            {
                $_unique_key  .= $_condition;
            }
            $_uva_nonprimary_condition .= $_condition;
        }

        // Correction uva 19991216: prefer primary or unique keys
        // for condition, but use conjunction of all values if no
        // primary key
        if ($_primary_key)
        {
            $_uva_condition = $_primary_key;
        }
        else if ($_unique_key)
        {
            $_uva_condition = $_unique_key;
        }
        else
        {
            $_uva_condition = $_uva_nonprimary_condition;
        }
        return preg_replace('|\s?AND$|', '', $_uva_condition);
    }
}

?>
