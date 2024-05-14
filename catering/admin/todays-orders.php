<?php
session_start();
include('include/config.php');
$st = 'Completed';
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    date_default_timezone_set('Europe/London');
    $currentTime = date('d-m-Y h:i:s A', time());
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin| Delivered Orders</title>
        <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="css/theme.css" rel="stylesheet">
        <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    </head>

    <body>
        <?php include('include/header.php'); ?>
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <?php include('include/sidebar.php'); ?>
                    <div class="span9">
                        <div class="content">
                            <div class="module">
                                <div class="module-head">
                                    <h3>Today Orders</h3>
                                </div>
                                <div class="module-body table">
                                    <br />

                                    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display table-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order Id</th>
                                                <th>Products</th>
                                                <th>Total Quantity</th>
                                                <th>Total Amount</th>
                                                <th>Order Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
											$f1 = "00:00:00";
											$from = date('Y-m-d') . " " . $f1;
											$t1 = "23:59:59";
											$to = date('Y-m-d') . " " . $t1;
                                            $query = mysqli_query($con, "SELECT orders.id AS orderId, orderDetails.productName AS productname, orderDetails.productQuantity AS quantity, orders.orderDate AS orderdate, orderDetails.productPrice AS productprice FROM orders JOIN orderDetails ON orderDetails.orderId=orders.orderId WHERE orders.orderDate Between '$from' and '$to' ORDER BY orders.id DESC");
                                            $orderData = [];
                                            while ($row = mysqli_fetch_array($query)) {
                                                $orderId = $row['orderId'];
                                                if (!isset($orderData[$orderId])) {
                                                    $orderData[$orderId] = [
                                                        'orderDate' => $row['orderdate'],
                                                        'products' => [],
                                                        'totalQuantity' => 0,
                                                        'totalAmount' => 0
                                                    ];
                                                }
                                                $orderData[$orderId]['products'][] = $row['productname'] . ' x ' . $row['quantity'];
                                                $orderData[$orderId]['totalQuantity'] += $row['quantity'];
                                                $orderData[$orderId]['totalAmount'] += $row['quantity'] * $row['productprice'];
                                            }

                                            $cnt = 1;
                                            foreach ($orderData as $id => $info) {
                                                ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($id); ?></td>
                                                    <td><?php echo implode(", ", $info['products']); ?></td>
                                                    <td><?php echo htmlentities($info['totalQuantity']); ?></td>
                                                    <td><?php echo htmlentities($info['totalAmount']); ?></td>
                                                    <td><?php echo htmlentities($info['orderDate']); ?></td>
                                                    <td><a href="updateorder.php?oid=<?php echo htmlentities($id); ?>" title="Update order" target="_blank"><i class="icon-edit"></i></a></td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
		<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
		<script src="scripts/datatables/jquery.dataTables.js"></script>
		<script>
			$(document).ready(function() {
				$('.datatable-1').dataTable();
				$('.dataTables_paginate').addClass("btn-group datatable-pagination");
				$('.dataTables_paginate > a').wrapInner('<span />');
				$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
				$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
			});
		</script>
	</body>
<?php } ?>