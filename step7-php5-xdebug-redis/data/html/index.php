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

        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        $redis->auth($_ENV['REDIS_PASSWORD']);
        echo '<pre class="log">';
        echo $redis->ping();
        echo '</pre>';

        phpinfo();
        ?>
    </div>
    <script src="/main.js"></script>
</body>
</html>
