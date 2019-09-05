$(document).ready(function(){
	// when a 'search products' button was clicked
	$(document).on("submit", "#search-product-form", function(){

		//get keywords
		let keywords = $(this).find(":input[name='keywords']").val();

		// get data from the api based on search keywords
		//$.getJSON("http://localhost:8888/jintian/api/product/search.php?s=" + keywords, function(data){
		$.getJSON(server_address + "/api/product/search.php?s=" + keywords, function(data){
			console.log(data);
	        // template in products.js
            readProductsTemplate(data, keywords);
 
            // chage page title
            changePageTitle("Search products: " + keywords);		
		});

        // prevent whole page reload
        return false;
	});
});