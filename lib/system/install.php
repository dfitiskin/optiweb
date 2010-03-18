<?php

class CSystem_Install
{
    public function process()
    {
        if (isset($_POST['object'], $_POST['action'], $_POST['mode']) && $_POST['object'] == $this->Name)
        {
            $method = sprintf('%s%s', $_POST['action'], $_POST['mode']);
            
            if (method_exists($this, $method))
            {
                $this->$method($_POST, $_FILES);
            }
        }
    }
    
    public function saveConfig($params)
    {
        if (isset($params['db'], $params['db']['host'], $params['db']['dbname'], $params['db']['user'], $params['db']['pass']))
        {
            $this->Kernel->saveConfig('system', 'database', $params['db'], 'eval');
            header(sprintf('Location: %s', $this->Kernel->Url));
            die();
        }
    }
    
    public function restoreDatabase($params)
    {
        $db = $this->Kernel->link('database.manager');
        $filemanager = $this->Kernel->link('services.filemanager');
        
        $files = $filemanager->getFilesList($params['backup']['file']);
        
        foreach ($files as $file)
        {
            $dump = $this->Kernel->readFile($file);
            $lines = explode(';', $dump);
            
            foreach ($lines as $sql)
            {
                $sql = trim($sql);
                if (!empty($sql))
                {
                    $db->query($sql);  
                }
            }

        }
    }
        
    public function backupDatabase()
    {
        $db = $this->Kernel->link('database.manager');
        $filemanager = $this->Kernel->link('services.filemanager');
        $res = $db->query('SHOW TABLES');
        
        $now = date('Y-m-d-H-i-s');        
        $rootPath = sprintf('%s%s/%s/%s/', GEN_DATA_PATH, $this->Name, 'database', $now);
        
        $tables = array();
        while ($rec = $db->getNextRec($res, false))
        {
            $tableName = $rec[0];
            
            $db->query(sprintf('SHOW CREATE TABLE `%s`', $tableName));
            $rec = $db->getNextRec(null, false);
            $data = array(
                sprintf('DROP TABLE IF EXISTS `%s`', $tableName),
                $rec[1],
            );
            
            $db->select($tableName);
            while ($row = $db->getNextRec())
            {
                $data[] = sprintf(
                    'INSERT INTO %s(`%s`) VALUES ("%s")',
                    $tableName,
                    implode('`, `', array_keys($row)),
                    implode('", "', array_values($row))
                );
            }
            
            $filemanager->writeFile(
                sprintf('%s%s.sql', $rootPath, $tableName),
                implode(";\n", $data)
            );
        }
        header(sprintf('Location: %s', $this->Kernel->Url));
        die();
    }

    public function execute()
    {
        $db = $this->Kernel->LoadConfig('system','database');
    
        if (isset($_POST) && $_POST)
        {
            $this->process();
        }
        
        $ds = &$this->Kernel->Link('dataset.abstract');
        //$ds->addParams($db, 'db.');
        $ds->addParam('db', $db);
        
        $rootPath = sprintf('%s%s/%s/', GEN_DATA_PATH, $this->Name, 'database');
        $filemanager = $this->Kernel->link('services.filemanager');
        $dbVersions = $filemanager->getFilesList($rootPath, 'folder');
        
        $dbVersionsDS = &$this->Kernel->Link('dataset.array');
        foreach ($dbVersions as $i => $ver)
        {
            $parts = explode('/', trim($ver, '/'));
            $subParts = explode('-', array_pop($parts));
            
            $item = array(
                'value'     => $ver,
                'name'      => sprintf(
                    '%s-%s-%s %s:%s:%s',
                    $subParts[0],
                    $subParts[1],
                    $subParts[2],
                    $subParts[3],
                    $subParts[4],
                    $subParts[5]
                ),
            );
            $dbVersionsDS->addData($item);
        }
        $ds->addChildDs('dbVersions', $dbVersionsDS);
        
        $templ = array(
            'file'  => 'page.tpl',
        );
    
        $view = &$this->Kernel->Link('template.manager');
        $result = $view->execute($ds, $templ, $this->Name);
        echo $result;
    }
}
