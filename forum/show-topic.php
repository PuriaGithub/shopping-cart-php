<?php
include '../functions.php';
connectToDB();

$display_block = "";
$topic_id = $_GET['topic_id'];

// if there is not a topic_id redirect to /forum
if (!isset($topic_id)) {
    header('Location: /forum');
    exit;
}

//  create safe value for use
$safe_topic_id = $conn->real_escape_string($topic_id);

// verify topic
$verify_topic_sql = "SELECT title from forum_topics WHERE id = '$topic_id'";

$verify_topic_res = $conn->query($verify_topic_sql) or die($conn->error);

if ($verify_topic_res->num_rows < 1) {
    $display_block .= "<h2>You have selected an invalid topic</h2><br />
                        <p>Please <a href='/forum'>try again</a></p>";

    echo $display_block;
    exit;
} else {
    while ($topic_info = $verify_topic_res->fetch_array()) {
        $topic_title = stripslashes($topic_info['title']);
    }
}

//  Gather posts
$get_posts_sql = "SELECT id AS post_id, body AS post_body, created_at AS post_created_at, owner_email AS post_owner from forum_posts
WHERE topic_id = '$safe_topic_id' ORDER BY post_created_at ASC;";

$get_posts_res = $conn->query($get_posts_sql) or die($conn->error);

$display_block .= <<<EOT
    <h1 class="p-3">Showing posts for the " $topic_title "</h1>
    <table class="table table-topic">
        <thead class="thead-dark">
            <tr>
                <th>Author</th>
                <th>Post</th>
            </tr>
        </thead>
        <tbody>
EOT;

while ($posts_info = $get_posts_res->fetch_array()) {
    $post_body = nl2br(stripcslashes($posts_info['post_body']));
    $post_id = $posts_info['post_id'];
    $post_created_at = $posts_info['post_created_at'];
    $post_owner = stripslashes($posts_info['post_owner']);


    $display_block .= <<<EOT
        <tr>
            <td>$post_owner<br/>
            created on: $post_created_at<br/>
            <a href="reply-post.php?post_id=$post_id">Reply to this post</a>
            </td>
            <td>$post_body</td>
        </tr>
EOT;

}


$display_block .= "</tbody>
</table>";

// close the database connection
$conn->close();

?>

<?php
// header_section($activate_carousel, $title);
echo header_section(false, "Posts for \" $topic_title \" - Retro Records"); ?>
<main>
    <div class="content">
        <section class="col-full">
            <div id="data">
                <?php echo $display_block; ?>
            </div>
        </section>
    </div>
</main>
<?php echo footer_section(); ?>
