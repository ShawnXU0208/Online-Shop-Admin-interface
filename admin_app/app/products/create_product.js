$(document).ready(function(){

	//show html file when create new product button is clicked
	$(document).on('click', '#create-product', function(){

		//go back button
		let all_products_button = `
			<div id = "all_products_button" class = "btn btn-primary float-right m-b-15px all-products-button">
				<i class="fas fa-list"></i> CANCEL
			</div>`;


		/*****************************************************************************************************
							Create Form Content for Create Product (START)
		******************************************************************************************************/

		//$.getJSON("http://localhost:8888/jintian/api/category/read_all.php", function(data){
		$.getJSON(server_address + "/api/category/read_all.php", function(data){

			let categories = {};

			$.each(data.records, function(){
				categories[this.categoryID] = this.categoryName;
			});

			//category select form input
			let categories_select_html = `
				<select name = "category" class = "form-control">`;

			$.each(categories, function(id, name){

				let category_option_html = `
					<option value = ${name}>${name}</option>`;

				categories_select_html += category_option_html;
			});

			categories_select_html += "</select>";



			//Form HTML
			let create_form_html = `
				<div class = "container" style = "clear: right;">
					<div class = "row">

						<div class = "col-3 d-flex flex-column p-0 m-0">
							<div class = "product-img-section w-100-pct h-80-pct border = '1' "></div>
							<label class="btn btn-block btn-primary h-10-pct">
               					Browse <input type="file" id = "image-chooser" style= "display: none;" accept = "image/png, .jpg, image/jpeg">
            				</label>
							<input type = "button" id = "upload-button" class = "btn btn-primary h-10-pct" value = "upload" />

						</div>


						<div class = "col-9">
							<form id = "create-product-form" action = "#" method = "post" border = "1" class = "h-100-pct">
								<table class = "table table-hover table-bordered table-sm h-100-pct">
									<!--name field-->
									<tr>
										<td>Product Name</td>
										<td><input type = "text" name = "name" class = "form-control" required></td>
									</tr>

									<!--price field-->
									<tr>
										<td>Product Price</td>
										<td><input type = "number" min = "0" name = "price" class = "form-control" step="0.01" required></td>
									</tr>

									<!--specification field-->
									<tr>
										<td>Product Specification</td>
										<td><input type = "text" min = "0" name = "specification" class = "form-control" required></td>
									</tr>


									<!--brand field-->
									<tr>
										<td>Product Brand</td>
										<td><input type = "text" name = "brand" class = "form-control" required></td>
									</tr>

									<!--expire date field-->
									<tr>
										<td>Expire Date</td>
										<td><input type = "text" id = "datepicker" name = "expireDate" class = "form-control" required></td>
										<script>
											$(function(){
												$("#datepicker").datepicker();
											});
										</script>
									</tr>

									<!--stock field-->
									<tr>
										<td>Stock Number</td>
										<td><input type = "number" name = "stockStatus" class = "form-control" required></td>
									</tr>

									<!--category field-->
									<tr>
										<td>Product Category</td>
										<td>${categories_select_html}</td>
									</tr>

									<!--button to submit the form-->
									<tr>
										<td></td>
										<td>
											<button type = "submit" id = "submit-button" class = "btn btn-primary">
												<i class="fas fa-plus"></i> Create Product
											</button>
										</td>
									</tr>

								</table>
							</form>
						</div>
					</div> 
				</div>

				`;

			/*****************************************************************************************************
								Create Form Content for Create Product (END)
			******************************************************************************************************/

			let whole_content_html = all_products_button + create_form_html;

			//inject to 'page-content' of the app
			$("#page-content").html(whole_content_html);

		});

		

		var hasUploadedImage = false;

		$(document).off("click", "#submit-button");
		$(document).on("click", "#submit-button", function(event){
			if(!hasUploadedImage){
				alert("upload image first");
				event.preventDefault();
			}
		});

		//the handler for submitting form event
		$(document).off("submit", "#create-product-form");
		$(document).on("submit", "#create-product-form", function(){
			let form_data = JSON.stringify($(this).serializeFormValues());

			//send data to server via AJAX
			$.ajax({
				//url: "http://localhost:8888/jintian/api/product/create.php",
				url: server_address + "/api/product/create.php",
				type: "POST",
				contentType: "application/json",
				data: form_data,
				async: true,
				success: function(result){
					//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");
					showProducts(server_address + "/api/product/read_paging.php");
					alert("create successfully");
				},
				error: function(xhr, resp, text){
			        // show error to console
			        alert("can't create this product");	
			        console.log(xhr, resp, text);		
				}
			});

			return false;
		});

		//the handler for uploading image
		$(document).off("click", "#upload-button");
		$(document).on("click", "#upload-button", function(){
			let image = $("#image-chooser")[0].files[0];
			let formData = new FormData();
			formData.append("imageFile", image);

			$.ajax({
				//url: "http://localhost:8888/jintian/admin_app/app/image_upload/upload.php",
				url: server_address + "/admin_app/app/image_upload/upload.php",
				type: "POST",
				data: formData,
				processData: false,
			    contentType: false,
			    success: function(response) {
			        hasUploadedImage = true;
			        alert("upload success");
			           
			    },
			    error: function(xhr, resp, text){
			    	console.log(xhr, resp, text);
			    }
			});			
		});

		$(document).off("change", "#image-chooser");
		$(document).on("change", "#image-chooser", function(event){
			let src = URL.createObjectURL(event.target.files[0]);
			$(".product-img-section").html(`<img src = ${src} class = "img-fluid" /> `);
		});




	});


});