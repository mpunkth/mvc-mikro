<?php

use App\Controllers\Home;

?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1>Welcome <?php /** @var Home $name */
        echo htmlspecialchars(string: $name); ?></h1>
    <p>Hello from the view <?= htmlspecialchars($name); ?></p>

    <ul>
        <?php /** @var Home $colors */
        foreach ($colors as $color) :?>
            <li><?= htmlspecialchars($color); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
