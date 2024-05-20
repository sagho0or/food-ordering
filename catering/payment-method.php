<?php
session_start();
session_regenerate_id(true);

// Set secure cookie attributes
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

error_reporting(0);
ini_set('display_errors', 1);
include 'includes/config.php';
$apiContext = require 'includes/paypal-config.php';
require 'vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

// Generating a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			die("Invalid email format");
		}
		$paymentMethod = $_POST['paymethod'] ?? '';
		$orderId = $_SESSION['order_id'];
		$_SESSION['user_email'] = $_POST['email'];
		if ($paymentMethod === 'CASH') {
				header('Location:success-cache.php?orderId=$orderId"' .  $orderId);
			exit;
		} elseif ($paymentMethod === 'PayPal') {
			// implement PayPal payment
			$payer = new Payer();
			$payer->setPaymentMethod('paypal');
	
			$amount = new Amount();
			$amount->setTotal($_SESSION['tp']);
			$amount->setCurrency('GBP');
	
			$transaction = new Transaction();
			$transaction->setAmount($amount);
			$transaction->setDescription('Payment for Order #' . $orderId);
	
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("http://localhost/food-ordering/catering/execute-payment.php?success=true&orderId=$orderId")
				->setCancelUrl("http://localhost/food-ordering/catering/execute-payment.php?success=false&orderId=$orderId");
	
			$payment = new Payment();
			$payment->setIntent('sale')
				->setPayer($payer)
				->setTransactions(array($transaction))
				->setRedirectUrls($redirectUrls);
	
			try {
				$payment->create($apiContext);
				header('Location: ' . $payment->getApprovalLink());
			} catch (Exception $ex) {
				error_log("Error creating PayPal payment: " . $ex->getMessage());
				// Handle PayPal payment creation error
				die($ex->getMessage());
			}
		}
	} else {
		die("CSRF token validation failed");
	}
	
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
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="assets/images/favicon.ico">
</head>

<body class="cnt-home">
	<header class="header-style-1">
		<?php include 'includes/main-header.php';?>
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
										<form name="payment" method="post" action="#">
										<div class="form-group">
											<label for="exampleInputEmail1">Email address</label>
											<input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Enter email">
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="CASH" name="paymethod" id="CASH" checked>
											<label class="form-check-label" for="CASH" >
											CASH
											</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="PayPal" name="paymethod" id="PayPal">
											<label class="form-check-label" for="PayPal" >
											PayPal
											</label>
										</div>
										<div class="form-check">
											<?php echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';?>
											<input type="submit" value="submit" name="submit" class="btn btn-info">
										</div>
										</form>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<script src="assets/js/jquery-1.11.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	<script src="assets/js/scripts.js"></script>
</body>

</html>