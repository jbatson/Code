<?php
/*
 * Class Pagination 
 * @version 1.0
 * @date 07/06/10
 * @compatibility: PHP 4, PHP 5
 */
 
class Pagination
{
	
	/**
	 * @public $pageLinks
	 */	
	 
	public $pageLinks;
	
	/*
	 * Pagination using GET
	 */
	
	function ShowPagination($pageQuery, $pageName, $start, $show, $additional = '', $pageNumber = '', $class = '')
	{
		$start = $start;
		
		$pageQuery = mysql_query($pageQuery);
		$pageRows = @mysql_num_rows($pageQuery);						
		$pageTotal = ceil($pageRows / $show);
		$pageLinks = '';

		if($pageTotal > 1)
		{
			$pageLinks = '<div class="'.$class.'">';
			
			if($pageNumber > 1)
			{
				$pageBottom = ($pageNumber - 5);
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 5);	
			}
			else
			{
				$pageBottom = 1;
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 10);
			}		

			$pageCheck = 0;
			$pageLinks .= '<a href="'.$pageName.'?start=0&end='.($show).'&current_page=1'.$additional.'" style="color: #333; text-decoration: none;"><strong> << </strong></a> ';
			
			for($i = 1; $i <= $pageTotal; $i++)
			{
				if($i >= $pageBottom && $i < $pageSelected)
				{
					if($pageCheck == 0)
					{
						if($start == 0)
						{
							$pageLinks .= '<a href="'.$pageName.'?start=0&end='.($show).'&current_page='.$i.$additional.'" style="color: #333; text-decoration: none;"><strong>'.$i.'</strong></a> ';
						}
						else
						{
							$pageLinks .= '<a href="'.$pageName.'?start='.($show * ($i - 1)).'&end='.($show).'&current_page='.$i.$additional.'" style="color: #333; text-decoration: none;"><strong>'.$i.'</strong></a> ';
						}	
						
						$pageCheck = 1;
					}
					else
					{
						$pageLinks .= '<span style="color: #333;">|</span> <a href="'.$pageName.'?start='.($show * ($i - 1)).'&end='.($show).'&current_page='.$i.$additional.'" style="color: #333; text-decoration: none;"><strong>'.$i.'</strong></a> ';
					}	
				}
				
				if($i == $pageSelected)
				{
					if($pageSelected == 1)
					{
						$pageLinks .= '<a href="'.$pageName.'?start='.($show * ($i - 1)).'&end='.($show).'&current_page='.$i.$additional.'" style="color: red;"><strong>'.$i.'</strong></a> ';
					}
					else
					{
						$pageLinks .= '<span style="color: #333;">|</span> <a href="'.$pageName.'?start='.($show * ($i - 1)).'&end='.($show).'&current_page='.$i.$additional.'" style="color: red;"><strong>'.$i.'</strong></a> ';	
					}		
				}

				if(($i > $pageSelected) && ($i <= $pageTop))
				{
					$pageLinks .= '<span style="color: #333;">|</span> <a href="'.$pageName.'?start='.($show * ($i - 1)).'&end='.($show).'&current_page='.$i.$additional.'" style="color: #333; text-decoration: none;"><strong>'.$i.'</strong></a> ';	
				}
			}

			$end = (($pageTotal * $show) - $show);

