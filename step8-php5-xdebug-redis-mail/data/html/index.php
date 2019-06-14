<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Step.8 nginx + PHP5 + MySQL + xdebug + redis + Mail その１</title>
    <link rel="stylesheet" href="/main.css">
</head>
<body>
    <div class="container">
        <h1 class="title">nginx + PHP5 + MySQL + xdebug + redis + Mail その１</h1>
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


        //////////////////////////////////////////////////////
        // メール
        //////////////////////////////////////////////////////
        // https://github.com/PHPMailer/PHPMailer
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        
        // Load Composer's autoloader
        require 'vendor/autoload.php';
        
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer;
        
        try {
            //Server settings
            echo '<pre class="log">';
            echo 'SMTPでメール配信' . PHP_EOL;

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
            $mail->setFrom('from@example.com');
            $mail->addAddress('smtp@example.net');     // Add a recipient
        
            // Attachments
            // $mail->addAttachment('/var/www/html/index.php');         // Add attachments
            $mail->addAttachment('/var/www/html/img.jpg', 'img.jpg');    // Optional name
        
            // // Content
            // $mail->isHTML(true);                                  // Set email format to HTML
            // $mail->Subject = 'Here is the subject';
            // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->Subject = mb_encode_mimeheader('日本語サブジェクト(SMTP)');
            $mail->Encoding = '7bit';
            $mail->CharSet ='ISO-2022-JP';

$mailBody =<<< EOL
日本語のメールのテストです。

改行も問題ないと思います。

SMTPで配信しています。
EOL;

            $mail->Body = mb_convert_encoding($mailBody, "JIS", "UTF-8");
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo '</pre>';

        echo '<pre class="log">';
        echo 'sendmailでメール配信' . PHP_EOL;
        $mail2 = new PHPMailer;
        $mail2->isSendmail();
            //Recipients
            $mail2->setFrom('from@example.com');
            $mail2->addAddress('sendmail@example.net');     // Add a recipient

            $mail2->Subject = mb_encode_mimeheader('日本語サブジェクト(sendmail)');
            $mail2->Encoding = '7bit';
            $mail2->CharSet ='ISO-2022-JP';

$mailBody =<<< EOL
日本語のメールのテストです。

改行も問題ないと思います。

sendmailで配信しています。
EOL;

            $mail2->Body = mb_convert_encoding($mailBody, "JIS", "UTF-8");
        
            $mail2->send();
            echo 'Message has been sent';
        echo '</pre>';

        phpinfo();
        ?>
    </div>
    <script src="/main.js"></script>
</body>
</html>
