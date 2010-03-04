<?php

loadlib('database.manager');

class CObject_Storage extends CDataBase_Manager
{
    private $loaded = array();
    public $_modified = array();
    public $debug = false;
    public $_config = null;

    function setLoaded($obj)
    {
        $id = $obj->get('id');
        $type = $obj->getType();

        if ($id && $type && !isset($this->loaded[$type][$id]))
        {
            $this->loaded[$type][$id] = $obj;
            $this->notify('load', $obj);
        }
    }

    function getLoaded($type, $id)
    {
		$result = null;
        if (isset($this->loaded[$type][$id]))
        {
			$result = $this->loaded[$type][$id];
        }
        return $result;
    }

    function init()
    {
        parent::init();
        if ($this->Kernel->libExists($this->Name.'.config'))
        {
            $this->_config = $this->Kernel->link($this->Name.'.config');
        }
    }

    function getConfig($moduleName)
    {
        $this->_config = $this->Kernel->link($moduleName.'.config');
    }
    
    function create($type)
    {
        $typeImpl = 'object.storageobject';
        if (isset($this->_config->Impl[$type]))
        {
            $typeImpl = $this->_config->Impl[$type];
        }
        $obj = $this->Kernel->link('Object.StorageObject');
        if ($this->Kernel->libExists($typeImpl))
        {
            $obj = $this->Kernel->link($typeImpl);
        }
        $obj->setType($type);
        $obj->setStorage($this);
        $this->notify('create', $obj);
        return $obj;
    }

    function load($type, $id)
    {
        $obj = null;

        $obj = $this->getLoaded($type, $id);

        if (!$obj)
        {
            $exp = array('id' => $id);
            $this->select(
                $this->_getTable($type),
                '*',
                $this->_createExp($type, $exp),
                null,
                $this->debug
            );
            $rec = $this->getNextRec();

            if ($rec)
            {
                $obj = $this->create($type);
                $obj->setup($rec);
                $this->setLoaded($obj);
            }
        }
        return $obj;

    }

    private function getFields($type)
    {
        $result = array();
        if (isset($this->_config->Fields[$type]))
        {
            $result = $this->_config->Fields[$type];
        }
        return $result;
    }

    private function createRecordFromObject($obj)
    {
        $fields = $this->getFields($obj->getType());
        $rec = array();
        foreach ($fields as $recField => $objParam)
        {
            $rec[$recField] = mysql_escape_string($obj->get($objParam));
        }
        return $rec;        
    }

    function save($obj)
    {
		$obj = $this->notify('save', $obj);
        $id = $obj->get('id');
        $rec = $this->createRecordFromObject($obj);
        if ($id)
        {
            $this->updateValues(
                $this->_getTable($obj->getType()),
                $rec,
                'id="'.$id.'"',
				$this->debug
            );
        }
        else
        {
            $this->insertValues(
                $this->_getTable($obj->getType()),
                $rec,
				$this->debug
            );

            $id = $this->getLastId();
            $obj->set('id', $id);
        }
    }

    
	function findCount($type, $exp = null)
    {
    	if (is_array($exp))
    	{
    		$_exp = $this->_createExp($type, $exp);
    	} 
    	else 
    	{
    		$_exp = $exp;
    	}
    	$info = $this->getNextRec(
    		$this->select(
	            $this->_getTable($type),
	            'count(*) as count',
	            $_exp,
	            null,
	            $this->debug
			)
        );
        return $info['count'];
    }
    
    function find($type, $exp = null, $orderBy = null, $limit = null, $from = 0)
    {
    	$result = $this->findExt(
            $type,
            $exp,
            $this->_createOrderBy($orderBy) . ' ' . $this->_createLimit($limit, $from)
        );
        return $result;
    }

    function findExt($type, $exp = null, $ext = null)
    {
    	if (is_array($exp))
    	{
    		$_exp = $this->_createExp($type, $exp);
    	} 
    	else 
    	{
    		$filter = $this->_createExp($type, $this->_getFilter($type));
    		$_exp = $exp . ($filter ? ' AND '.$filter : null);
    	}
        $res = $this->select(
            $this->_getTable($type),
            '*',
            $_exp,
            $ext,
            $this->debug
        );
        $result = $this->Kernel->link('object.storageresult');
        $result->setStorage($this);
        $result->setResult($type, $res);
        return $result;
    }
    
	function _createExp($type, $exp)
    {
    	$exp = is_array($exp) ? $exp : array();

        $filter = $this->_getFilter($type);
        
        $exp = array_merge($filter, $exp);
        $result = array();
        foreach ($exp as $field => $value)
        {
            if (is_array($value))
            {
            	$texp = '';
            	foreach($value as $key=>$val)
            	{
            		if (!is_numeric($key))
            		{
            			$result[] = ''.$field.' '.$key.' "'.mysql_escape_string($val).'"';
            		} 
            	}
            	if (is_numeric($key))
            	{
            		$result[] = ''.$field.' '.$value[0].' "'.mysql_escape_string($value[1]).'"';
            	}
            }
            else
            {
                $result[] = ''.$field.'="'.mysql_escape_string($value).'"';
            }
        }
        return implode(' AND ', $result);
    }
    
    
    function _getTableAlias($type)
    {
        $result = array();
        if (is_array($type))
        {
            $result = $this->_getTable(key($type)) . ' AS `'.$type[key($type)].'`';    
        }
        else
        {
            $result = $this->_getTable($type);    
        }
        return $result;
    }
    
