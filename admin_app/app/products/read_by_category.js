$(document).ready(function(){
/*
	$(document).on("click", ".all-button", function(){
		//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");
		showProducts(server_address + "/api/product/read_paging.php");
	});
*/
	$(document).on("click", ".all-button", function(){
		//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");

		$.getJSON(server_address + "/api/product/read_paging.php", function(data){

			readProductsTemplate(data, "");
			changePageTitle("Read Products");
		});
	});

	$(document).on("click", ".category-button", function(){
		let category_name = this.innerHTML;

		//$.getJSON("http://localhost:8888/jintian/api/product/read_category.php?category=" + category_name, function(data){
		$.getJSON(server_address + "/api/product/read_paging_category.php?category=" + category_name, function(data){
			console.log(data);
			readProductsTemplate(data, "");
			changePageTitle("Read Products");

		});
	});
});