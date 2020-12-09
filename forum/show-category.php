<?php
include '../functions.php';
connectToDB();


$category_id = $_POST['category_id'];

if (!isset($category_id) || $category_id <= 0) {
    //Get topics SQL when the request does not include a vaid category_id
    $get_topics_sql = "SELECT f.id, f.title, f.category_id, c.name AS category_name, c.id AS category_id,
                DATE_FORMAT(f.created_at,  '%b %e %Y') AS
                created_at, owner_email FROM forum_topics AS f
                LEFT JOIN forum_categories AS c 
                ON f.category_id = c.id
                ORDER BY f.created_at DESC";
} else {
    // Get SQL when the request includes a vaid category_id
    $get_topics_sql = "SELECT f.id, f.title, f.category_id, c.name AS category_name, c.id AS category_id,
                  DATE_FORMAT(f.created_at,  '%b %e %Y') AS
                  created_at, owner_email FROM forum_topics AS f
                  LEFT JOIN forum_categories AS c 
                  ON f.category_id = c.id WHERE f.category_id = $category_id
                  ORDER BY f.created_at DESC";
}

//Gather the topics
$get_topics_res = $conn->query($get_topics_sql) or die($conn->error);

if ($get_topics_res->num_rows < 1) {
    //there are no topics, so say so
    $display_block = "<p class='text-center'>No topics exist.</p>";
} else {
    // create the display table
    $display_block = <<<EOT

<table class="table table-forum">
    <thead class="thead-dark">
        <tr>
            <th>TITLE</th>
            <th class="text-center">Posts</th>
            <th class="text-center">Category</th>
        </tr>
    </thead>
    <tbody>
EOT;


    while ($topic_info = $get_topics_res->fetch_assoc()) {

        $topic_id = $topic_info['id'];

        $topic_title = stripslashes($topic_info['title']);

        $topic_create_time = $topic_info['created_at'];

        $topic_owner = stripslashes($topic_info['owner_email']);
        $topic_category = stripslashes($topic_info['category_name']);


        //get number of posts
        $get_num_posts_sql = "SELECT COUNT(id) AS post_count FROM
                            forum_posts WHERE topic_id = '" . $topic_id . "'";

        // get the number of posts
        $get_num_posts_res = $conn->query($get_num_posts_sql) or die($conn->error);

        $posts_info = $get_num_posts_res->fetch_assoc();
        $num_posts = $posts_info['post_count'];

        // add to display table
        $display_block .= <<<EOT
            <tr>
                <td><a href="show-topic.php?topic_id=$topic_id">

                <strong>$topic_title</strong></a><br/>

                Created on $topic_create_time by <strong>$topic_owner</strong></td>

                <td class="text-center">$num_posts</td>
                <td class="text-center"><span class='badge badge-success'>$topic_category</span></td>
            </tr>
EOT;
    }

    //close the database connection 
    $conn->close();

    //close up the table
    $display_block .= "
    </tbody>
    </table>";
}

echo $display_block;
