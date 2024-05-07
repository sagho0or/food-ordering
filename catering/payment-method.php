<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['submit'])) {
	$orderId = $_SESSION['order_id'];
	mysqli_query($con, "UPDATE orders SET paymentMethod='" . $_POST['paymethod'] . "' WHERE orderId='" . $orderId . "' AND paymentMethod IS NULL");
	
	unset($_SESSION['cart']);
	unset($_SESSION['order_id']);
	session_destroy(); 
	header('location:order-history.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Payment Method</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/green.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css">
	<link href="assets/css/lightbox.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="assets/css/config.css">
	<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
	<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
	<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
	<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
	<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="assets/images/favicon.ico">
</head>

<body class="cnt-home">
	<header class="header-style-1">
		<?php include('includes/main-header.php'); ?>
	</header>
	<div class="body-content outer-top-bd">
		<div class="container">
			<div class="checkout-box faq-page inner-bottom-sm">
				<div class="row">
					<div class="col-md-12">
						<h2>Choose Payment Method</h2>
						<div class="panel-group checkout-steps" id="accordion">
							<div class="panel panel-default checkout-step-01">
								<div class="panel-heading">
									<h4 class="unicase-checkout-title">
										<a data-toggle="collapse" class="" data-parent="#accordion" href="#collapseOne">
											Select your Payment Method
										</a>
									</h4>
								</div>

								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<form name="payment" method="post">
											<input type="radio" name="paymethod" value="CASH" checked="checked"> CASH
											<input type="radio" name="paymethod" value="PayPal"> PayPal
											<input type="submit" value="submit" name="submit" class="btn btn-primary">


										</form>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div><!-- /.row -->
			</div><!-- /.checkout-box -->
			
		</div>
	</div>
	<script src="assets/js/jquery-1.11.1.min.js"></script>

	<script src="assets/js/bootstrap.min.js"></script>

	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>

	<script src="assets/js/echo.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
	<script src="assets/js/bootstrap-slider.min.js"></script>
	<script src="assets/js/jquery.rateit.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	<script src="assets/js/scripts.js"></script>
</body>

</html>