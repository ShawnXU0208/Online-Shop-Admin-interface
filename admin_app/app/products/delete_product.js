$(document).ready(function(){
	// show html form when "delete product" button is clicked
	$(document).on("click", ".delete-product-button", function(){
		let id = $(this).attr("data-id");
		let categoryID = $(this).attr("data-category");
		let name = $(this).attr("data-name");
		let sendData = JSON.stringify({"id": id, "category": categoryID});
		console.log(sendData);

		//bootbox for a confirmation box
		bootbox.confirm({
			message: `<h6>Are you sure of deleting product ${name} ?</h6>`,
			buttons: {
				confirm: {
					label: `<i class='fas fa-check'></i> Yes`,
					classNmae: 'btn-danger'
				},
				cancel: {
					label: `<i class='fas fa-times'></i> No`,
					classNmae: "btn-primary"
				}
			},

			callback: function(result){
				if(result == true){
					//send the delete request to server
					$.ajax({
						//url: "http://localhost:8888/jintian/api/product/delete.php",
						url: server_address + "/api/product/delete.php",
						type: "POST",
						contentType: "application/json",
						data: sendData,
						async: true,
						success: function(result){
							//showProducts("http://localhost:8888/jintian/api/product/read_paging.php");
							showProducts(server_address + "/api/product/read_paging.php");
							alert("delete successfully");
						},
						error: function(xhr, resp, text){
					        // show error to console
					        console.log(xhr, resp, text);
					        alert("can't delete this product");			
						}					
					});
				}
			}
		});

	});

});