<?php
    include "parts.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>User profile</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
</head>
<body>
    <?php printHeader(); ?>
    <main>
        <div class="profile-sidebar">
            <div class="inline-container">
                <img class="profile-picture" src="./images/avatar.png" />
            </div>
            <div class="inline-container">
                <span class="profile-info">Ime:</span>
                <span class="profile-info">Nikola Knezevic</span>
            </div>
            <div class="inline-container">
                <span class="profile-info">Username:</span>
                <span class="profile-info">knezevicdev</span>
            </div>
            <div class="inline-container">
                <span class="profile-info">Email:</span>
                <span class="profile-info">knezevicdev@gmail.com</span>
            </div>
        </div>
        <div class="content">
            <div class="tabs">
                <a class="tab active">Postavljena pitanja <i class="fa fa-question-circle" aria-hidden="true"></i></a>
                <a class="tab">Odgovori <i class="fa fa-rss" aria-hidden="true"></i></a>
                <a class="tab">Aktivnost <i class="fa fa-star" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="clear"></div>
    </main>
</body>
</html>