<?php
loadlib('object.storage');
class CObject_StoragePaging extends CObject_Storage
{
	private $itemsPerPage = 5;
	private $pageNum= 1;
	private $filter = array();
	private $pageCount = null;
	private $type = null;
	
	private function getPageCount()
	{
		$this->Select($this->_config->Tables[$this->type], 'COUNT(*) as count', $this->_createExp($this->type, $this->filter));
		$res = $this->GetNextRec();
		$rowNum = (int)$res['count'];
		$this->pageCount = ceil($rowNum/$this->itemsPerPage);
		return $this->pageCount;
	}
	
	public function getPaginator()
	{
		$dsPages = $this->Kernel->link('object.list');
		for ($i=1; $i<=$this->pageCount; $i++)
		{
			$page = $this->Kernel->link('object.object');
			$page->set('page', $i);
			$page->set('cur_page', $this->pageNum);
			$dsPages->add($page);
		}
		
		$dsPages->set('cur_page', $this->pageNum);
		
		$dsPaginator = $this->Kernel->link('object.object');
		$dsPaginator->set('list', $dsPages);
		
		$dsPaginator->set('cur_page', $this->pageNum);
		$dsPaginator->set('page_count', $this->pageCount);
		
		//prev num
		if ($this->pageNum > 1)
		{
			$dsPaginator->set('prev', $this->pageNum - 1);
		}
		
		//next num
		if ($this->pageNum < $this->pageCount)
		{
			$dsPaginator->set('next', $this->pageNum + 1);
		}
		
		return $dsPaginator;
	}
	
	public function findPaging($type, $pageNum = 1, $itemsPerPage = 5, $filter = array(), $order = null)
	{
		$this->filter = $filter;
		$this->type = $type;
		if (!$itemsPerPage) $itemsPerPage = 5;
		$this->itemsPerPage = $itemsPerPage;
		
		if ($pageNum > $this->getPageCount()) $pageNum = $this->pageCount;
		if ($pageNum < 1) $pageNum = 1;
		
		$this->pageNum = $pageNum;
		
		$itemList = $this->find($type, $filter, $order, $this->itemsPerPage, ($this->pageNum-1)*$this->itemsPerPage);
		$paginator = $this->getPaginator();
		$result = $this->Kernel->link('object.object');
		$result->set('list', $itemList);
		$result->set('pages', $paginator);
		return $result;
	}
}
?>