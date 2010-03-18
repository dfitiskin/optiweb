<?php
class CPaging
{
	private $countElements = 0;
	private $elementsPerPage = 10;
	private $currentPageNumber = 1;
	protected $countShowingPages = null;
	private $countPages = 0;
	
	public function __construct($count = 0, $elementsPerPage = 10, $currentPageNumber = 1, $countShowingPages = null)
	{
		$this->countElements = (int)$count;
		$this->elementsPerPage = (int)$elementsPerPage;
		if (!$this->elementsPerPage) $this->elementsPerPage = 10;
		
		$this->countPages = (int)ceil($this->countElements / $this->elementsPerPage);
		$currentPageNumber = (int)$currentPageNumber;
		if ($currentPageNumber > $this->countPages) $currentPageNumber = $this->countPages;
		
		if ($currentPageNumber < 1) $currentPageNumber = 1;
		$this->currentPageNumber = (int)$currentPageNumber;
		
		$countShowingPages = (int)$countShowingPages;
		if ($countShowingPages)
		{
			if ($countShowingPages === 1) $countShowingPages++;
			if ($countShowingPages % 2 == 0) $countShowingPages++;
		}
		$this->countShowingPages = $countShowingPages;
	}
	
	/**
	 *
	 * @param int $from
	 * @param int $to
	 * @return array
	 */
	public function getPageList($from = 1, $to = null)
	{
		$pages = array();
		for ($i = $from; $i <= $to; $i++)
		{
			$page = array();
			$page['number'] = $i;
			$page['isCurrent'] = ($i == $this->currentPageNumber);
			$pages[] = $page;
		}
		return array('pages' => $pages);
	}
	
	/**
	 *
	 * @return array
	 */
	public function getAdvancedPaging()
	{
		$half = floor($this->countShowingPages/2);
		$from = (int)($this->currentPageNumber - $half);
		
		if ($from < 1)
		{
			$from = 1;
		}

		$to = $from + ($this->countShowingPages - 1);
		
		if ($to > $this->countPages)
		{
			$diff = $to - $this->countPages;
			$to = $this->countPages;
			$from = $from - $diff;
			if ($from < 1)
			{
				$from = 1;
			}
		}
		$result = array();
		$result = $this->getPageList($from, $to);
		$result['isFirst'] = ($from === 1);
		$result['isLast'] = ($to === $this->countPages);
		
		$result['first'] = ($this->currentPageNumber == 1) ? null : 1;
		$result['prev'] = ($this->currentPageNumber == 1) ? null: $this->currentPageNumber - 1;

		$result['next'] = ($this->currentPageNumber == $this->countPages) ? null : $this->currentPageNumber + 1;
		$result['last'] = ($this->currentPageNumber == $this->countPages) ? null : $this->countPages;
		
		$result['length'] = count($result['pages']);
		
		$result['pagesCount'] = $this->countPages;
		return $result;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getPaging()
	{
		$result = array();
		if (!$this->countShowingPages)
		{
			$result = $this->getPageList(1, $this->countPages);
		}
		else
		{
			$result = $this->getAdvancedPaging();
		}
		
		$result['currentPageNumber'] = $this->currentPageNumber;
		return $result;
	}
}

loadlib('object.object');
loadlib('object.list');
class CPaging_Paging extends CPaging
{
	public function setParams()
	{
		$args = func_get_args();
		call_user_func_array(array($this, '__construct'), $args);
	}
	
	public function getPaging()
	{
		$data = parent::getPaging();
		$result = $this->Kernel->link('object.object');
		$result->setup($data);
		
		$pages = $this->Kernel->link('object.list');
		if ($data['length'] > 1)
		{
			foreach ($data['pages'] as $i => $pageData)
			{
				$page = $this->Kernel->link('object.object');
				$page->setup($pageData);
				$pages->add($page);
			}
		}
		$result->set('pages', $pages);
		return $result;
	}
}
?>