<?php
session_start();
error_reporting(0);
include('includes/config.php');
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
//error_reporting(E_ALL);
if (isset($_GET['action']) && $_GET['action'] == "add") {
	// Check if an order ID already exists, if not, create one.
	if (!isset($_SESSION['order_id'])) {
		$_SESSION['order_id'] = uniqid();
	}
	session_regenerate_id();
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
}
// Fetch product details
if ($pid > 0) {

	$stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
	$stmt->bind_param("i", $pid);
	$stmt->execute();
	$result = $stmt->get_result();
	$product = $result->fetch_assoc();
	// echo '<pre>';
	// var_dump($product);
	// echo '</pre>';
} else {
	$product = false;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Details</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/green.css">
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
		<?php include('includes/main-header.php'); ?>
	</header>
	<div class="body-content outer-top-xs">
		<div class='container'>
			<div class='row single-product outer-bottom-sm '>
				<div class='col-md-3 sidebar'>
					<div class="sidebar-module-container">
						<div class="sidebar-widget outer-bottom-xs wow fadeInUp">
							<h3 class="section-title">Category</h3>
							<div class="sidebar-widget-body m-t-10">
								<div class="accordion">
									<?php $sql = mysqli_query($con, "select id,categoryName  from category");
									while ($row = mysqli_fetch_array($sql)) {
									?>
										<div class="accordion-group">
											<div class="accordion-heading">
												<a href="category.php?cid=<?php echo $row['id']; ?>" class="accordion-toggle collapsed">
													<?php echo $row['categoryName']; ?>
												</a>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="sidebar-widget hot-deals wow fadeInUp">
						</div>
					</div>
				</div><!-- /.sidebar -->
				<?php
				$ret = mysqli_query($con, "select * from products where id='$pid'");
				while ($row = mysqli_fetch_array($ret)) {
				?>
					<div class='col-md-9'>
						<div class="row  wow fadeInUp">
							<div class="col-xs-12 col-sm-6 col-md-5 gallery-holder">
								<div class="product-item-holder size-big single-product-gallery small-gallery">
										<div class="single-product-gallery-item">
											<a data-lightbox="image-1" data-title="<?php echo htmlentities($row['productName']); ?>" href="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>">
												<img alt="" src="assets/images/blank.gif" data-echo="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>" width="370" height="150" />
											</a>
										</div>

								</div>
							</div>
							<div class='col-sm-6 col-md-7 product-info-block'>
								<div class="product-info">
									<h1 class="name"><?php echo htmlentities($row['productName']); ?></h1>

									<div class="stock-container info-container m-t-10">
										<div class="row">
											<div class="col-sm-3">
												<div class="stock-box">
													<span class="label">Availability :</span>
												</div>
											</div>
											<div class="col-sm-9">
												<div class="stock-box">
													<span class="value"><?php echo htmlentities($row['productAvailability']); ?></span>
												</div>
											</div>
										</div>
									</div>

									<div class="price-container info-container m-t-20">
										<div class="row">
											<div class="col-sm-6">
												<div class="price-box">
													<span class="price">£ <?php echo htmlentities($row['productPrice']); ?></span>
													<span class="price-strike">£<?php echo htmlentities($row['productPriceBeforeDiscount']); ?></span>
												</div>
											</div>
										</div>
									</div>
									<?php if ($row['productAvailability'] == 'Available') { ?>
										<div class="quantity-container info-container">
											<div class="row">
												<div class="col-sm-2">
													<span class="label">Qty :</span>
												</div>
												<div class="col-sm-2">
													<div class="cart-quantity">
														<div class="quant-input">
															<div class="arrows">
																<div class="arrow plus gradient"><span class="ir"><i class="icon fa fa-sort-asc"></i></span></div>
																<div class="arrow minus gradient"><span class="ir"><i class="icon fa fa-sort-desc"></i></span></div>
															</div>
															<input type="text" value="1">
														</div>
													</div>
												</div>
												<div class="col-sm-7">

													<a href="product-details.php?page=product&action=add&id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart inner-right-vs"></i> ADD TO CART</a>
												<?php } else { ?>
													<div class="action" style="color:red">Not Available</div>
												<?php } ?>
												</div>
											</div>
										</div>
								</div>
							</div>
						</div>
						<div class="product-tabs inner-bottom-xs  wow fadeInUp">
							<div class="row">
								<div class="col-sm-3">
									<ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
										<li class="active"><a data-toggle="tab" href="#description">DESCRIPTION</a></li>
									</ul>
								</div>
								<div class="col-sm-9">
									<div class="tab-content">
										<div id="description" class="tab-pane in active">
											<div class="product-tab">
												<p class="text"><?php echo $row['productDescription']; ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>

	<?php $cid = $row['category'];
				} ?>


	</div><!-- /.col -->
	</div>
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
	<script src="assets/js/wow.min.js"></script>
	<script src="assets/js/scripts.js"></script>
</body>

</html>