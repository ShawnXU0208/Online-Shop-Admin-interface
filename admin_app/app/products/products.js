// product list html


function readProductsTemplate(products_data, keywords){


	/*****************************************************************************************************
						Create Table Content for Data Returned (Start)
	******************************************************************************************************/
	let table_head_html = `
		<!--product table start-->
		<div style = "height: 650px">
			<table class = 'table table-bordered table-hover text-nowrap'>
				<!--table heading-->
				<tr>
					<th class = 'w-5-pct'>ID</th>
					<th class = 'w-5-pct'>Category</th>
					<th class = 'w-30-pct'>Name</th>
					<th class = 'w-5-pct'>Price</th>
					<th class = 'w-20-pct'>Brand</th>
					<th class = 'w-30-pct text-align-center'>Action</th>
				</tr>
		`;


	//loop through the data returned
	let table_body_html = '';
	$.each(products_data.records, function(key, val){
		//create new table row for each record
		let table_row_html = `
			<tr>
				<td>${val.id}</td>
				<td>${val.category}</td>
				<td>${val.name}</td>
				<td>${val.price}</td>
				<td>${val.brand}</td>

				<!--Action Buttons-->
				<td>
					<!--read product button-->
					<button class = "btn btn-primary m-r-10px read-one-product-button" data-id = ${val.id}>
						<i class="fas fa-eye"></i> Read
					</button>

					<!--update product button-->
					<button class = "btn btn-secondary m-r-10px update-product-button" data-id = ${val.id}>
						<i class="fas fa-edit"></i> Edit
					</button>

					<!--delete product button-->
					<button class = "btn btn-danger m-r-10px delete-product-button" data-id = ${val.id} data-category = ${val.category} data-name = ${val.name}>
						<i class="fas fa-times"></i> Delete
					</button>

				</td>
			</tr>`;

		table_body_html += table_row_html;
	});

	let table_html = table_head_html + table_body_html + "</table></div>";

	//pagination
	let read_page_html = ``;
	if(products_data.paging){
		read_page_html += `
			<ul class = "pagination float-left margin-zero padding-bottom-2em">
		`;

		//first page
		if(products_data.paging.first != ""){
			read_page_html += `<li class = "page-item"><a class="page-link" data-page = ${products_data.paging.first.url}>first Page</a></li>`;
		}

		//loops through pages
		$.each(products_data.paging.pages, function(key, val){
			let active_page = "";
			if(val.current_page == "yes"){
				active_page = "active";
			}

			read_page_html += `<li class = "page-item ${active_page}"><a class="page-link" data-page = ${val.url}>${val.page}</a>`;
		});

		if(products_data.paging.last != ""){
			read_page_html += `<li class = "page-item"><a class="page-link" data-page = ${products_data.paging.last.url}>Last Page</a></li>`;
		}

		read_page_html += "</ul>";
	}

	/*****************************************************************************************************
						Create Table Content for Data Returned (End)
	******************************************************************************************************/

	//inject to 'page-content' of the app
	$("#products-display").html(table_html + read_page_html);


}