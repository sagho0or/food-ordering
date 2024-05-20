<?php 
session_start();
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <title>Order Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/rateit.css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
</head>
<body class="cnt-home">
    <header class="header-style-1">
        <?php include('includes/main-header.php');?>
    </header>

    <div class="body-content outer-top-xs">
        <div class="container">
            <div class="row inner-bottom-sm">
                <div class="shopping-cart">
                    <div class="col-md-12 col-sm-12 shopping-cart-table ">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="cart-romove item">#</th>
                                        <th class="cart-description item">Image</th>
                                        <th class="cart-product-name item">Product Name</th>
                                        <th class="cart-qty item">Quantity</th>
                                        <th class="cart-sub-total item">Price Per unit</th>
                                        <th class="cart-total item">Grandtotal</th>
                                        <th class="cart-total item">Payment Method</th>
                                        <th class="cart-description item">Order Date</th>
                                        <th class="cart-total last-item">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(isset($_POST['orderid'])) {
                                        $orderid = $_POST['orderid'];
                                        $query = mysqli_query($con, "SELECT products.productImage1 as pimg1, products.productName as pname, orders.productId as opid, orders.quantity as qty, products.productPrice as pprice, orders.paymentMethod as paym, orders.orderDate as odate, orders.id as orderid FROM orders JOIN products ON orders.productId = products.id WHERE orders.id = '$orderid' AND orders.paymentMethod IS NOT NULL");

                                        $cnt = 1;
                                        if(mysqli_num_rows($query) > 0) {
                                            while($row = mysqli_fetch_array($query)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt;?></td>
                                                    <td class="cart-image">
                                                        <a class="entry-thumbnail" href="detail.html">
                                                            <img src="admin/productimages/<?php echo $row['pname'];?>/<?php echo $row['pimg1'];?>" alt="" width="100" height="80">
                                                        </a>
                                                    </td>
                                                    <td class="cart-product-name-info">
                                                        <h4 class='cart-product-description'><a href="product-details.php?pid=<?php echo $row['opid'];?>">
                                                        <?php echo $row['pname'];?></a></h4>
                                                    </td>
                                                    <td class="cart-product-quantity"><?php echo $row['qty']; ?></td>
                                                    <td class="cart-product-sub-total"><?php echo $row['pprice']; ?></td>
                                                    <td class="cart-product-grand-total"><?php echo $row['qty']*$row['pprice'];?></td>
                                                    <td class="cart-product-sub-total"><?php echo $row['paym']; ?></td>
                                                    <td class="cart-product-sub-total"><?php echo $row['odate']; ?></td>
                                                    <td><a href="javascript:void(0);" onClick="popUpWindow('track-order.php?oid=<?php echo htmlentities($row['orderid']);?>');" title="Track order">Track</a></td>
                                                </tr>
                                                <?php $cnt++; } 
                                            } else { ?>
                                                <tr><td colspan="9">Order ID is invalid or not found</td></tr>
                                            <?php }
                                        } else { ?>
                                            <tr><td colspan="9">Order ID is required</td></tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
        <script src="assets/js/jquery.easing-1.3.min.js"></script>
        <script src="assets/js/bootstrap-slider.min.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        
    </body>
</html>
