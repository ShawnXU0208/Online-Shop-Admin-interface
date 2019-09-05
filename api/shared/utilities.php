<?php
	class Utilities{
		
		// $page: current page, from url GET parameter - location: api/config/core.php
		// $totalRows: the total number of records of products, get from count() method in api/object/product.php
		// $recordsPerPage: the number of products to display per page. - location: api/config/core.php
		// $pageURL: the unchanged part of URL with page number.
		public function getPaging($page, $totalRows, $recordPerPage, $pageURL){
			//paging array
			$pagingArray = array();

			//count all produts in the database to calculate total pages
			$totalPages = ceil($totalRows / $recordPerPage);
			//ranges to links to show
			$range = 2;
			//display links to 'range of pages' around 'current page'
			$initialNum = $page - $range;
			if($initialNum < 1){
				$initialNum = 1;
			}

			$lastNum = $page + $range;
			if($lastNum > $totalPages){
				$lastNum = $totalPages;
			}


			//if display previous page
			if($page > 1){
				$previousPage = $page - 1;
				$pagingArray["previous"] = "{$pageURL}page={$previousPage}";
			}else{
				$pagingArray["previous"] = "";
			}

			//if display first page
			if($initialNum > 1){
				$firstPage = array();
				$firstPage['url'] = "{$pageURL}page=1";
				$firstPage['page'] = 1;
				$pagingArray["first"] = $firstPage;
			}else{
				$pagingArray["first"] = "";
			}

			//if display last page
			if($lastNum < $totalPages){
				$lastPage = array();
				$lastPage['url'] = "{$pageURL}page={$totalPages}";
				$lastPage['page'] = $totalPages;
				$pagingArray["last"] = $lastPage;
			}else{
				$pagingArray["last"] = "";
			}

			//if display next page
			if($page < $totalPages){
				$nextPage = $page + 1;
				$pagingArray["next"] = "{$pageURL}page={$nextPage}";
			}else{
				$pagingArray["next"] = "";
			}


			//loop through pages in range
			$pagingArray["pages"] = array();
			$pageCount = 0;
			for($x = $initialNum; $x <= $lastNum; $x++){

				$pagingArray["pages"][$pageCount]["page"] = $x;
				$pagingArray["pages"][$pageCount]["url"] = "{$pageURL}page={$x}";

				if($x == $page){
					$pagingArray["pages"][$pageCount]["current_page"] = "yes";
				}else{
					$pagingArray["pages"][$pageCount]["current_page"] = "no";
				}

				$pageCount++;

			}

			//json format
			return $pagingArray;
		}
	}
?>