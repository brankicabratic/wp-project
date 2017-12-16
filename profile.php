<?php
    include "parts.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php printIncludes("Profil"); ?>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
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
            <div class="inline-container">
                <span class="profile-info">Smer:</span>
                <span class="profile-info">RN</span>
            </div>
            <div class="inline-container">
                <span class="profile-info">Godina upisa:</span>
                <span class="profile-info">2017</span>
            </div>
        </div>
        <div class="content">
            <div class="tabs">
                <a class="tab active" data-tab="questions">Postavljena pitanja <i class="fa fa-question-circle" aria-hidden="true"></i></a>
                <a class="tab" data-tab="answers">Odgovori <i class="fa fa-rss" aria-hidden="true"></i></a>
                <a class="tab" data-tab="activity">Aktivnost <i class="fa fa-star" aria-hidden="true"></i></a>
            </div>
            <div class="tabs-content">
                <div class="tab-content" id="questions">
                    <h2>Pitanja</h2>
                </div>
                <div class="tab-content" id="answers">
                    <h2>Odgovori</h2>
                </div>
                <div class="tab-content" id="activity">
                    <h2>Aktivnost</h2>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </main>
    <script>
        document.addEventListener( 'DOMContentLoaded', function () {
            var tabs = document.getElementsByClassName("tab");
            Array.prototype.forEach.call(tabs, function(element) {
                var tabsContent = document.getElementsByClassName("tab-content");
                element.addEventListener("click", function(e) {
                    Array.prototype.forEach.call(tabsContent, function(tempElem) {
                        tempElem.style.display = "none";
                    });
                    document.getElementById(element.dataset.tab).style.display = "block";
                });
            });
        }, false );
    </script>
</body>
</html>