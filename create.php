<?php
session_start(); 
include 'includes/dbconnection.php';


function checkDuplicateProduct($value, $conn)
{
	try{

		$stmts = $conn->prepare("SELECT product FROM crud_table WHERE product =:product");
		$stmts->execute(array(':product' => $value));

		if ($row = $stmts->fetch()) {

			return true; // This prractice changes 
		}

		return false;

	}catch(PDOException $ex){

	}
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$product = htmlspecialchars($_POST['product']);
	$available_stock = htmlspecialchars($_POST['available_stock']);
	$total_stock_sold = htmlspecialchars($_POST['total_stock_sold']);


	if (isset($_POST['submit'])) {
		// Form Validation 

			if (empty($product)) {
				$product_error = '<p class="alert alert-danger">You must insert a specific product</p>';
			}elseif(empty($available_stock) ){
				$available_stock_error = '<p class="alert alert-danger">You must insert number of stock available </p>';
			}elseif(!isset($total_stock_sold) || $total_stock_sold < 0  ) {
				$total_stock_sold_error = '<p class="alert alert-danger">You must insert number of stock sold till date</p>';
			}elseif (checkDuplicateProduct($product, $conn)){
				$duplicate_error = '<p class="alert alert-danger">Product already added, <b>Update</b> the product in the dashboard page </p>'; 
			}
	


	else{


		// insert into database 
		$query = $conn->prepare("INSERT INTO crud_table(product, available_stock, total_stock_sold) VALUES(:product, :available_stock, :total_stock_sold)");

		$data = [
			':product' => $product,
			':available_stock' => $available_stock,
			':total_stock_sold' => $total_stock_sold
		];

		$query_execute = $query->execute($data);

		if ($query_execute) {
			$_SESSION['message'] = 'Product Added Successfully';
			header('location: read.php');
			exit(0);
		}else{
			$_SESSION['message'] = 'Product not Added';
			header('location: read.php');
			exit(0);
			}

	}
	

	}
}



 ?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Product</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 mt-3">
				<?php
					if (isset($product_error)) {
						echo $product_error;
					}elseif (isset($available_stock_error)) {
						echo $available_stock_error;
					}elseif (isset($total_stock_sold_error)) {
						echo $total_stock_sold_error;
					}elseif (isset($duplicate_error)) {
						echo $duplicate_error;
					}
				?>

				<div class="card ">
					<div class="card-header">
						<p class="btn btn-primary">CREATE</p>
						<a href="read.php" class="btn btn-danger float-end">Back</a>
					</div>

					<div class="card-body">
						<form method="POST">
							<div class="mb-3">
								<input type="text" name="product" placeholder="Name of product" class="form-control" value="<?php if(isset($product)){ echo $product; }?>">
							</div>

							<div class="mb-3">
								<input type="text" name="available_stock" placeholder="Number of the product in stock " class="form-control" value="<?php if(isset($available_stock)){ echo $available_stock; }?>">
							</div>

							<div class="mb-3">
								<input type="text" name="total_stock_sold" placeholder="Number of the product sold (Optional)" class="form-control" value="<?php if(isset($total_stock_sold)){ echo $total_stock_sold; }?>">
							</div>

								<input type="submit" value ="Save Product" name="submit" class="btn btn-success ">
						</form>
					</div>
				</div>

			</div>


		</div>
	</div>

</body>
</html>
