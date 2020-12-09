<?php
include '../functions.php';
connectToDB();

// get all the categories
$get_categories_sql = "SELECT id,name from forum_categories";
$categories_res = $conn->query($get_categories_sql) or die($conn->error);

$category_options = '<option value="">Show All Categories</option>';

// loop through all the categories
while ($category = $categories_res->fetch_assoc()) {
    $name = $category['name'];
    $id = $category['id'];

    $category_options .= "<option value='$id'>$name</option>";
}

// gather all the topics
$get_topics_sql = "SELECT f.id, f.title, f.category_id, c.name AS category_name, c.id AS category_id,
                  DATE_FORMAT(f.created_at,  '%b %e %Y') AS
                  created_at, owner_email FROM forum_topics AS f
                  LEFT JOIN forum_categories AS c 
                  ON f.category_id = c.id
                  ORDER BY f.created_at DESC";


$get_topics_res = $conn->query($get_topics_sql) or die($conn->error);

if ($get_topics_res->num_rows < 1) {

    //there are no topics
    $display_block = "<p class='text-center'>No topics exist.</p>";
} else {

    // create the display data
    $display_block = <<<END_OF_TEXT

<table class="table table-forum">
    <thead>
        <tr>
            <th>TITLE</th>
            <th class="text-center">Posts</th>
            <th class="text-center">Category</th>
        </tr>
    </thead>
    <tbody>
END_OF_TEXT;

    // loop through all the topics
    while ($topic_info = $get_topics_res->fetch_assoc()) {

        $topic_id = $topic_info['id'];

        $topic_title = stripslashes($topic_info['title']);

        $topic_create_time = $topic_info['created_at'];

        $topic_owner = stripslashes($topic_info['owner_email']);
        $topic_category = stripslashes($topic_info['category_name']);


        //get number of posts

        $get_num_posts_sql = "SELECT COUNT(id) AS post_count FROM

                    forum_posts WHERE topic_id = '" . $topic_id . "'";

        // $get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql);
        $get_num_posts_res = $conn->query($get_num_posts_sql);

        if (!$get_num_posts_res === TRUE) {
            die("Error : " . $conn->error);
        }
        // or die(mysqli_error($mysqli));

        // mysqli_fetch_array($get_num_posts_res)
        while ($posts_info = $get_num_posts_res->fetch_assoc()) {
            $num_posts = $posts_info['post_count'];
        }

        //add to display

        $display_block .= <<<END_OF_TEXT

<tr>

       <td><a href="show-topic.php?topic_id=$topic_id">

       <strong>$topic_title</strong></a><br/>

       Created on $topic_create_time by <strong>$topic_owner</strong></td>

       <td class="text-center">$num_posts</td>
       <td class="text-center"><span class='badge badge-success'>$topic_category</span></td>

</tr>

END_OF_TEXT;
    }

    //close the database connection
    $conn->close();

    //close up the table
    $display_block .= "
    </tbody>
    </table>";
}

?>
<?php
// header_section($activate_carousel, $title);
echo header_section(false, "Forum - Retro Records"); ?>
<main>
    <div class="content">
        <section class="col-full">
            <a href="/forum/create-topic.php" class="custom-button btn-lg">Create new topic</a>
            <h2 class="text-center text-primary mp-1">Topics</h2>
            <select class="custom-select" name="category_id" id="category" onchange="loadData(this); return false;">
                <?php echo $category_options ?>
            </select>
            <div id="data">
                <?php echo $display_block; ?>
            </div>
        </section>
    </div>
</main>
<?php echo footer_section(); ?>