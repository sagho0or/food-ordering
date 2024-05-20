<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include 'includes/config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

session_start();

try {
	
	$orderId = $_SESSION['order_id'] ?? 'UNKNOWN_ORDER_ID';
	$userEmail = $_SESSION['user_email'] ?? 'user@example.com';
	$message = "Your order with ID: {$orderId} has been placed. Go to pay cash and pick it up and a receipt has been sent to your email.";

	if ($con && $orderId !== 'UNKNOWN_ORDER_ID') {
		$updateQuery = "UPDATE orders SET orderStatus = 'Completed', paymentMethod = ? WHERE orderId = ?";
		$stmt = $con->prepare($updateQuery);
		$paymentMethod = 'CASH';
		$stmt->bind_param("ss", $paymentMethod, $orderId);
		$stmt->close();
		//send email to user's email
		sendConfirmationEmail($userEmail, $orderId, $con);
		unset($_SESSION['order_id']);
		unset($_SESSION['cart']);
	}

	
} catch (Exception $ex) {
	die($ex->getMessage());
}

function sendConfirmationEmail($toEmail, $orderId, $con)
{
    $mail = new PHPMailer(true);

    // Fetch product details for the order
    $productDetails = '';
    $stmt = $con->prepare("SELECT productId, productQuantity, productName FROM orderdetails WHERE orderId = ?");
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $productDetails .= "Product: " . $row['productName'] . ", Quantity: " . $row['productQuantity'] . "<br>";
    }
    $stmt->close();
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hull.uni.catering@gmail.com';
        $mail->Password = 'npjl vvnm hmvz hsvj '; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom('hull.uni.catering@gmail.com', 'Mailer');
        $mail->addAddress($toEmail);

        //Content
        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation #$orderId";
        $mail->Body    = "Dear Customer,<br><br>Your order #$orderId has been successfully processed. Here are the details:<br>" . $productDetails . "<br>You can pay cash and pick up your items as per the schedule. Thank you for shopping with us!";
        $mail->AltBody = "Dear Customer,\n\nYour order #$orderId has been successfully processed. Here are the details:\n" . strip_tags($productDetails) . "\nYou can pay cash and pick up your items as per the schedule. Thank you for shopping with us!";

        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="Saghar Fadaei">
	<meta name="robots" content="all">

	<title>Order receipt</title>

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

	<!-- Customizable CSS -->
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

	<div class="body-content outer-top-xs">
		<div class="container">
			<div class="homepage-container">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<div class="alert alert-success">
							<h1>Thank You!</h1>
							<p><?php echo $message; ?></p>
						</div>
					</div>
				</div>

			</div>
		</div>

		<script src="assets/js/jquery-1.11.1.min.js"></script>

		<script src="assets/js/bootstrap.min.js"></script>

		<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
		<script src="assets/js/owl.carousel.min.js"></script>
		<script src="assets/js/jquery.easing-1.3.min.js"></script>
		<script src="assets/js/bootstrap-slider.min.js"></script>
		<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
		<script src="assets/js/bootstrap-select.min.js"></script>
		<script src="assets/js/scripts.js"></script>
</body>

</html>