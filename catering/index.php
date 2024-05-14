<?php
session_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include('includes/config.php');
if (isset($_GET['action']) && $_GET['action'] == "add") {
	
	$id = intval($_GET['id']);
	if (isset($_SESSION['cart'][$id])) {
		$_SESSION['cart'][$id]['quantity']++;
		header('Location: my-cart.php');
		$_SESSION['message'] = 'Product has been added to the cart';
        exit;
    } else {
        // Using prepared statements for security
        $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows != 0) {
            $row_p = $result->fetch_assoc();
            $_SESSION['cart'][$row_p['id']] = array("quantity" => 1, "price" => $row_p['productPrice']);
            $_SESSION['message'] = 'Product has been added to the cart';
            header('Location: my-cart.php');
            exit;
        } else {
            $_SESSION['error'] = 'Product ID is invalid';
            header('Location: index.php'); 
            exit;
        }
	}
	echo "<script>alert('Product has been added to the cart')</script>";
	header('Location: my-cart.php');
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

	<title>University Catering</title>

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

	<div class="body-content outer-top-xs" id="top-banner-and-menu">
		<div class="container">
			<div class="furniture-container homepage-container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-3 sidebar">
						<?php include('includes/side-menu.php'); ?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9">
						<div id="product-tabs-slider" class="scroll-tabs inner-bottom-vs  wow fadeInUp">
							<div class="more-info-tab clearfix">
								<h3 class="new-product-title pull-left">All Products</h3>
								
							</div>

							<div class="tab-content outer-top-xs">
								<div class="tab-pane in active" id="all">
									<div class="product-slider">
										<div class="owl-carousel home-owl-carousel custom-carousel owl-theme" data-item="4">
											<?php
											$ret = mysqli_query($con, "select * from products");
											while ($row = mysqli_fetch_array($ret)) {
												
											?>
												<div class="item item-carousel">
													<div class="products">
														<div class="product">
															<div class="product-image">
																<div class="image">
																	<a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>">
																		<img src="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>" data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>" width="180" height="115" alt=""></a>
																</div>
															</div>
															<div class="product-info text-left">
																<h3 class="name"><a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['productName']); ?></a></h3>
																<div class="rating rateit-small"></div>
																<div class="description"></div>

																<div class="product-price">
																	<span class="price">
																	£ <?php echo htmlentities($row['productPrice']); ?> </span>
																</div>
															</div>
															<?php if ($row['productAvailability'] == 'Available') { ?>
																<div class="action"><a href="index.php?page=product&action=add&id=<?php echo $row['id']; ?>" class="lnk btn btn-primary">Add to Cart</a></div>
															<?php } else { ?>
																<div class="action" style="color:red">Not Available</div>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } ?>
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
		<script src="assets/js/owl.carousel.min.js"></script>
		<script src="assets/js/jquery.easing-1.3.min.js"></script>
		<script src="assets/js/bootstrap-slider.min.js"></script>
		<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
		<script src="assets/js/bootstrap-select.min.js"></script>
		<script src="assets/js/scripts.js"></script>
</body>

</html>