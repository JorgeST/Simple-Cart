<?php

// A collection of sample products
$products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
];

$page = (isset($_GET['page'])) ? $_GET['page'] : 'home';

require_once 'Cart.php';

// Initialize cart object
$cart = new Cart();

// Shopping Cart Page
if ($page == 'cart') {

    $cartContents = '
	<div class="alert alert-warning">
		<i class="fa fa-info-circle"></i> There are no items in the cart.
	</div>';

    // Empty the cart
    if (isset($_POST['empty'])) {
        $cart->clear();
    }

    // Add item
    if (isset($_POST['add'])) {


        $cart->add($_POST['name'],$_POST['price']);

    }

    // Update item
    if (isset($_POST['update'])) {
        foreach ($products as $product) {
            if ($_POST['id'] == $product->id) {
                break;
            }
        }

        $cart->update($product->id, $_POST['qty'], [
            'price' => $product->price,
            'color' => (isset($_POST['color'])) ? $_POST['color'] : '',
        ]);
    }

    // Remove item
    if (isset($_POST['remove'])) {

        $cart->remove($_POST['name']);

    }

    if (!$cart->isEmpty()) {
        $allItems = $cart->getItems();

        $cartContents = '
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="col-md-7">Product</th>
					<th class="col-md-3 text-center">Quantity</th>
					<th class="col-md-2 text-right">Price</th>
				</tr>
			</thead>
			<tbody>';

        foreach ($allItems as $product) {


                $cartContents .= '
				<tr>
					<td>' . $product["name"] . '</td>
					<td class="text-center"><div class="form-group"><input type="number" value="' . $product['quantity'] . '" class="form-control quantity pull-left" style="width:100px"><div class="form-group"></button><button class="btn btn-danger btn-remove" data-name="' . $product["name"] . '"><i class="fa fa-trash"></i></button></div></div></td>
					<td class="text-right">$' . $product["price"] . '</td>
				</tr>';

        }

        $cartContents .= '
			</tbody>
		</table>

		<div class="text-right">
			<h3>Total:<br />$' . number_format($cart->getAttributeTotal('price'), 2, '.', ',') . '</h3>
		</div>

		<p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>ezyVet Developers Practical Task</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">


</head>

<body>
<style>
    body{margin-top:50px;margin-bottom:200px}
</style>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="?page=shop" class="navbar-brand">Shop</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li><a href="?page=cart" id="li-cart"><i class="fa fa-shopping-cart"></i> Cart</a></li>
            </ul>
        </div>
    </div>
</div>

<?php if ($page == 'cart'): ?>
    <div class="container">
        <h1>Cart</h1>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <?php echo $cartContents; ?>
                </div>
            </div>
        </div>
    </div>



<?php else: ?>
    <div class="container">
        <h1>Products</h1>
        <div class="row">
            <?php
            foreach ($products as $product) {

                echo '
					<div class="col-md-6">
						<h2>' . $product["name"] . '</h2>
						<h3>' . $product["price"] . '</h3>

						
					<form>
										                <input type="hidden" value= ' . $product["name"]  . ' class="product-name">


                                     <input type="hidden" value=' .  $product["price"] . ' class="product-price">';



								echo '
									<div class="form-group">
										<button class="btn btn-danger add-to-cart"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
									</div>
								</form>
							<div class="clearfix"></div>

					</div>';


            }
            ?>
        </div>
    </div>

<?php endif; ?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $('.add-to-cart').on('click', function(e){
            e.preventDefault();

            var $btn = $(this);
            var name = $btn.parent().parent().find('.product-name').val();
            var price = $btn.parent().parent().find('.product-price').val() || '';

            var $form = $('<form action="?page=cart" method="post" />').html('<input type="hidden" name="add" value=""><input type="hidden" name="name" value="' + name + '"><input type="hidden" name="price" value="' + price + '">');

            $('body').append($form);
            $form.submit();
        });

        $('.btn-remove').on('click', function(){
            var $btn = $(this);
            var name = $btn.attr('data-name');

            var $form = $('<form action="?page=cart" method="post" />').html('<input type="hidden" name="remove" value=""><input type="hidden" name="name" value="'+name+'">');

            $('body').append($form);
            $form.submit();
        });

        $('.btn-empty-cart').on('click', function(){
            var $form = $('<form action="?a=cart" method="post" />').html('<input type="hidden" name="empty" value="">');

            $('body').append($form);
            $form.submit();
        });
    });
</script>
</body>
</html>