<?php 
include 'config.php';
session_start(); ?>
<?php

	$user = $_SESSION['username'];

	$db = new Database();
	$db->select('options','site_name',null,null,null,null);
	$site_name = $db->getResult();

	$_SESSION['TID'] = null
	$params1 = [
		'item_number' => $_POST['product_id'],
		'txn_id' => null
		'payment_gross' => $_POST['product_total'],
		'payment_status' => 'credit',
	];
	$params2 = [
		'product_id' => $_POST['product_id'],
		'product_qty' => $_POST['product_qty'],
		'total_amount' => $_POST['product_total'],
		'product_user' => $_SESSION['user_id'],
		'order_date' => date('Y-m-d'),
		'pay_req_id' => null
	];
	$db = new Database();
	$db->insert('payments',$params1);
	$db->insert('order_products',$params2);
	$db->getResult();


	if(!$_SESSION['TID']){
	$title = 'Payment Successful';
	$response = '<div class="panel-body">
				  	<i class="fa fa-check-circle text-success"></i>
				    <h3>Payment Successful</h3>
				    <p>Your Product Will be Delivered within 4 to 7 days.</p>
				  	<a href="'.$hostname.'" class="btn btn-md btn-primary">Continue Shopping</a>
				  </div>';

	  // reduce purchased quantity from products
	    $db = new Database();
	    $db->select('order_products','product_id,product_qty',null,null,null,null);
	    $result = $db->getResult();
	    $products = array_filter(explode(',',$result[0]['product_id']));
	    $qty = array_filter(explode(',',$result[0]['product_qty']));
	    for($i=0;$i<count($products);$i++){
	    	$db->sql("UPDATE products SET qty = qty - '{$qty[$i]}' WHERE product_id = '{$products[$i]}'");
	    }
	    $res = $db->getResult();


	  // remove cart items
	  	if(isset($_COOKIE['user_cart'])){
	        setcookie('cart_count','',time() - (180),'/','','',TRUE);
			setcookie('user_cart','',time() - (180),'/','','',TRUE);
	    }
	}else{
	$title = 'Payment UnSuccessful';
	$response = '<div class="panel-body">
				  	<i class="fa fa-times-circle text-danger"></i>
				    <h3>Payment Unsuccessful</h3>
				  	<a href="'.$hostname.'" class="btn btn-md btn-primary">Continue Shopping</a>
				  </div>';
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="payment-response">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-3 col-md-6">
					<div class="panel panel-default">
					  <?php echo $response; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>


