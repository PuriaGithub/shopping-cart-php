<?php
function connectToDB()
{
    $host = "localhost";
    $database = "retro";
    $username = "root";
    $password = "root";

    global $conn;

    // Create Connection 
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
function header_section($activate_carousel = false, $title = 'Retro Records')
{
   if($activate_carousel) {
        $carousel = '<header>
                <img src="/images/banner-yellow.png" alt="Retro Records Banner">
                <div>
                    <h1>Retro Records</h1>
                </div>
                <div class="contact-info">
                    <p><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:0295191234">02 9519 1234</a></p>
                    <p><i class="fa fa-envelope-o" aria-hidden="true"></i><a
                            href="mailto:info@retrorecordsnewtown.com.au">info@retrorecordsnewtown.com.au</a></p>
                </div>
            </header>';
   } else {
       $carousel = '';
   }
   

    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/all.css">
    <link rel="stylesheet" href="/src/font-awesome/css/font-awesome.min.css">
    <title>' . $title .'</title>
</head>
<body>
    <div id="container">
        <div id="navigation">
            <nav>
                <a href="/">
                    <img class="brand-logo" src="/images/logo.png" width="230" height="100"
                        alt="Retro Records Newtown logo" title="Your one-stop-shop for retro music purchases">
                </a>
                <ul>
                    <li><a href="/index.php"><i class="fa fa-home" aria-hidden="true"></i> HOME</a></li>
                    <li><a href="/albums.php"><i class="fa fa-music" aria-hidden="true"></i> RETRO ALBUMS</a></li>
                    <li><a href="/forum/"><i class="fa fa-comment"></i> Forum</a></li>
                    <li><a href="/store/"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                    <li><a href="/contact.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contact&nbsp;us</a></li>
                </ul>
            </nav>'.
            $carousel.
        '</div>';
}

function footer_section()
{
    return '
    </div>
    <footer>
        <ul>
            <li>&copy; Retro Records Newtown Pty Limited 2020. All Rights Reserved</li>
            <li><a class="text-primary" href="mailto:info@retrorecordsnewtown.com.au">info@retrorecordsnewtown.com.au</a></li>
            <li>
                <ul>
                    <li><a href="javascript:void(0)">Legals</a></li>
                    <li><a href="javascript:void(0)">Privacy Policy</a></li>
                </ul>
            </li>
        </ul>
        <ul class="social-media">
            <li><a href="javascript:void(0)"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
            <li><a href="javascript:void(0)"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="javascript:void(0)"><i class="fa fa-youtube-square" aria-hidden="true"></i></a></li>
        </ul>
    </footer>
    <script>
        function loadData(e) {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                document.getElementById("data").innerHTML = this.responseText;
                }
            };

            xhttp.open("POST", "show-category.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("category_id=".concat(e.selectedIndex));
        }
    </script>
    </body>
</html>';
}
