$(document).ready(function(){
	var id;
	// show html form when "update product" button is clicked
	$(document).on("click", ".update-product-button", function(){
		id = $(this).attr("data-id");

		//go back button
		let all_products_button = `
			<div id = "all_products_button" class = "btn btn-primary float-right m-b-15px all-products-button">
				<i class="fas fa-list"></i> CANCEL
			</div>`;



		/*****************************************************************************************************
							Create Form Content for Update Product (START)
		******************************************************************************************************/

		//get information of the product based on the id
		//$.getJSON("http://localhost:8888/jintian/api/product/read_one.php?id=" + id, function(data){
		$.getJSON(server_address + "/api/product/read_one.php?id=" + id, function(data){
			//values to be used to fill out the form
			let category = data.category;
			let name = data.name;
			let price = data.price;
			let brand = data.brand;
			let expire_date = data.expire_date;
			let stock_status = data.stock_status;
			let specification = data.specification;

			let update_form_html = `

				<div class = "container">
					<div class = "row">
						<div class = "col-3 d-flex flex-column p-0 m-0">
							<div class = "product-img-section w-100-pct h-80-pct border = '1' "></div>
							<label class="btn btn-block btn-primary h-10-pct">
               					Browse <input type="file" id = "image-chooser" style= "display: none;" accept = "image/png, .jpg, image/jpeg">
            				</label>
							<input type = "button" id = "upload-button" class = "btn btn-primary h-10-pct" value = "Modify Image" />
						</div>

						<div class = "col-9">
							<form id = 'update-product-form' action = '#' method = "post" border = "0" class = "h-100-pct">
								<table class = "table table-hover table-bordered table-sm h-100-pct">

									<!--Category field-->
									<tr>
										<td>Product Category</td>
										<td><input value = "${category}" type = "text" name = "category" class = "form-control" readonly = "readonly" /></td>
									</tr>

									<!--Name field-->
									<tr>
										<td>Product Name</td>
										<td><input value = "${name}" type = "text" name = "name" class = "form-control" required /></td>
									</tr>

									<!--Price field-->
									<tr>
										<td>Product Price</td>
										<td><input value = ${price} type = "number" min = "0" name = "price" class = "form-control" step="0.01" required /></td>
									</tr>

									<!--Specification field-->
									<tr>
										<td>Product Specification</td>
										<td><input value = "${specification}" type = "text" name = "specification" class = "form-control" required /></td>
									</tr>

									<!--Brand field-->
									<tr>
										<td>Product Brand</td>
										<td><input value = "${brand}" type = "text" name = "brand" class = "form-control" required /></td>
									</tr>

									<!--Expire Date field-->
									<tr>
										<td>Expire Date</td>
										<td><input type = "text" id = "datepicker" name = "expireDate" class = "form-control" value = "${expire_date}" required></td>
										<script>
											$(function(){
												$("#datepicker").datepicker();
											});
										</script>
									</tr>

									<!--Stock Status field-->
									<tr>
										<td>Stock Status</td>
										<td><input value = ${stock_status} type = "number" name = "stockStatus" class = "form-control" required /></td>
									</tr>

									<!--button to submit the form-->
									<tr>
										<td></td>
										<td>
											<button type = "submit" class = "btn btn-info">
												<i class="fas fa-edit"></i> Update Product
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
							Create Form Content for Update Product (END)
		******************************************************************************************************/

		let whole_content_html = all_products_button + update_form_html;
		$("#page-content").html(whole_content_html);
		loadImg(id + ".jpg");  

		});


		//the handler for submitting the form

		//unbind the event from the last
		$(document).off("submit", "#update-product-form");
		$(document).on("submit", "#update-product-form", function(){
			let form_data = JSON.stringify($(this).serializeFormValues());
			console.log(form_data);
			let jsonObj = JSON.parse(form_data);
			console.log(jsonObj);
			jsonObj["updateImg"] = changeImage.toString();
			jsonObj["id"] = id;
			form_data = JSON.stringify(jsonObj);
			console.log(form_data);

			//send data to server via AJAX
			$.ajax({
				//url: "http://localhost:8888/jintian/api/product/update.php",
				url: server_address + "/api/product/update.php",
				type: "POST",
				contentType: "application/json",
				data: form_data,
				async: true,
				success: function(result){
					//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");
					showProducts(server_address + "/api/product/read_paging.php");
					alert("update successfully");
				},
				error: function(xhr, resp, text){
			        // show error to console
			        console.log(xhr, resp, text);
			        alert("can't update this product");			
				}
			});

			return false;
		});


		//the handler for uploading image

		var changeImage = false;

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
			        changeImage = true;
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
			$(".product-img-section").html(`<img src = ${src} class = "w-100-pct h-100-pct" /> `);
		});





	});

});