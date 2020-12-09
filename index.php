<?php
session_start();
include './functions.php';
connectToDB();

$get_albums_sql = "SELECT id, name, artist_name, description, price, quantity, created_at, cover_image from store_items ORDER BY RAND() LIMIT 2;";
$get_albums_res = $conn->query($get_albums_sql) or die($conn->error);

?>

<?php 
// header_section($activate_carousel, $title);
echo header_section(true, 'Welcome - Retro Records'); ?>
<main>
    <div class="content">
        <section class="col-main">
            <h2 class="text-primary">Welcome to RetroRecords Newtown</h2>

            <p>RetroRecords Newtown is a one-stop-shop for your retro music purchases.</p>

            <p>We source retro albums, rare LPs and used records for you, the music consumer and audiophile.</p>

            <h2 class="text-primary">Vinyl records sound better</h2>

            <p>It's pure nostalgia. The audio on vinyl records cannot be compressed as much as modern digital
                audio. It
                means that the audio from vinyl records is more precise and more natural. </p>

            <h2 class="text-primary">Find us</h2>

            <p>We are located at the top of King Street, just near the Marly Hotel. Phone us on
                <span class="text-primary">02&nbsp;9519&nbsp;1234.</span>
            </p>
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
// Close the database connection
$conn->close();

echo footer_section() ?>