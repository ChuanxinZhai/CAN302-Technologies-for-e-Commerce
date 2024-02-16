<?php


$con = mysqli_connect("localhost", "root", "", "store_admin");
if (mysqli_connect_errno($con)) {
    die("Connect to MySQL failed: " . mysqli_connect_error());
}

//a safe method to recieve get data
function myget($str) {
    $val = !empty($_GET[$str]) ? $_GET[$str] : '';
    return $val;
}

function mypost($str) {
    $val = !empty($_POST[$str]) ? $_POST[$str] : '';
    return $val;
}

$str = $_SERVER["QUERY_STRING"];
$id = explode('=',$str)[1];
$sql = "SELECT * FROM `user` WHERE user_id= '".$id."' ";
$query = mysqli_query($con, $sql);
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAN302 Lab3</title>
    <link rel="stylesheet" href="styles/bootstrap-337.min.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="css/Nav.css">
    <link rel="stylesheet" href="css/Dashboard_main.css">
    <link rel="stylesheet" href="css/Dashboard_Profile.css">
    <script src="js/jquery-331.min.js"></script>
    <script src="js/canvas.js"></script>
    <script src="js/bootstrap-337.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            const menu = document.getElementById('menu')
            menu.addEventListener('click', () => {
                let options = document.getElementById('options')
                const select = document.getElementById('select')

                if (options.style.display == 'none') {
                    options.style.display = 'block'
                    select.style.transform = "rotate(90deg)"
                } else {
                    options.style.display = 'none'
                    select.style.transform = "rotate(0deg)"
                }

            })
        });
    </script>

</head>
<body onload="draw()">
<div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Store Backstage</h3>
        </div>
        <ul class="list-unstyled components">
            <p>Web Store</p>
        </ul>
            <ul class="list-unstyled components">
                <li>
                    <a href="./index.php?param1=<?php echo $id ?>" class="sidebar_item a_active">DashBoard</a>
                    <a href="./category.php?param1=<?php echo $id ?>" class="sidebar_item">Category</a>
                    <a href="./order.php?param1=<?php echo $id ?>" class="sidebar_item">Order</a>
                    <a href="./product.php?param1=<?php echo $id ?>" class="sidebar_item">Product</a>
                    <a href="./user.php?param1=<?php echo $id ?>" class="sidebar_item ">User</a>
                    <a href="./coupon.php?param1=<?php echo $id ?>" class="sidebar_item">Coupon</a>
                </li>
            </ul>

        <ul class="list-unstyled CTAs">
            <li><a href="index.php?param1=<?php echo $id ?>" class="download">Back to HOME</a></li>
        </ul>
    </nav>

    <!-- Page Content Holder -->
    <div id="content" style="width: 90%">

        <nav class="navbar navbar-default" style="width: 100%">
            <div class="container-fluid">

                <div class="navbar-header">
                    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                        <span>Toggle Sidebar</span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <div id="user-info" class="navbar-right">

                        <div style="width: 40px;height: 40px;position: relative;margin-left: 10px;margin-right: 10px"
                             class="navbar-right">
                            <a href="#" class="navbar-right" id="menu" style="margin-left: 10px;display: inline-block">
                                <img src="assets/下拉.svg" id="select"
                                     style="width: 40px;height: 40px;margin-left: 10px;margin-top: 4px" alt="">
                            </a>
                            <div id="options">
                                <div style="width: 80px;height: 30px"><a href="profile.php?param1=<?php echo $id ?>" class="options">Profile</a></div>
                                <div style="width: 80px;height: 30px; border-top: 1px grey solid"><a href="./login.php" class="options">Log out</a></div>
                            </div>
                        </div>

                        <img src="assets/user-filling.svg" class="navbar-right" id="user-icon" alt="">
                        <p style="display: inline" class="navbar-right" id="user-name">
                            <?php
                            echo $result[0]['name']
                            ?>

                        </p>
                        <img src="assets/search.svg" class="navbar-right"
                             style="width: 40px;height: 40px;margin-right: 10px;margin-top: 4px" alt="">


                    </div>


                </div>

            </div>

        </nav>

        <div class="col-lg-3 col-md-4 col-sm-6 card">
            <span class="card_title">Total sales:</span>
            <span class="card_number">$800,600</span>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 card">
            <span class="card_title">Users:</span>
            <span class="card_number">179</span>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 card">
            <span class="card_title">Order Number:</span>
            <span class="card_number">1,234</span>
        </div>

        <canvas class="col-lg-12" id="drawing" style="border:1px solid #6d7fcc; margin-top:70px" width="1000px"
                height="300px"></canvas>


    </div>
</div>

</body>
</html>