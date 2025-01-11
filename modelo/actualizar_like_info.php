<?php
session_start();
include_once '../modelo/config.php';
if (isset($_GET['estadoId'])) {
    $estado_id = $_GET['estadoId'];
   

// Consulta SQL para obtener la cantidad de likes
$sqlLikesCount = "SELECT COUNT(*) AS likes_count FROM likes WHERE estado_id = ?";
$stmtLikesCount = mysqli_prepare($conn, $sqlLikesCount);
mysqli_stmt_bind_param($stmtLikesCount, "i", $estado_id);
mysqli_stmt_execute($stmtLikesCount);
$resultLikesCount = mysqli_stmt_get_result($stmtLikesCount);

if ($rowLikesCount = mysqli_fetch_assoc($resultLikesCount)) {
    $likes_count = $rowLikesCount["likes_count"];
} else {
    $likes_count = 0;
}

// Consulta SQL para obtener el nombre del último usuario que dio like
$sqlLastLiker = "SELECT users.fname AS liker_fname, users.lname AS liker_lname
                 FROM likes
                 LEFT JOIN users ON likes.user_id = users.user_id
                 WHERE likes.estado_id = ?
                 ORDER BY likes.id DESC
                 LIMIT 1";

$stmtLastLiker = mysqli_prepare($conn, $sqlLastLiker);
mysqli_stmt_bind_param($stmtLastLiker, "i", $estado_id);
mysqli_stmt_execute($stmtLastLiker);
$resultLastLiker = mysqli_stmt_get_result($stmtLastLiker);
}
if ($rowLastLiker = mysqli_fetch_assoc($resultLastLiker)) {
    $liker_fname = htmlspecialchars($rowLastLiker["liker_fname"]);
    $liker_lname = htmlspecialchars($rowLastLiker["liker_lname"]);
} else {
    $liker_fname = "";
    $liker_lname = "";
}


echo '<li class="hover-orange selected-orange"><i class="fa fa-heart"></i> ' . $likes_count . '</li>';

if (!empty($liker_fname) && !empty($liker_lname)) {
    echo '<li><span><b>' . $liker_fname . ' ' . $liker_lname . '</b></span></li>';
}

if ($likes_count > 1) {
    echo '<li><span>y ' . ($likes_count - 1) . ' mas..</span></li>';
}

?>