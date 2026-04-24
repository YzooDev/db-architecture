<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  $title ?? "" ?></title>
</head>
<body>
    <?php
        $mdp = "admin";
        $hashmdp = password_hash($mdp, PASSWORD_DEFAULT);

        var_dump($mdp);
        var_dump($hashmdp);
    
    ?>
</body>
</html>