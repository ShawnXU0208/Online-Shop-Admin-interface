$(document).ready(function(){
	//app html
	let appHTML = `
		<div class = "container">
			<br>
			<div class = "page-header">
				<h1 id = "page-title">Read Products</h1>
			</div>

			<br>
			<hr>
			<br>

			<!-- display content -->
			<div id = "page-content"></div>
		</div>
	`;

	//inject to 'app' in index.html
	$("#app").html(appHTML);
});


//change page title
function changePageTitle(page_title){
	//change page title
	$("#page-title").text(page_title);
}

//function to make the form values to JSON
$.fn.serializeFormValues = function(){
	let jsonObject = {};

	/* the format of formValArray: 
	   [
	        {name: "name", value: "data input"},
	        {name: "price", value: "data input"},
	        {name: "brand", value: "data input"},
	        {name: "expireDate", value: "data input"},
	        {name: "stockStatus", value: "data input"},
			{name: "category", value: "data input"}
	   ]	
	*/
	let formValArray = this.serializeArray();

	$.each(formValArray, function(){
		jsonObject[this.name] = this.value;
	});

	return jsonObject;
	
}

//funtion to load the image from server
function loadImg(filename){
	$.ajax({
		//url: "http://localhost:8888/jintian/api/product/read_image.php?LoadImg=" + filename,
		url: server_address + "/api/product/read_image.php?LoadImg=" + filename,
		type: "GET",
		async: true,
		success: function(data){
			let src = "data:image/jpg;base64," + data;

			$(".product-img-section").html(`<img class = "w-100-pct" src = ${src} />`);
		}
	});
}




























