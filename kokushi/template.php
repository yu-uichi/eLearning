<?php
//問題のリスト

session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

require_once(__DIR__ . "/functions.php");

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $APP_TITLE_EXPLAIN . $APP_TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>

<?php
    require_once(__DIR__ . "/header.php");
?>

<div class="container mt-3">
    <div class="row">
        <h3>
        テンプレート
        </h3>
        <div class="container mt-3">




        </div>  <!-- container -->
    </div> <!-- row -->
</div> <!-- container -->

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>