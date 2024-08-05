<?php
session_start();
include 'includes/dbconnection.php';






?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Read Product</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
</head>
<body>

	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-11 mt-3">

			<?php if(isset($_SESSION['message'] )) :?>
 				<h5 class="alert alert-success"> <?php echo $_SESSION['message']; ?></h5>
   			 <?php unset($_SESSION['message']); endif;?>

				<div class="card ">
					<div class="card-header">
						<a href="create.php" class="btn btn-success float-end">Add New Product</a>
					</div>

					<div class="card-body">
					   <table class="table table-bordered table-striped">
						   	<thead>
						   		<tr>
							   		<th>ID</th>
							   		<th>Product</th>
							   		<th>Stock Purchased(UNITS)</th>
							   		<th>Total No. Sold (UNITS)</th>
							   		<th>Stock Available For Sales(UNITS)</th>
							   		<th>Edit</th>
							   		<th>Delete</th>
						   		</tr>
						   	</thead>
						   	<tbody>

						   		<?php
						   			$query = $conn->prepare("SELECT * FROM crud_table");
						   			$query->execute();

						   			$fetch = $query->fetchAll(PDO::FETCH_ASSOC);

						   			if ($fetch) {

						   				foreach($fetch as $rl){
						   					?>

						   					<tr>
						   						<td> <?php echo $rl['id'];  ?></td>
						   						<td> <?php echo $rl['product'];  ?></td>
						   						<td> <?php echo $rl['available_stock'];  ?></td>
						   						<td> <?php echo $rl['total_stock_sold'];  ?></td>
						   						<td> <?php $as = $rl['available_stock']; $tss = $rl['total_stock_sold']; echo $as - $tss; ?></td>
						   						<td><a href="update.php?id=<?= $rl['id']; ?>" class="btn btn-success">Update</a></td>
						   						<td>
						   							<form method="POST" action="delete.php">
						   								<button type="submit" name="delete" value="<?= $rl['id']; ?>" class="btn btn-danger">Delete</button>
						   							</form>
						   							
						   						</td>
						   					</tr>
						   					<?php
						   				}
						   				
						   			}else{
						   				?>
						   				<tr>
						   					<td colspan="4"> No Record Found</td>
						   				</tr>
						   				<?php 
						   			}
						   		?>
						   	</tbody>
					   </table>
					</div>
				</div>
				
			</div>


		</div>
	</div>


</body>
</html>
