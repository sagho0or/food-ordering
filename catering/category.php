<?php
session_start();
error_reporting(0);
include 'includes/config.php';
$cid = intval($_GET['cid']);
if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
		echo "<script type='text/javascript'> document.location ='my-cart.php'; </script>";
    } else {
        $sql_p = "SELECT * FROM products WHERE id={$id}";
        $query_p = mysqli_query($con, $sql_p);
        if (mysqli_num_rows($query_p) != 0) {
            $row_p = mysqli_fetch_array($query_p);
            $_SESSION['cart'][$row_p['id']] = array("quantity" => 1, "price" => $row_p['productPrice']);
            echo "<script type='text/javascript'> document.location ='my-cart.php'; </script>";
        } else {
            $message = "Product ID is invalid";
        }
    }
}
// COde for Wishlist
if (isset($_GET['pid']) && $_GET['action'] == "wishlist") {
    if (strlen($_SESSION['login']) == 0) {
        header('location:login.php');
    } else {
        mysqli_query($con, "insert into wishlist(userId,productId) values('" . $_SESSION['id'] . "','" . $_GET['pid'] . "')");
        echo "<script>alert('Product aaded in wishlist');</script>";
        header('location:my-wishlist.php');
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
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Product Category</title>

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/css/green.css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css">
	<link href="assets/css/lightbox.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/animate.min.css">
	<link rel="stylesheet" href="assets/css/rateit.css">
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
		<div class='container'>
			<div class='row outer-bottom-sm'>
				<div class="col-xs-12 col-sm-12 col-md-3 sidebar">
					<?php include 'includes/side-menu.php';?>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9">

					<div class="search-result-container">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane active " id="grid-container">
								<div class="category-product  inner-top-vs">
									<div class="row">
										<?php
$ret = mysqli_query($con, "select * from products where category='$cid'");
$num = mysqli_num_rows($ret);
if ($num > 0) {
    while ($row = mysqli_fetch_array($ret)) {?>
												<div class="col-sm-6 col-md-4 wow fadeInUp">
													<div class="products">
														<div class="product">
															<div class="product-image">
																<div class="image">
																	<a href="product-details.php?pid=<?php echo htmlentities($row['id']); ?>">
																		<img src="admin/productimages/<?php echo htmlentities($row['id']); ?>/<?php echo htmlentities($row['productImage1']); ?>" alt="" width="200" height="115">
																	</a>
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
															<div class="cart clearfix animate-effect">
																<div class="action">
																	<ul class="list-unstyled">
																		<li class="add-cart-button btn-group">

																			<?php if ($row['productAvailability'] == 'Available') {?>
																				<button class="btn btn-primary icon" data-toggle="dropdown" type="button">
																					<i class="fa fa-shopping-cart"></i>
																				</button>
																				<a href="category.php?page=product&action=add&id=<?php echo $row['id']; ?>">
																					<button class="btn btn-primary" type="button">Add to cart</button></a>
																			<?php } else {?>
																				<div class="action" style="color:red">Not Available</div>
																			<?php }?>

																		</li>

																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
											<?php }
} else {?>

											<div class="col-sm-6 col-md-4 wow fadeInUp">
												<h3>No Product Found</h3>
											</div>

										<?php }?>
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
	<script src="assets/js/jquery.rateit.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	<script src="assets/js/scripts.js"></script>

</body>

</html>