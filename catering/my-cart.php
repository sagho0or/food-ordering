<?php
session_start();
error_reporting(0);
include 'includes/config.php';
if (isset($_POST['submit'])) {
    if (!empty($_SESSION['cart'])) {
        foreach ($_POST['quantity'] as $key => $val) {
            if ($val == 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                $_SESSION['cart'][$key]['quantity'] = $val;
            }
        }
        echo "<script>alert('Your Cart hasbeen Updated');</script>";
    }
}
// Code for Remove a Product from Cart
if (isset($_POST['remove_code'])) {

    if (!empty($_SESSION['cart'])) {
        foreach ($_POST['remove_code'] as $key) {

            unset($_SESSION['cart'][$key]);
        }
        echo "<script>alert('Your Cart has been Updated');</script>";
    }
}
if (isset($_POST['ordersubmit'])) {
    $con->begin_transaction();
    try {
        if (!isset($_SESSION['order_id'])) {
            echo "<script>alert('Product has been added to the cart')</script>";
            $_SESSION['order_id'] = uniqid();
            $orderId = $_SESSION['order_id'];
            $productIds = array_keys($_SESSION['cart']);
            $defaultPaymentMethod = "CASH";
            $defaultStatus = "Pending";

            $stmt = $con->prepare("INSERT INTO orders( orderId, orderDate, paymentMethod, orderStatus) VALUES (?, NOW(), ?, ?)");
            $stmt->bind_param("sis", $orderId, $defaultPaymentMethod, $defaultStatus);
            $stmt->execute();
        } else {
            $orderId = $_SESSION['order_id'];
        }

        // insert into orderdetails
        foreach ($_SESSION['cart'] as $id => $details) {
            $productDetails = mysqli_query($con, "SELECT productName, productPrice FROM products WHERE id = $id");
            if ($row = mysqli_fetch_assoc($productDetails)) {
                $stmt = $con->prepare("INSERT INTO orderdetails (orderId, productId, productQuantity, productName, productPrice) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiss", $orderId, $id, $details['quantity'], $row['productName'], $row['productPrice']);
				$stmt->execute();
                $stmt->close();
            }
        }

        $con->commit();
        header('location:payment-method.php');
        exit;
    } catch (Exception $e) {
        $con->rollback(); // Rollback changes on error
        echo "Error: " . $e->getMessage();
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

	<title>My Cart</title>
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
		<?php include 'includes/main-header.php';?>
	</header>
	<div class="body-content outer-top-xs">
		<div class="container">
			<div class="row inner-bottom-sm">
				<div class="shopping-cart">
					<div class="col-md-12 col-sm-12 shopping-cart-table ">
						<div class="table-responsive">
							<form name="cart" method="post">
								<?php
if (!empty($_SESSION['cart'])) {
    ?>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="cart-romove item">Remove</th>
												<th class="cart-description item">Image</th>
												<th class="cart-product-name item">Product Name</th>

												<th class="cart-qty item">Quantity</th>
												<th class="cart-sub-total item">Price Per unit</th>
												<th class="cart-total last-item">Grandtotal</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<td colspan="7">
													<div class="shopping-cart-btn">
														<span class="">
															<a href="index.php" class="btn btn-upper btn-primary outer-left-xs">Continue Shopping</a>
															<input type="submit" name="submit" value="Update shopping cart" class="btn btn-upper btn-primary pull-right outer-right-xs">
														</span>
													</div>
												</td>
											</tr>
										</tfoot>
										<tbody>
											<?php
$pdtid = array();
    $sql = "SELECT * FROM products WHERE id IN(";
    foreach ($_SESSION['cart'] as $id => $value) {
        $sql .= $id . ",";
    }
    $sql = substr($sql, 0, -1) . ") ORDER BY id ASC";
    $query = mysqli_query($con, $sql);
    $totalprice = 0;
    $totalqunty = 0;
    if (!empty($query)) {
        while ($row = mysqli_fetch_array($query)) {
            $quantity = $_SESSION['cart'][$row['id']]['quantity'];
            $subtotal = $_SESSION['cart'][$row['id']]['quantity'] * $row['productPrice'];
            $totalprice += $subtotal;
            $_SESSION['qnty'] = $totalqunty += $quantity;

            array_push($pdtid, $row['id']);
            //print_r($_SESSION['pid'])=$pdtid;exit;
            ?>

													<tr>
														<td class="romove-item"><input type="checkbox" name="remove_code[]" value="<?php echo htmlentities($row['id']); ?>" /></td>
														<td class="cart-image">
															<a class="entry-thumbnail" href="detail.html">
																<img src="admin/productimages/<?php echo $row['id']; ?>/<?php echo $row['productImage1']; ?>" alt="" width="114" height="100">
															</a>
														</td>
														<td class="cart-product-name-info">
															<h4 class='cart-product-description'><a href="product-details.php?pid=<?php echo htmlentities($pd = $row['id']); ?>"><?php echo $row['productName'];

            $_SESSION['sid'] = $pd;
            ?></a></h4>


														</td>
														<td class="cart-product-quantity">
															<div class="quant-input">
																<!-- <div class="arrows">
																	<div class="arrow plus gradient"><span class="ir"><i class="icon fa fa-sort-asc"></i></span></div>
																	<div class="arrow minus gradient"><span class="ir"><i class="icon fa fa-sort-desc"></i></span></div>
																</div> -->
																<input type="text" value="<?php echo $_SESSION['cart'][$row['id']]['quantity']; ?>" name="quantity[<?php echo $row['id']; ?>]">

															</div>
														</td>
														<td class="cart-product-sub-total"><span class="cart-sub-total-price"><?php echo "£" . " " . $row['productPrice']; ?>.00</span></td>
														<td class="cart-product-grand-total"><span class="cart-grand-total-price"><?php echo ($_SESSION['cart'][$row['id']]['quantity'] * $row['productPrice']); ?>.00</span></td>
													</tr>

											<?php
}
    }
    $_SESSION['pid'] = $pdtid;
    ?>

										</tbody>
									</table>
						</div>
					</div>

					<div class="col-md-4 col-sm-12 cart-shopping-total">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>

										<div class="cart-grand-total">
											Grand Total<span class="inner-left-md"><?php echo $_SESSION['tp'] = "$totalprice" . ".00"; ?></span>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="cart-checkout-btn pull-right">
											<button type="submit" name="ordersubmit" class="btn btn-primary">PROCCED TO CHEKOUT</button>
										</div>
									</td>
								</tr>
							</tbody><!-- /tbody -->
						</table>
					<?php
} else {
    echo "Your Cart is empty";
}?>
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>

	<script src="assets/js/jquery-1.11.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
		<script src="assets/js/owl.carousel.min.js"></script>
	<script src="assets/js/bootstrap-slider.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
	<script src="assets/js/bootstrap-select.min.js"></script>
	<script src="assets/js/scripts.js"></script>
</body>

</html>