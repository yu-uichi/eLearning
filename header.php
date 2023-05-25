<?php
    require_once(__DIR__ . "/config.php");
?>

<header>
    <!--<nav class="navbar navbar-expand-lg navbar-light bg-light">-->
    <nav class="navbar navbar-expand-lg navbar-dark 
    <?php
        if($_SESSION['teacher']==TRUE){
            echo 'bg-info';
        }else{
            echo 'bg-primary';
        }
    ?>">
        <div class="container">
            <a class="navbar-brand" href="./">総合eラーニングアプリ(仮)</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <!--<li class="nav-item">
                        <a class="nav-link" href="index.php">総合トップ <span class="sr-only">(current)</span></a>
                    </li> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            アプリ
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="nursingRecord/index.php">実習記録</a>
                            <a class="dropdown-item" href="kokushi/index.php">国家試験対策</a>
                            <a class="dropdown-item" href="akatool_demo/index.php" target="_blank">関連図作成</a>
                        </div>
                    </li>
                    <?php
                    if ($_SESSION['teacher']==TRUE || $_SESSION['admin']==TRUE) {
                        echo '<li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ユーザ管理
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="user_list.php">学生管理</a>';
                        if ($_SESSION['admin']==TRUE) {
                            echo '<a class="dropdown-item" href="registration_teacher.php">教員作成</a>
                                </div>
                                </li>';
                        }
                    }
                    ?>
                </ul>

                <ul class="navbar-nav">
                <?php
                if (!isset($_SESSION['family_name'])) {
                    echo '<li class="nav-item">
                        <a class="nav-link active" href="login.php">ログイン</a>
                        </li>';
                    echo '<li class="nav-item">
                        <a class="nav-link active" href="registration_mail_form.php">新規登録</a>
                        </li>';
                }else{
                    echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .$_SESSION['family_name'] . 'さん (';
                    if ($_SESSION['admin']==TRUE) {
                        echo "管理者";
                    }else if($_SESSION['teacher']==TRUE) {
                        echo "教員";
                    }else{
                        echo "学生";
                    }
                    echo ')</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php">マイページ</a>';
                    echo '<div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">ログアウト</a>
                    </div>
                </li>';
                }
                ?>
                </ul>
            </div> <!-- collapse -->
        </div> <!-- container -->
    </nav>
</header>