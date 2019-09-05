$(document).ready(function(){

	//show list of products on first load
	showProductsFirstPage();

	// when a 'read products' button was clicked
	$(document).on('click', '.all-products-button', function(){
		showProductsFirstPage();
	});

	// when a page button is clicked
	$(document).on("click", ".pagination li", function(){
		//get json url
		let json_url = $(this).find('a').attr("data-page");

        // show list of products
        showProducts(json_url);
	});
});


function renderHeader(keywords){

	//clear the page
	let page_html = `
		<div id = "search-bar"></div>
		<div id = "nav-bar"></div>
		<div id = "products-display"></div>
	`;

	$("#page-content").html(page_html);

	//$.getJSON("http://localhost:8888/jintian/api/category/read_all.php", function(data){
	$.getJSON(server_address + "/api/category/read_all.php", function(data){
		//html for creating the navbar
		let create_navbar_html = `
			<ul class = "nav nav-pills float-left" role = "tablist">
				<li class = "nav-item"> 
					<button type = "button" class = "nav-link active btn btn-link all-button" data-toggle="pill">All</button>
				</li>
		`;

		//loop through the data returned
		$.each(data.records, function(key, val){
			//category button html
			let category_html = `
				<li class = "nav-item">  
					<button type = "button" class = "nav-link btn btn-link category-button" data-toggle="pill">${val.categoryName}</button>
				</li>
			`;

			create_navbar_html += category_html;
		});

		create_navbar_html += `				
			</ul>
		`;


		//html for creating search form
		let search_products_html = `
			<!-- search product form -->
			<form id = "search-product-form" action = "#" method = "post">
				<div class = "input-group w-50-pct m-b-20px">
					<input type = "text" name = "keywords" class = "form-control product-search-keywords" value='` + keywords + `' placeholder = "search..." />

					<span class = "input-group-btn">
						<button type = "submit" class = "btn btn-default" type = "button">
							<i class="fas fa-search"></i>
						</button>
					</span>
				</div>
			</form>

		`;

		//html for creating new product button
		let create_prodcut_button_html = `
			<!-- when clicked, it will load the create product form-->
			<div class = "float-right m-b-15px">
				<div id = "create-product" class = "btn btn-info create-product-button" style = "margin-right: 10px; width: 150px;">
					<i class="fas fa-plus"></i> Add one
				</div>

				<!-- when clicked, it will load the create product form-->
				<div id = "create-multiple" class = "btn btn-info create-product-button" style = "width: 150px;">
					<i class="fas fa-plus"></i> Add multiple
				</div>
			</div>
		`;


		$("#search-bar").html(search_products_html);

		//inject to 'page-content' of the app
		$("#nav-bar").html(create_navbar_html + create_prodcut_button_html);
	});	
}

function showProductsFirstPage(){
	//let json_url = "http://localhost:8888/jintian/api/product/read_paging.php";
	renderHeader("");
	let json_url = server_address + "/api/product/read_paging.php";
	showProducts(json_url);
}

//function to show list of products
function showProducts(json_url){
	// get list of products from the API
	$.getJSON(json_url, function(data){
        // html for listing products
        readProductsTemplate(data, "");
 
        // chage page title
        changePageTitle("Read Products");
	});
}
