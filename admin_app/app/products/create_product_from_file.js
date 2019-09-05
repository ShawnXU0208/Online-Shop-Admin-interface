$(document).ready(function(){

	let csv_data_uploaded = false;
	let images_data_uploaded = false;
	let csv_data = [];
	let images_data = {};
	let final_data = [];
	let valid_data = false;


	$(document).on("click", "#create-multiple", function(){


		//go back button
		let all_products_button = `
			<div id = "all_products_button" class = "btn btn-primary float-right m-b-15px all-products-button">
				<i class="fas fa-list"></i> CANCEL
			</div>
		`;

		let input_html = `

			<div class = "container m-b-20px">
				<div class = "row">

					<div class = "col-9 p-0 m-0">
						<!--when clicked, it will read a csv file-->
						<div class="custom-file m-b-15px">
							<input type = "file" class = "custom-file-input" id = "csv-chooser" accept = ".csv" />
							<label class = "custom-file-label" for = "csv-chooser">Import CSV</label>
						</div>

						<!--when clicked, it will read a image file folder-->
						<div class="custom-file">
							<input type = "file" class = "custom-file-input" id = "image-folder-chooser" webkitdirectory directory multiple />
							<label class = "custom-file-label" for = "image-folder-chooser">Import Image</label>
						</div>
					</div>


					<div class = "col-3 p-0 m-0">	

						<!--when clicked, it will show products list-->
						<button class = "btn btn-primary h-100-pct float-right" id = "upload-products" style = "width: 100px;">
							<i class="fas fa-cloud-upload-alt"></i>Import
						</button>

						<!--when clicked, it will show products list-->
						<button class = "btn btn-info h-100-pct m-r-10px float-right" id = "preview-products" style = "width: 100px;">
							<i class="fas fa-eye"></i>View
						</button>	

					</div>

				</div>
			</div>

			<div id = "result-content"><div>
		`;

		let page_html = all_products_button + input_html;

		$("#page-content").html(page_html);
	});


	$(document).on("change", "#csv-chooser", function(event){

	    //get the file name
	    var fileName = event.target.files[0].name;
	    //replace the "Choose a file" label
	    $(this).next('.custom-file-label').html(fileName);

		let file = event.target.files[0];

		Papa.parse(file, {
			complete: function(result){
				let csv_header = result.data[0];
				console.log(csv_header);
				if(csv_header[0] == "name" &&
					csv_header[1] == "category" &&
					csv_header[2] == "price" &&
					csv_header[3] == "brand" &&
					csv_header[4] == "specification" &&
					csv_header[5] == "image" &&
					csv_header[6] == "expire" &&
					csv_header[7] == "stock"
				){
					csv_data = result.data.slice(1);
					csv_data_uploaded = true

				}else{
					alert("file's format is wrong");
				}
			}
		});		
	});


	$(document).on("change", "#image-folder-chooser", function(event){

	    //get the file name
	    var label_info = event.target.files.length + " files are selected";
	    //replace the "Choose a file" label
	    $(this).next('.custom-file-label').html(label_info);

		let files = event.target.files;

		$.each(files, function(key, file){
			images_data[file.name] = file;
		});

		images_data_uploaded = true;

	});

	function check_csv_data(){

		if(csv_data_uploaded && images_data_uploaded){

			$.each(csv_data, function(key, val){

				let json_obj = {
					"name": val[0],
					"price": val[2],
					"brand": val[3],
					"specification": val[4],
					"expireDate": val[6],
					"stockStatus": val[7],
					"category": val[1]
				};

				let product_info = JSON.stringify(json_obj);

				//try to get the corresponding image
				let image_file = images_data[val[5]];
				if (!image_file){
					alert("can't find a matched image");
					valid_data = false;
				}else{
					valid_data = true;
				}

				final_data.push([product_info, image_file]);

			});
		}else{
			alert("please import a file");
		}	
	}

	$(document).on("click", "#preview-products", function(){

		check_csv_data();

		//display table to display all products info from csv file
		if(valid_data){

			let table_html = `
			<div class = "table-responsive" style = "max-height: 600px">
				<table class = 'table table-bordered table-hover text-nowrap'>
					<!--table heading-->
					<tr>				
						<th>name</th>
						<th>category</th>
						<th>price</th>
						<th>brand</th>
						<th>specification</th>
						<th>image</th>
						<th>expire</th>
						<th>stock</th>
					</tr>
			`;


			$.each(csv_data, function(key, record){
				//create table row for each
				table_html += `
					<tr>
						<td>${record[0]}</td>
						<td>${record[1]}</td>
						<td>${record[2]}</td>
						<td>${record[3]}</td>
						<td>${record[4]}</td>
						<td>${record[5]}</td>
						<td>${record[6]}</td>
						<td>${record[7]}</td>
					</tr>
				`;
			});

			table_html += `</table></div>`;

			//inject to 'page-content' of the app
			$("#result-content").html(table_html);
		}

	});


	$(document).on("click", "#upload-products", function(){
		if(!valid_data){
			check_csv_data();
		}

		if(valid_data){

			let products_count = 0;
			let stop_processing = false;

			$.each(final_data, function(key, val){

				//stop executing when an error occurs
				if(stop_processing){
					alert("failed");
					return false;
				}

				let product_info = val[0];
				let image_file = val[1]

				let image_data = new FormData();
				image_data.append("imageFile", image_file);

				//send image to server
				$.ajax({
					//url: "http://localhost:8888/jintian/admin_app/app/image_upload/upload.php",
					url: server_address + "/admin_app/app/image_upload/upload.php",
					type: "POST",
					data: image_data,
					processData: false,
				    contentType: false,
				    async: false,
				    error: function(xhr, resp, text){
				    	stop_processing = true;
				    	console.log(xhr, resp, text);
				    	throw new Error("uploading image failed");
				    }
				});	


				//send data to server via AJAX
				$.ajax({
					//url: "http://localhost:8888/jintian/api/product/create.php",
					url: server_address + "/api/product/create.php",
					type: "POST",
					contentType: "application/json",
					data: product_info,
					async: false,
					error: function(xhr, resp, text){
				        stop_processing = true;
				        console.log(xhr, resp, text);	
				        throw new Error("uploading product data failed");	
					}
				});

				//count products added
				products_count += 1;

			});

			//execute when no error occurs when creating products
			if(!stop_processing){
				alert(products_count + " products are imported successfully");
				//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");
				showProducts(server_address + "/api/product/read_paging.php");
			}


		}

	});

});