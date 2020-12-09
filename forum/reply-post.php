<?php
include '../functions.php';
connectToDB();

// if the request method is not POST
if (!$_POST) {
    // if there is no post_id
    if (!isset($_GET['post_id'])) {
        header('Location: /forum');
        exit;
    }

    $safe_post_id = $conn->real_escape_string($_GET['post_id']);

    $verify_sql = "SELECT ft.id AS forum_id, ft.title AS forum_title from forum_posts AS fp
    LEFT JOIN forum_topics AS ft ON fp.topic_id = ft.id WHERE fp.id = '$safe_post_id'";

    $verify_res = $conn->query($verify_sql) or die($conn->error);

    // if there is no record redirect to the /forum
    if ($verify_res->num_rows < 1) {
        header('Location: /forum');
        exit;
    } else {
        while ($topic_info = $verify_res->fetch_array()) {
            $topic_id = $topic_info['forum_id'];
            $topic_title = $topic_info['forum_title'];
        }
    }

?>

    <?php 
    // header_section($activate_carousel, $title);
    echo  header_section(false, 'Reply to Post "' . $topic_title . '" - Retro Records'); ?>
    <main>
        <div class="content">
            <section class="col-full">
                <h2 class="p-3">Reply to Post " <?php echo $topic_title ?> "</h2>
                <form class="form-group" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <p>
                        <label for="topic_owner">Email Address:</label><br />
                        <input class="form-control" type="email" id="topic_owner" name="post_owner" size="40" maxlength="150" required="required" />
                    </p>
                    <p>
                        <label for="post_body">Post Body:</label><br />
                        <textarea class="form-control" id="post_body" name="post_body" rows="8" cols="40"></textarea></p>
                    <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                    <input class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="Create">
                </form>
            </section>
        </div>
    </main>
    <?php echo  footer_section() ?>

<?php
    // close the mysql connection
    $conn->close();
    // if the request method is POST
} else if ($_POST) {
    if ((!$_POST['topic_id']) || (!$_POST['post_owner']) || (!$_POST['post_body'])) {
        header('Location: forum.php');
        exit;
    }
    $safe_topic_id = $conn->real_escape_string($_POST['topic_id']);
    $safe_post_owner = $conn->real_escape_string($_POST['post_owner']);
    $safe_post_body = $conn->real_escape_string($_POST['post_body']);

    $insert_post_sql = "INSERT INTO forum_posts(owner_email, body, topic_id, created_at) VALUES('$safe_post_owner','$safe_post_body', '$safe_topic_id', now())";
    $insert_post_res = $conn->query($insert_post_sql) or die($conn->error);

    // close the database connection
    $conn->close();

    // redirect to the topic
    header("Location: show-topic.php?topic_id=" . $_POST['topic_id']);
    exit;
}


?>