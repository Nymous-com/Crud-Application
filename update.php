<?php
session_start();
include 'includes/dbconnection.php';

	if (isset($_GET['id'])) 
		{
			$produt_id = $_GET['id'];
			$stmt = "SELECT * FROM crud_table WHERE id = :p_id LIMIT 1";
			$query= $conn->prepare($stmt);
			$data =[':p_id' => $produt_id];
			$query->execute($data);

		   $stmts = $query->fetch(PDO::FETCH_ASSOC);

		}

if ($_SERVER['REQUEST_METHOD'] = 'POST') {

if (isset($_POST['submit'])) {
	$new_stock_sold = $_POST['total_stock_sold'] + $stmts['total_stock_sold'];
	$new_available_stock_fr_purchase = $_POST['total_stock_purchased'] + $stmts['available_stock'];
	$pr_id = $_POST['pro_id']; 
	$stock_available = $stmts['available_stock'] - $stmts['total_stock_sold']; 

	if (empty($_POST['total_stock_sold'] || $_POST['total_stock_purchased'])) {
		$validation_error = '<p class="alert alert-danger">No changes is made</p>';

	}elseif($_POST['total_stock_sold'] > $stock_available ){
		$validation_error2 = '<p class="alert alert-danger">You have run out of stock</p>';
	}else{
		if (!empty($_POST['total_stock_sold'])) {
			try{

			$statement = $conn->prepare('UPDATE crud_table SET total_stock_sold = :total_stock_sold WHERE id = :p_id');
			$datas = [
				':p_id' => $pr_id,
				':total_stock_sold' => $new_stock_sold
			];

			$statement_execute = $statement->execute($datas);

			if ($statement_execute)
			{
				$_SESSION['message'] = 'Product Updated Successfully';
				header('location: read.php');
				exit(0);
			}else{
				$_SESSION['message'] = 'Product not updated';
				header('location: read.php');
				exit(0);
			}

			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}

		if (isset($_POST['total_stock_purchased'])) {
			try{

			$statement = $conn->prepare('UPDATE crud_table SET available_stock = :available_stock WHERE id = :p_id');
			$datas = [
				':p_id' => $pr_id,
				':available_stock' => $new_available_stock_fr_purchase,
			];

			$statement_execute = $statement->execute($datas);

			if ($statement_execute)
			{
				$_SESSION['message'] = 'Product Updated Successfully';
				header('location: read.php');
				exit(0);
			}else{
				$_SESSION['message'] = 'Product not updated';
				header('location: read.php');
				exit(0);
			}

			}catch(PDOException $e){
				echo $e->getMessage();
			}
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
				
				<?php if (isset($validation_error)){ echo $validation_error;} if(isset($validation_error2)){echo $validation_error2;} ?>
				<div class="card ">

					<div class="card-header">
						<h3 class="btn btn-primary">UPDATE</h3>
						<a href="read.php" class="btn btn-danger float-end">Back</a>
					</div>

					<div class="card-body">


						<form method="POST">
							<input type="hidden" name="pro_id" value="<?php echo $stmts['id'];?>">

							<div class="mb-3">
								<input type="text" name="product"  readonly class="form-control" value="<?php echo $stmts['product'];?>" >
							</div>

							<div class="mb-3">
								<input type="text" name="available_stock" readonly  class="form-control" value="<?php echo $stmts['available_stock'];?>">
							</div>

							<div class="mb-3">
								<input type="text" name="total_stock_sold" placeholder="Number of the product sold (Optional)" class="form-control" value="">
							</div>

							<div class="mb-3">
								<input type="text" name="total_stock_purchased" placeholder="Number of the product purchased (Optional)" class="form-control" value="">
							</div>

								<input type="submit" value ="Update Product" name="submit" class="btn btn-success ">
						</form>
					</div>
				</div>

			</div>


		</div>
	</div>

</body>
</html>
