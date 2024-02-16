<?php


$con = mysqli_connect("localhost", "root", "", "store_admin");
if (mysqli_connect_errno($con)) {
    die("Connect to MySQL failed: " . mysqli_connect_error());
}

//a safe method to recieve get data

function mypost($str)
{
    $val = !empty($_POST[$str]) ? $_POST[$str] : '123';
    return $val;
}

//$Email = mypost('email');
//$Password = mypost('password');
//$Name = mypost('username');
//$Payment_method = mypost('payment_method');
//$Shopping_address = mypost('shopping_method');

$str1 = $_SERVER["QUERY_STRING"];
$id = explode('=', $str1)[1];
$sql = "SELECT * FROM `user` WHERE user_id= '" . $id . "' ";
$query = mysqli_query($con, $sql);
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
//var_dump($result[0]['name']);

//if (isset($_POST['add'])) {
//
//    $sql = "UPDATE `user` SET `name`='YCHYCH' WHERE `user_id`=14";
//    mysqli_query($con,$sql);
//}


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
            //toolbar
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            //menu
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
            //update_button
            // const update_button = document.getElementById('button_update')
            // const personal_info = document.getElementsByClassName('info')
            // const confirm_button = document.getElementById('add')
            // console.log(typeof personal_info)
            // update_button.addEventListener('click', () => {
            //     //禁用
            //     if (personal_info[0].disabled) {
            //         Array.prototype.forEach.call(personal_info, function (info) {
            //             info.removeAttribute('disabled')
            //         });
            //         confirm_button.style.display = 'inline-block'
            //         personal_info[2].type = 'text'
            //     }
            // })
            //confirm_button
            // confirm_button.addEventListener('click', () => {
            //     //不禁用
            //     if (!personal_info[0].disabled) {
            //         Array.prototype.forEach.call(personal_info, function (info) {
            //             info.disabled = 'disabled'
            //         });
            //         confirm_button.style.display = 'none'
            //         personal_info[2].type = 'password'
            //     }
            // })

        });
    </script>
</head>
<body>
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
                                <div style="width: 80px;height: 30px"><a href="JavaScript:;" class="options">Profile</a></div>
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

        <div class="col-lg-4" id="big_profile">
            <img src="assets/user-filling.svg" alt="" width="100%" height="100%">
        </div>

        <div class="col-lg-4" id="profile_details">
            <form action="" method="post">
                <p>Username:
                    <input class="info" name="username" id="username" disabled value=
                        <?php
                        echo $result[0]['name']
                        ?>>
                </p>
                <p>Email:
                    <input class="info" name="email" id="email" disabled value=
                        <?php
                        echo $result[0]['email']
                        ?>>
                </p>
                <p>Password:
                    <input class="info" name="password" id="password" disabled value=
                        <?php
                        echo $result[0]['password']
                        ?>>
                </p>
                <p>Payment Method:
                    <input class="info" name="payment_method" id="payment_method" disabled value=
                        <?php
                    if ($result[0]['payment_method'] == 1) echo 'Wechat Pay';
                    else if ($result[0]['payment_method'] == 2) echo 'Alipay';
                    else if ($result[0]['payment_method'] == 3) echo 'Credit Card';
                        ?>>
                </p>
                <p>Shopping Address:
                    <input class="info" name="shopping_address" id="shopping_address" disabled value=
                        <?php
                        echo $result[0]['shipping_address']
                        ?>>
                </p>
<!---->
<!--                <button type="button" class="btn-lg button" id="button_update" style="margin-top: 10px;margin-left: -10px">-->
<!--                    Update-->
<!--                </button>-->
<!---->
<!--                <button type="submit" class="btn-lg button" id="add" name="add"-->
<!--                        style="margin-top: 10px;margin-left: 150px;display: none">-->
<!--                    Confirm-->
<!--                </button>-->
            </form>

        </div>


    </div>

</div>

</body>
</html>
