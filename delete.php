<?php
include 'includes/dbconnection.php';
session_start();

if(isset($_POST['delete'])){

	$product_id = $_POST['delete'];

	try{

		$query = $conn->prepare("DELETE FROM crud_table WHERE id = :p_id");
		$data = [':p_id' => $product_id];
		$statement_execute = $query->execute($data);

		if ($statement_execute) {
			$_SESSION['message'] = 'Product Deleted Successfully';
			header('location: read.php');
			exit(0);

		}else{
			$_SESSION['message'] = 'Product not Deleted';
			header('location: read.php');
			exit(0);
			}

	}catch(PDOException $e){
		echo $e->getMessage();
	}
}


?>
