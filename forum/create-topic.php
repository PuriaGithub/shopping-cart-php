<?php
include '../functions.php';
connectToDB();

$display_block = "";

// check request method if it is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ((!$_POST['topic_owner']) || (!$_POST['topic_title']) || (!$_POST['post_body']) || (!$_POST['topic_category_id'])) {
        header("Location: /forum");
        exit;
    }

    //create safe values for input into the database

    $safe_topic_owner = $conn->real_escape_string($_POST['topic_owner']);
    $safe_topic_title = $conn->real_escape_string($_POST['topic_title']);
    $safe_post_body = $conn->real_escape_string($_POST['post_body']);
    $safe_topic_category_id = $conn->real_escape_string($_POST['topic_category_id']);

    //create and issue the first query
    $add_topic_sql = "INSERT INTO forum_topics
                  (title, created_at, owner_email, category_id)
                  VALUES ('$safe_topic_title', now(),
                  '$safe_topic_owner', '$safe_topic_category_id')";

    $add_topic_res = $conn->query($add_topic_sql) or die($conn->error);

    //get the id of the last query
    $topic_id = $conn->insert_id;

    //create and issue the second query
    $add_post_sql = "INSERT INTO forum_posts
                 (body, created_at, owner_email, topic_id)
                 VALUES ('$safe_post_body', now(), '$safe_topic_owner', '$topic_id')";

    $add_post_res = $conn->query($add_post_sql) or die($conn->error);

    // redirect to topic page
    header("Location: /forum/show-topic.php?topic_id=$topic_id");
    exit;
    
    //close the database connection
    $conn->close();

    //create nice message for user
    $display_block = "<p>The <strong>" . $_POST["topic_title"] . "</strong>
    topic has been created.</p>";
//  if the request method is GET
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {

    // get all the categories
    $get_categories_sql = "SELECT id,name from forum_categories";
    $categories_res = $conn->query($get_categories_sql) or die($conn->error);

    $category_options = "";

    while ($category = $categories_res->fetch_assoc()) {
        $name = $category['name'];
        $id = $category['id'];

        $category_options .= "<option value='$id'>$name</option>";
    }
}



?>
<?php 
    // header_section($activate_carousel, $title);
    echo  header_section(false, 'Create New Topic - Retro Records'); ?>

<main>
    <div class="content">
        <section class="col-full">
            <h1 class="p-3">Create a new Topic</h1>
            <form class="form-group" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <p>
                    <label for="topic_owner">Email Address:</label><br />
                    <input class="form-control" type="email" id="topic_owner" name="topic_owner" size="40" maxlength="150" required="required" />
                </p>
                <p>
                    <label for="topic_category">Category:</label><br />
                    <select class="custom-select w-100" name="topic_category_id" id="topic_category">
                        <option value="">Please select a category</option>
                        <?php echo $category_options ?>
                    </select>
                </p>
                <p>
                    <label for="topic_title">Topic Title:</label><br />
                    <input class="form-control" type="text" id="topic_title" name="topic_title" size="40" maxlength="150" required="required" />
                </p>
                <p>
                    <label for="post_body">Post Body:</label><br />
                    <textarea class="form-control" id="post_body" name="post_body" rows="8" cols="40"></textarea></p>
                <input class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="Create">
            </form>
        </section>
    </div>
</main>


<?php echo  footer_section() ?>