<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Step.7 nginx + PHP5 + MySQL + xdebug + redis</title>
    <link rel="stylesheet" href="/main.css">
</head>
<body>
    <div class="container">
        <h1 class="title">nginx + PHP5 + MySQL + xdebug + redis</h1>
        <img src="/img.jpg" alt="" class="thumbnail" />

        <?php
        $mysql = new mysqli($_ENV['DATABASE_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);

        if (!$mysql) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

        $sql = "INSERT INTO hoges(created_at) VALUES('" . date('Y-m-d H:i:s') . "')";

        $result = $mysql->query($sql);

        $sql = "SELECT * FROM hoges ORDER BY hoge_id desc limit 1";

        $result = $mysql->query($sql)->fetch_row();

        echo '<pre class="log">';
        var_dump($result);
        echo '</pre>';

        mysqli_close($mysql);

        ///////////////////////////////////
        // REDIS
        ///////////////////////////////////

        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        $redis->auth($_ENV['REDIS_PASSWORD']);
        echo '<pre class="log">';
        echo 'Redis接続' . PHP_EOL;
        echo $redis->ping() . PHP_EOL;

        // string_keyというkeyにhugaという値をセット
        $redis->set('string_key', 'huga');
        // 値を取得する
        $value = $redis->get('string_key');
        // 表示
        echo $value . PHP_EOL; // huga

        // lPushは先頭、rPushは末尾に値をpush
        $redis->rPush('list_key', 'a');
        $redis->rPush('list_key', 'b');
        $redis->lPush('list_key', 'c');
        $redis->lPush('list_key', 'd');

        // 値をすべて取得する -1はすべて
        $value = $redis->lRange('list_key', 0, -1);

        // 表示
        var_dump($value);
        echo '</pre>';


        // メール
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        
        // Load Composer's autoloader
        require 'vendor/autoload.php';
        
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer;
        
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'maildev';  // Specify main and backup SMTP servers
            // $mail->SMTPAuth   = false;                                   // Enable SMTP authentication
            // $mail->Username   = 'user@example.com';                     // SMTP username
            // $mail->Password   = 'secret';                               // SMTP password
            $mail->SMTPSecure = false;                                  // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPAutoTLS = false;
            $mail->Port       = 25;                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');
        
            // Attachments
            $mail->addAttachment('/var/www/html/index.php');         // Add attachments
            $mail->addAttachment('/var/www/html/img.jpg', 'img.jpg');    // Optional name
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


        phpinfo();
        ?>
    </div>
    <script src="/main.js"></script>
</body>
</html>
