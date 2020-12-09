<?php
// start the session
session_start();
// including helper functions
include '../functions.php';
// connecting to the database => it declares global $conn 
connectToDB();

//  if there is no product_id redirect to albums.php
if (empty($_GET['product_id'])) {
    header('Location: /albums.php');
    exit;
}

$product_id = $conn->real_escape_string($_GET['product_id']);
// get the product from the database
$get_album_sql = "SELECT id, name, artist_name, description, price, quantity, created_at, cover_image from
 store_items WHERE id = '$product_id';";
// execute the get_album_sql query
$get_album_res = $conn->query($get_album_sql) or die($conn->error);
// fetch the result as a associative array
$item = $get_album_res->fetch_assoc();

// error message to show in cases that there is an error
$error_message = '';

// check to ensure that if users want to add a product to their cart
if (isset($_POST['add']) && ($_POST['add'] == '1') && isset($_POST['qty'])) {
    $session_id = $_COOKIE['PHPSESSID'];
    $item_id = $conn->real_escape_string($_POST['product_id']);
    $item_qty = $conn->real_escape_string($_POST['qty']);

    $find_item_sql = "SELECT * from store_shopper_track where sel_item_id = $item_id AND session_id = '$session_id'";
    $find_item_res = $conn->query($find_item_sql) or die($conn->error);
    $find_item_assoc = $find_item_res->fetch_assoc();


    // Checking if we already have selected item in cart to decide about insert new record or update the current one
    if ($find_item_res->num_rows == 1) {
        $update_item_sql = "UPDATE store_shopper_track AS st INNER JOIN store_items AS si
        ON si.id = st.sel_item_id 
        SET st.sel_item_qty = (st.sel_item_qty + $item_qty) WHERE st.sel_item_id = '$item_id' 
        AND session_id = '$session_id' AND si.quantity >= (st.sel_item_qty + $item_qty);";
        
        $conn->query($update_item_sql) or die($conn->error);
        // check if affected rows equals to 0 - decide to showing the error message
        if($conn->affected_rows == 0){
            $error_message = "<p>We only have a limited number of this product,<br /> 
            You can order maximum ". $item['quantity'] . " item, and you are already have some products in your cart</p>";
        } else {
        header('Location: /store');
        exit;
        }

    } else {
        $insert_item_sql = "INSERT INTO store_shopper_track (session_id, sel_item_id, sel_item_qty ) 
        VALUES ('$session_id', '$item_id', '$item_qty' );";
        $conn->query($insert_item_sql) or die($conn->error);
        header('Location: /store');
        exit;
    }

}


// header_section($activate_carousel, $title);
echo header_section(false, $item['name'] . ' - Retro Records');
?>
<main>
    <div class="content">
        <section class="col-full">
            <div class="single-product">
                <div class="single-product-cover">
                    <p><img src="/images/<?php echo $item['cover_image'] ?>" alt="<?php echo $item['name'] ?>"></p>
                </div>
                <form class="single-product-details" method="POST">
                    <h1><?php echo $item['name'] ?></h1>
                    <p class="single-product-price"><?php echo '$' . $item['price'] ?></p>
                    <p><?php echo $item['artist_name'] ?></p>
                    <?php if ($item['quantity'] > 1) : ?>
                        <label for="qty">Quantity:</label>
                        <select name="qty" id="qty">
                            <?php $i = 0;
                            $quantity = $item['quantity'];
                            while ($i < $quantity) {
                                ++$i;
                                echo "<option value='$i'>$i</option> \n";
                            }
                            ?>
                        </select>
                        <input type="submit" name="submit" value="Add to cart" class="single-product-btn">
                    <?php else : ?>
                        <p class="text-center text-primary">Out of stock</p>
                    <?php endif ?>
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="hidden" name="add" value="1">
                    <?php echo "<p class='text-primary'>" . $error_message . "</p>" ?>
                </form>
            </div>

        </section>
    </div>
</main>

<?php
// close the database connection
$conn->close();

echo footer_section() ?>