<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1-success</title>
    </head>
    <body>
        <h2>好きな映像作品</h2>
        <h3>映画、ドラマ、youtubeなど何でも好きな映像作品をコメントしてください！</h3><br>
        <form action="" method="post">
            <p>【投稿フォーム】</p>
            <input type="search" name="name" placeholder="名前"><br>
            <input type="search" name="comment" placeholder="コメント"><br>
            <input type="search" name="pass" placeholder="パスワードの設定">
            <input type="submit" name="submit"><br>
            <p>【削除フォーム】</p>
            <input type="number" name="delete" placeholder="投稿番号"><br>
            <input type="search" name="de_pass" placeholder="パスワード">
            <input type="submit" name="submit" value = "削除"><br>
            <p>【編集フォーム】</p>
            <input type="number" name="edit" placeholder="投稿番号"><br>
            <input type="search" name="ed_pass" placeholder="パスワード">
            <input type="submit" name="submit" value = "編集"><br><br>
            <hr width=800 align="left" color=#3366ff>
        <?php
            error_reporting(E_ALL & ~E_NOTICE);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $time = date('Y/m/d H:i:s');
            $pass = $_POST["pass"];
            $delete = $_POST["delete"];
            $de_pass = $_POST["de_pass"];
            $edit = $_POST["edit"];
            $ed_pass = $_POST["ed_pass"];
        ?>
        </form>
        <?php
            error_reporting(E_ALL & ~E_NOTICE);
            // DB接続設定
            $dsn = 'データベース名';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            if (!empty($name)&empty($edit)) {
                //テーブル作成
                $sql = "CREATE TABLE IF NOT EXISTS tbtest"
                ." ("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name char(32),"
                . "pass char(32),"
                . "comment TEXT,"
                . "time TEXT"
                .");";
                $stmt = $pdo->query($sql);
                if (!empty($pass)) {
                    //データ入力
                    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $sql -> execute();
                } elseif (!empty($name)&empty($pass)&empty($delete)&empty($edit)) {
                    echo "パスワードを設定してください<br><br>";
                }
            }
            if (!empty($delete)&!empty($de_pass)) {
                $id = $delete;
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                    }
                if ($de_pass == $row['pass']) {
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo "パスワードが誤っています<br><br>";
                }
            } elseif (!empty($delete)&empty($de_pass)) {
                echo "パスワードを入力してください<br><br>";
            }
            if (!empty($edit)&!empty($ed_pass)) {
                $id = $edit; //変更する投稿番号
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。
                $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                    }
                if ($ed_pass == $row['pass']) {
                    $name = "$name";
                    $comment = "$comment"; //変更したい名前、変更したいコメントは自分で決めること
                    $time = date('Y/m/d H:i:s');
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment,time=:time WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':time', $time, PDO::PARAM_STR);
                    $stmt->execute();
                } else {
                    echo"パスワードが誤っています<br><br>";
                }
            } elseif (!empty($edit)&empty($ed_pass)) {
                echo "パスワードを入力してください<br><br>" ; 
            }
            echo "【投稿一覧】<br><br>";
            //データ表示
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].'.';
                echo $row['name'].' [';
                echo $row['comment'].'] ';
                echo $row['time'].'<br>';
                //echo $row['pass'].'<br>';
            }     
        ?>
    </body>
</html>