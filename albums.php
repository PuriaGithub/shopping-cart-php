<?php
session_start();
include './functions.php';
connectToDB();

$get_albums_sql = "SELECT id, name, artist_name, description, price, quantity, created_at, cover_image from store_items;";
$get_albums_res = $conn->query($get_albums_sql) or die($conn->error);

?>


<?php
// header_section($activate_carousel, $title);
echo header_section(false, "Albums - Retro Records"); ?>
<main>
    <div class="content">
        <section class="col-full">
            <h2 class="text-primary text-center">List of Products</h2>
            <table class="table-products">
                <caption>Retro Albums</caption>
                <thead>
                    <tr>
                        <th scope="col">Cover</th>
                        <th scope="col">Name of Album</th>
                        <th scope="col">Artist</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $get_albums_res->fetch_assoc()) : ?>
                        <tr>
                            <td><a href="/store/product.php?product_id=<?php echo $item['id'] ?>"><img src="/images/<?php echo $item['cover_image'] ?>" alt="<?php echo $item['name'] ?>"></a></td>
                            <td><a href="/store/product.php?product_id=<?php echo $item['id'] ?>"><?php echo $item['name'] ?></a></td>
                            <td><?php echo $item['artist_name'] ?></td>
                            <td><?php echo $item['price'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</main>
<?php
// close the database connection
$conn->close();
echo footer_section();?>