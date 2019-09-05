$(document).ready(function(){
    // handle 'read one' button click
    $(document).on('click', '.read-one-product-button', function(){
        
    	//go back button
		let all_products_button = `
			<div id = "all_products_button" class = "btn btn-primary float-right m-b-15px all-products-button">
				<i class="fas fa-list"></i> CANCEL
			</div>`;

        //get produt id
        let id = $(this).attr("data-id");

        //read product data from server by ajax
        //$.getJSON("http://localhost:8888/jintian/api/product/read_one.php?id=" + id, function(data){
        $.getJSON(server_address + "/api/product/read_one.php?id=" + id, function(data){

			let name = data.name;
			let category = data.category;
			let price = data.price;
			let brand = data.brand;
			let expire_date = data.expire_date;
			let stock_status = data.stock_status;
			let specification = data.specification;

        	let read_one_html = `
        		<div class = "container">
        			<div class = "row">

	        			<div class = "col-3 product-img-section d-flex align-items-center justify-content-center"></div>

	        			<div class = "col-9 product-info-section">
	        				<table class = "table table-sm table-bordered table-hover h-100-pct">

	        					<!--product id-->
	        					<tr>
	        						<td class = ".w-30-pct">Product ID</td>
	        						<td class = ".w-70-pct">${id}</td>
	        					</tr>

	        					<!--product name-->
	        					<tr>
	        						<td class = ".w-30-pct">Product Name</td>
	        						<td class = ".w-70-pct">${name}</td>
	        					</tr>

	        					<!--product category-->
	        					<tr>
	        						<td class = ".w-30-pct">Product Category</td>
	        						<td class = ".w-70-pct">${category}</td>
	        					</tr>

	        					<!--product price-->
	        					<tr>
	        						<td class = ".w-30-pct">Product Price</td>
	        						<td class = ".w-70-pct">${price}</td>
	        					</tr>

	        					<!--product price-->
	        					<tr>
	        						<td class = ".w-30-pct">Product Specification</td>
	        						<td class = ".w-70-pct">${specification}</td>
	        					</tr>

	        					<!--product brand-->
	        					<tr>
	        						<td class = ".w-30-pct">Product Brand</td>
	        						<td class = ".w-70-pct">${brand}</td>
	        					</tr>

	        					<!--product expire date-->
	        					<tr>
	        						<td class = ".w-30-pct">Expire Date</td>
	        						<td class = ".w-70-pct">${expire_date}</td>
	        					</tr>

	        					<!--product stock status-->
	        					<tr>
	        						<td class = ".w-30-pct">Stock Number</td>
	        						<td class = ".w-70-pct">${stock_status}</td>
	        					</tr>

	        				</table>
	        			</div>
        			</div>
        		</div>
        	`;

        	let whole_content_html = all_products_button + read_one_html;
        	$("#page-content").html(whole_content_html);
			loadImg(id + ".jpg");        	

        });
    });
});