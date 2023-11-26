<?php
function fetchPostData($createPostId) {
    include 'config_iskolarosa_db.php';
    $query = "SELECT * FROM create_post WHERE create_post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $createPostId); 
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        
        $postData = mysqli_fetch_assoc($result);
    } else {
        $postData = array(); 
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    return $postData;
}

if (isset($_GET['create_post_id'])) {
    $createPostId = $_GET['create_post_id'];

    // Perform a database query to fetch post data by $createPostId
    $postData = fetchPostData($createPostId);

    if (empty($postData)) {
        echo '<script>console.error("No data found for create_post_id: ' . $createPostId . '");</script>';
    } 
} else {
    echo json_encode(array());
    echo '<script>console.error("No create_post_id provided.");</script>';
}
?>
