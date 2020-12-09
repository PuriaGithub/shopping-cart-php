<?php
// start the session
session_start();
include '../functions.php';

// connect to database => it declares global $conn 
connectToDB();

// get the current session id
$session_id = $_COOKIE['PHPSESSID'];


//  si => store_items table 
//  st => store_shopper_track table

//  get All items in shopping cart 
$get_items_sql = "SELECT si.id, si.name, si.price, si.artist_name, si.description, 
                 si.quantity, si.cover_image, st.sel_item_id, st.sel_item_qty FROM store_items AS si 
                 INNER JOIN  store_shopper_track AS st ON si.id = st.sel_item_id 
                 WHERE session_id = '$session_id';" or die($conn->error);
                 
$get_items_res = $conn->query($get_items_sql) or die($conn->error);


// delete from shopping cart
if (isset($_GET['del']) && $_GET['del'] == 1 && isset($_GET['product_id'])) {
    $delete_item_sql = "DELETE FROM store_shopper_track WHERE session_id = '$session_id' AND sel_item_id =" . $_GET['product_id'];
    $conn->query($delete_item_sql) or die($conn->error);
    header('Location: /store');
    exit;
}

// header_section($activate_carousel, $title);
echo header_section(false, 'Shopping Cart - Retro Records');?>
<main>
    <div class="content">
        <section class="col-full">
            <h2 class="text-primary text-center">Shopping Cart</h2>

            <?php if ($get_items_res->num_rows >= 1) : ?>
                <table>
                    <caption style="visibility:hidden">Retro Albums</caption>
                    <thead>
                        <tr>
                            <th scope="col">Cover</th>
                            <th scope="col">Name of Album</th>
                            <th scope="col">Artist</th>
                            <th scope="col">Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_price = 0.00; ?>
                        <?php while ($item = $get_items_res->fetch_assoc()) : ?>

                            <tr>
                                <td><a href="/store/product.php?product_id=<?php echo $item['id'] ?>"><img src="/images/<?php echo $item['cover_image'] ?>" alt="<?php echo $item['name'] ?>"></a></td>
                                <td><?php echo $item['name'] ?></td>
                                <td><?php echo $item['artist_name'] ?></td>
                                <td>$<?php echo $item['price'] ?></td>
                                <td><?php echo $item['sel_item_qty'] ?></td>
                                <td>$<?php echo sprintf('%.02f', $item['price'] * $item['sel_item_qty']); ?></td>
                                <td><a class="mp-2" href="<?php echo $_SERVER['PHP_SELF'] . "?del=1&product_id=" . $item['sel_item_id'] ?>">Remove</a></td>
                            </tr>
                            <?php $total_price += ($item['price'] * $item['sel_item_qty']); ?>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="mp-3 text-center" colspan="3">Subtotal</td>
                            <td class="mp-3 text-center" colspan="4">$<?php echo sprintf('%.02f', $total_price); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="4"><a href="/store/checkout.php" class="custom-button">Checkout</a></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else : ?>
                <p class="text-center mp-3">
                    <i class="fa fa-shopping-cart fa-3x"></i>
                </p>
                <h2 class="text-center">You have no products added in your Shopping Cart</h2>
            <?php endif; ?>

        </section>
    </div>
</main>

<?php
// close the database connection
$conn->close();
echo footer_section() ?>