    function findMulti($typeItem, $multiType, $fields = "*", $exp =null, $orderBy = null, $limit = null, $from = 0)
    {
        $join = '';
        foreach ($multiType as $i => $joinData)
        {
            $joinType = isset($joinData[2]) ? $joinData[2] . ' ' : 'INNER ';
            $joinOn = isset($joinData[1]) ? ' ON ' . $joinData[1] : '';  
            $join .= ' '.$joinType . 'JOIN ' . $this->_getTableAlias($joinData[0]) . $joinOn;
        }
        $type = is_array($typeItem) ? key($typeItem) : $typeItem;
        $res = $this->select(
	        $this->_getTableAlias($typeItem).$join,
	        $fields,
	        is_array($exp) ? $this->_createExp($type, $exp) : $exp,
	        $this->_createOrderBy($orderBy) . ' ' . $this->_createLimit($limit, $from),
	        $this->debug
        );
        $result = $this->Kernel->link('object.storageresult');
        $result->setStorage($this);
        $result->setResult($type, $res);
        return $result;
    }
    
    
    function multiFind($table=null, $fields = "*", $exp=null, $orderBy = null)
    {
        if (is_array($table))
        {
            $tfname = $this->_getAlias($table[0]);
            $tname = $table[0][0];
            $ijoin = '';
            for ($i=1;$i<count($table);$i++)
            {
                $ijoin .= " INNER JOIN  ".$this->_getAlias($table[$i]);
            }
        } else {
            $tfname = $this->_getAlias($table);
            $tname = $table[0];
            $ijoin = "";
        }
        
        $res = $this->select(
            $tfname . ' ' . $ijoin,
            $fields,
            $this->_createExp($tname, $exp),
            $this->_createOrderBy($orderBy),
            $this->debug
        );   
        $result = $this->Kernel->link('object.storageresult');
        $result->setStorage($this);
        $result->setResult($tname, $res);  
        return $result;   
    }

    function findOne($type, $exp = null, $orderBy = null)
    {
        $res = $this->select(
            $this->_getTable($type),
            '*',
            $this->_createExp($type, $exp),
            $this->_createOrderBy($orderBy),
            $this->debug
        );

        $rec = $this->getNextRec();

		$obj = null;
        if ($rec)
        {
            $obj = $this->getLoaded($type, $rec['id']);

            if (!$obj)
            {
                $obj = $this->create($type);
                $obj->setup($rec);
                $this->setLoaded($obj);
            }
        }
        return $obj;
    }
    
    
    function findOneMulti($typeItem, $multiType, $fields = "*", $exp =null, $orderBy = null, $limit = null)
    {
        $from = 0;
        $join = '';
        foreach ($multiType as $i => $joinData)
        {
            $joinType = isset($joinData[2]) ? $joinData[2] . ' ' : 'INNER ';
            $joinOn = isset($joinData[1]) ? ' ON ' . $joinData[1] : '';  
            $join .= ' '.$joinType . 'JOIN ' . $this->_getTableAlias($joinData[0]) . $joinOn;
        }
        $type = is_array($typeItem) ? key($typeItem) : $typeItem;
        $res = $this->select(
	        $this->_getTableAlias($typeItem).$join,
	        $fields,
	        is_array($exp) ? $this->_createExp($type, $exp) : $exp,
	        $this->_createOrderBy($orderBy) . ' ' . $this->_createLimit($limit, $from),
	        $this->debug
        );
        
        $rec = $this->getNextRec($res);
		$obj = null;
        if ($rec)
        {
            $obj = $this->getLoaded($type, $rec['id']);
            if (!$obj)
            {
                $obj = $this->create($type);
                $obj->setup($rec);
                $this->setLoaded($obj);
            }
        }
        return $obj;
    }
    
    

    function _getAlias($type)
    {
        if (is_array($type))
        {
            $tname = $this->_getTable($type[0]);
            $alias = $tname . ' AS ' . $type[1];
        } else {
            $alias = $this->_getTable($type);
        }
        return $alias;  
    }

    function _getTable($type)
    {   
		return $this->_config->Tables[$type];
    }

    function _getFilter($type)
    {
    	return $this->_config->Filters[$type];
    }

    

    function _createOrderBy($exp)
    {
        $result = $exp ? 'ORDER BY ' . $exp : null;
        return $result;
    }

    function _createLimit($count, $from = 0)
    {
        $result = $count ? 'LIMIT ' . $from . ', ' . $count : null;
        return $result;
    }


    function removeById($type, $id)
    {
        $ids = is_array($id) ? $id : array($id);

        $this->deleteValues(
            $this->_getTable($type),
            'id',
            $ids
        );

        foreach ($ids as $i => $id)
        {
            unset($this->_loaded[$type][$id]);
        }
        
    }

    function remove(& $obj)
    {
		$this->notify('remove', $obj);
        $this->removeById(
            $obj->getType(),
            $obj->getId()
        );
        $id = null;
        $obj->set('id', $id);
    }

    function removeAll($type, $exp = null)
    {
        $items = $this->find($type, $exp);

        while ($item = $items->nextItem())
        {
            $item->remove();
        }
    }    

    function notify($event, $item)
    {
        $type = $item->getType();
        $method = 'on' . $event;

        if (method_exists($item, $method))
        {
            $item->$method();
        }

        if (isset($this->_config->Events[$type][$event]))
        {
            $observerInfo = $this->_config->Events[$type][$event];

            $observer = $this->Kernel->link($observerInfo[0]);
            $method = isset($observerInfo[1]) ? $observerInfo[1] : 'on' . $type . $event;

            if ($observer && method_exists($observer, $method))
            {
                $observer->$method($item);
            }
        }
        return $item;
    }
}
