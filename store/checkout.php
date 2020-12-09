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

//  Get All items in shopping cart
$get_items_sql = "SELECT si.id, si.name, si.price, si.artist_name, si.description, 
                 si.quantity, si.cover_image, st.sel_item_id, st.sel_item_qty FROM store_items AS si 
                 INNER JOIN  store_shopper_track AS st ON si.id = st.sel_item_id 
                 WHERE session_id = '$session_id';" or die($conn->error);

$get_items_res = $conn->query($get_items_sql) or die($conn->error);

// delete from shopping cart
if (isset($_GET['del']) && $_GET['del'] == 1 && isset($_GET['product_id'])) {
    $delete_item_sql = "DELETE FROM store_shopper_track WHERE session_id = '$session_id' AND sel_item_id =" . $_GET['product_id'];
    $conn->query($delete_item_sql) or die($conn->error);
}

if (isset($_POST['checkout']) && $_POST['checkout'] == '1') {

    $safe_name = $conn->real_escape_string($_POST['fullname']);
    $safe_email = $conn->real_escape_string($_POST['email']);
    $safe_address = $conn->real_escape_string($_POST['address']);
    $safe_postcode = $conn->real_escape_string($_POST['postcode']);
    $safe_city = $conn->real_escape_string($_POST['city']);
    $safe_state = $conn->real_escape_string($_POST['state']);
    $safe_telephone = $conn->real_escape_string($_POST['telephone']);
    $safe_total = $conn->real_escape_string($_POST['total']);


    // insert order information (Information about the customer)
    $create_order = "INSERT INTO store_orders (order_date, order_name, 
    order_address, order_city, order_state, order_zip, order_tel, order_email, item_total) 
    VALUES ( CURRENT_TIMESTAMP, '$safe_name', '$safe_address', '$safe_city', 
    '$safe_state', '$safe_postcode', '$safe_telephone', '$safe_email', '$safe_total');";
    $conn->query($create_order) or die($conn->error);

    //  decrease amount of stock
    $checkout_sql = "UPDATE store_items AS si INNER JOIN store_shopper_track AS st ON si.id = st.sel_item_id SET 
    si.quantity = (si.quantity - st.sel_item_qty) WHERE st.session_id = '$session_id';";
    $conn->query($checkout_sql) or die($conn->error);

    // remove all the items in cart for the current session id
    $clear_cart_sql = "DELETE FROM store_shopper_track WHERE session_id ='$session_id';";
    $conn->query($clear_cart_sql) or die($conn->error);

    // redirect to thank you page
    header('Location: thankyou.php');
    exit;
}

// header_section($activate_carousel, $title);
echo header_section(false, "Checkout - Retro Records");
?>
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
                                <td><img src="/images/<?php echo $item['cover_image'] ?>" alt="<?php echo $item['name'] ?>"></td>
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
                    </tfoot>
                </table>
                <section>
                    <h2 class="text-primary">Please fill the form</h2>
                    <form enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label class="required" for="fullname">Your Name</label>
                            <input type="text" id="fullname" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="address">Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="city">City</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="state">State</label>
                            <input type="text" id="state" name="state" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="telephone">Telephone</label>
                            <input type="text" id="telephone" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="name_card">Name on the card</label>
                            <input type="text" name="name_card" id="name_card" required>
                        </div>
                        <div class="form-group">
                            <label class="required" for="expiry">Expiry Date of Card</label>
                            <div>
                                <select id="expiry" class="custom-select" name="expiry_year" required>
                                    <option value="">YEAR</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2026">2027</option>
                                    <option value="2026">2028</option>
                                    <option value="2026">2029</option>
                                    <option value="2026">2030</option>
                                </select>
                                <select class="custom-select" name="expiry_month" required>
                                    <option value="">MONTH</option>
                                    <option value="1">01</option>
                                    <option value="2">02</option>
                                    <option value="3">03</option>
                                    <option value="4">04</option>
                                    <option value="5">05</option>
                                    <option value="6">06</option>
                                    <option value="7">07</option>
                                    <option value="8">08</option>
                                    <option value="9">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="total" value="<?php echo $total_price ?>">
                        <input type="hidden" name="checkout" value="1">
                        <input type="submit" name="submit" id="submit">
                    </form>
                </section>
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