			$pageLinks .= ' <a href="'.$pageName.'?start='.$end.'&end='.($show).'&current_page='.$pageTotal.''.$additional.'" style="color: #333; text-decoration: none;"><strong> >> </strong></a>';
			$pageLinks .= '</div>';
		}
		
		return $pageLinks;
	}

	/*
	 * Pagination for SEO links using Rewrite class
	 */

	function SeoPagination($pageQuery, $pageName, $start, $show, $additional = '', $pageNumber = '')
	{
		$start = $start;
		
		$pageQuery = mysql_query($pageQuery);
		$pageRows = @mysql_num_rows($pageQuery);				
		$pageTotal = ceil($pageRows / $show);
		$pageLinks = '';
		
		if($pageTotal > 1)
		{
			if($pageNumber > 1)
			{
				$pageBottom = ($pageNumber - 5);
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 5);	
			}
			else
			{
				$pageBottom = 1;
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 10);
			}		

			$pageCheck = 0;
			
			if(!empty($additional))
			{								
				$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/0/'.($show).'/1/'.$additional.'" class="seo-nonselected seo-double seo-first"> <<</a> ';
			}
			else 
			{
				$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/0/'.($show).'/1" class="seo-nonselected seo-double seo-first"> <<</a> ';
			}			
			
			for($i = 1; $i <= $pageTotal; $i++)
			{
				if($i > 9)
				{
					$seoDouble = ' seo-double';
				}
				else
				{
					$seoDouble = '';	
				}				
				
				if($i >= $pageBottom && $i < $pageSelected)
				{
					if($pageCheck == 0)
					{
						if($start == 0)
						{
							if(!empty($additional))
							{						
								$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-nonselected">'.$i.'</a> ';
							}
							else 
							{
								$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-nonselected">'.$i.'</a> ';
							}							
						}
						else
						{
							if(!empty($additional))
							{						
								$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-nonselected">'.$i.'</a> ';
							}
							else 
							{
								$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-nonselected">'.$i.'</a> ';
							}							
						}	
						
						$pageCheck = 1;
					}
					else
					{
						if(!empty($additional))
						{						
							$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-nonselected seo-first">'.$i.'</a> ';
						}
						else 
						{
							$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-nonselected seo-first">'.$i.'</a> ';
						}
					}	
				}
				
				if($i == $pageSelected)
				{
					if($pageSelected == 1)
					{
						if(!empty($additional))
						{
							$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-selected seo-first'.$seoDouble.'">'.$i.'</a> ';
						}
						else 
						{
							$pageLinks .= '<a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-selected seo-first'.$seoDouble.'">'.$i.'</a> ';
						}
					}
					else
					{
						if(!empty($additional))
						{													
							$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-selected'.$seoDouble.'">'.$i.'</a> ';
						}
						else 
						{
							$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-selected'.$seoDouble.'">'.$i.'</a> ';
						}							
					}		
				}

				if(($i > $pageSelected) && ($i <= $pageTop))
				{
					if(!empty($additional))
					{								
						$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'/'.$additional.'" class="seo-nonselected'.$seoDouble.'">'.$i.'</a> ';
					}
					else 
					{
						$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.($show * ($i - 1)).'/'.($show).'/'.$i.'" class="seo-nonselected'.$seoDouble.'">'.$i.'</a> ';
					}						
				}
			}

			$end = (($pageTotal * $show) - $show);

			if(!empty($additional))
			{								
				$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.$end.'/'.($show).'/'.($i - 1).'/'.$additional.'" class="seo-nonselected seo-double"> >> </a> ';
			}
			else 
			{
				$pageLinks .= ' <a href="'.BASEURL.'/'.$pageName.'/'.$end.'/'.($show).'/'.($i - 1).'" class="seo-nonselected seo-double"> >> </a> ';
			}			
		}

		return $pageLinks;
	}

	/*
	 * Pagination for ajax calls
	 */

	public function AjaxPagination($pageQuery, $pageName, $start, $show, $additional = '', $pageNumber = '', $entity_id = '', $type_entity_id = '', $type = '', $title = '', $div_id = '')
	{
		$start = $start;
		
		$pageQuery = mysql_query($pageQuery);
		$pageRows = @mysql_num_rows($pageQuery);				
		$pageTotal = ceil($pageRows / $show);
		$pageLinks = '';
		
		if($pageTotal > 1)
		{
			if($pageNumber > 1)
			{
				$pageBottom = ($pageNumber - 5);
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 5);	
			}
			else
			{
				$pageBottom = 1;
				$pageSelected = $pageNumber;
				$pageTop = ($pageNumber + 10);
			}		

			if($i > 9)
			{
				$seoDouble = ' seo-double';
			}
			else
			{
				$seoDouble = '';	
			}

			$pageCheck = 0;
			$pageLinks .= '<a href="#" class="seo-nonselected seo-first'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start=0&show='.$show.'&page='.$pageName.'&page_number=1\', \'#'.$div_id.'\'); return false;"> <<</a> ';
			
			for($i = 1; $i <= $pageTotal; $i++)
			{
				if($i >= $pageBottom && $i < $pageSelected)
				{
					if($pageCheck == 0)
					{
						if($start == 0)
						{
							$pageLinks .= ' <a href="#" class="seo-nonselected'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';
						}
						else
						{
							$pageLinks .= ' <a href="#" class="seo-nonselected'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';
						}	
						
						$pageCheck = 1;
					}
					else
					{
						$pageLinks .= ' <a href="#" class="seo-nonselected'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';
					}	
				}
				
				if($i == $pageSelected)
				{
					if($pageSelected == 1)
					{
						$pageLinks .= ' <a href="#" class="seo-selected'.$seoDouble.'"  onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';
					}
					else
					{
						$pageLinks .= ' <a href="#" class="seo-selected'.$seoDouble.'"  onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';	
					}		
				}

				if(($i > $pageSelected) && ($i <= $pageTop))
				{
					$pageLinks .= ' <a href="#" class="seo-nonselected'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.($show * ($i - 1)).'&show='.$show.'&page='.$pageName.'&page_number='.$i.'\', \'#'.$div_id.'\'); return false;">'.$i.'</a> ';	
				}
			}

			$end = (($pageTotal * $show) - $show);

			$pageLinks .= ' <a href="#" class="seo-nonselected'.$seoDouble.'" onclick="javascript: ajaxRequest(\''.BASEURL.'/ajax/'.$pageName.'\', \'type_entity_id='.$type_entity_id.'&type='.$type.'&title='.urlencode($title).'&start='.$end.'&show='.$show.'&page='.$pageName.'&page_number='.($i - 1).'\', \'#'.$div_id.'\'); return false;"> >> </a> ';
		}		
		
		return $pageLinks;		 
	}
}
?>