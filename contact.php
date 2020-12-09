<?php
session_start();
include './functions.php';

connectToDB();


$get_albums_sql = "SELECT id, name, artist_name, description, price, quantity, created_at, cover_image from store_items ORDER BY RAND() LIMIT 2;";
$get_albums_res = $conn->query($get_albums_sql) or die($conn->error);

?>

<?php
// header_section($activate_carousel, $title);
echo header_section(true, 'Contact Us - Retro Records'); ?>
<main>
    <div class="content">
        <section class="col-main">
            <h2 class="text-primary">Please fill the form</h2>
            <form enctype="multipart/form-data">
                <div class="form-group">
                    <label class="required" for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="e.g. John Doe" required>
                </div>
                <div class="form-group">
                    <label class="required" for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="e.g. johndoe@email.com" required>
                </div>
                <div class="form-group">
                    <label class="required" for="artist">Artist</label>
                    <input type="text" id="artist" name="artist" placeholder="e.g. Michael Jackson" required>
                </div>
                <div class="form-group">
                    <label class="required" for="album_name">Name of album</label>
                    <input type="text" id="album_name" name="ablbum_name" placeholder="e.g. Off the Wall" required>
                </div>
                <input type="submit" name="submit" id="submit">
            </form>
        </section>
        <section class="col-side products">
            <h2 class="text-primary text-head">Featured Products</h2>
            <div>
                <?php while ($item = $get_albums_res->fetch_assoc()) : ?>
                    <figure class="product">
                        <a href="/store/product.php?product_id=<?php echo $item['id'] ?>">
                            <img src="images/<?php echo $item['cover_image'] ?>" width="100" height="100" alt="Got to GB vinyl album"></a>
                        <figcaption>
                            <a href="/store/product.php?product_id=<?php echo $item['id'] ?>"><?php echo $item['name'] ?></a>
                        </figcaption>
                    </figure>
                <?php endwhile; ?>
            </div>
        </section>
    </div>
</main>
<?php 
// close the database connection
$conn->close();
echo footer_section() ?>