<?php
$con = mysqli_connect("localhost", "root", "", "store_admin");
if (mysqli_connect_errno($con)) {
    die("Connect to MySQL failed: " . mysqli_connect_error());
}

//a safe method to recieve get data
function myget($str)
{
    $val = !empty($_GET[$str]) ? $_GET[$str] : '';
    return $val;
}

function mypost($str)
{
    $val = !empty($_POST[$str]) ? $_POST[$str] : '';
    return $val;
}

$str = $_SERVER["QUERY_STRING"];
$id_user = substr(explode('=', $str)[1],0,1);

$sql = "SELECT * FROM `user` WHERE user_id= '" . $id_user . "' ";
$query = mysqli_query($con, $sql);
$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
//var_dump($result);

if (isset($_POST['update'])) {
    update($con, mypost('cname'), mypost('pname'),mypost('cid'));
}

function update($db_con, $name,$psw,$id)
{
    $sql = "UPDATE `user` SET `name` = '" . $name . "', `password` ='" . $psw . "' WHERE `user_id` = " . $id;
    $query = mysqli_query($db_con, $sql);
}

function myuser($con)
{
    $sql = "SELECT * FROM `User`";
    $query = mysqli_query($con, $sql);
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $arr = [];
    foreach ($result as $item) {
        array_push($arr, $item);
    }
//    var_dump($arr);
    foreach ($arr as $item) {
        echo '<tr>';
        echo '<td>' . $item['user_id'] . '</td>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>' . $item['email'] . '</td>';
        echo '<td>' . $item['password'] . '</td>';
        echo '<td>' ;
//        . $item['payment_method']. '
        if ($item['payment_method'] == 1)
            echo 'Wechat Pay';
        else if ($item['payment_method'] == 2)
            echo 'Alipay';
        else if ($item['payment_method'] == 3)
            echo 'Credit Card';
        echo '</td>';
        echo '<td>' . $item['shipping_address'] . '</td>';
        echo '<td>';
        echo '<a class="btn btn-info" href="javascript:show(' . $item["user_id"] . ');" style="margin-right: 2rem !important;">Update</a>';
        echo '<div id=' . $item["user_id"] . ' class="modal" style="width:500px; margin:auto;display:none">';
        echo '<div class="modal-content">';
        echo '<span class="close"><a href="javascript:hide(' . $item["user_id"] . ');">&times;</a></span>';
        echo '<form method="post">';
        //user name
        echo '<h4 class="modal-title" id="myModalLabel" style="margin: 40px 125px 20px 125px">Update the User name</h4>';
        echo '<input type="text" class="form-control" id="cname" name="cname" placeholder="Please input user name" style="margin-bottom:3rem !important; width:300px; margin-left: 100px;">';
        echo '<input value=' . $item["user_id"] . ' style="display: none;" id="cid" name="cid">';
        // psw
        echo '<h4 class="modal-title" id="myModalLabel" style="margin: 40px 125px 20px 125px">Update the Password</h4>';
        echo '<input type="text" class="form-control" id="pname" name="pname" placeholder="Please input new psw" style="margin-bottom:3rem !important; width:300px; margin-left: 100px;">';
        echo '<input value=' . $item["user_id"] . ' style="display: none;" id="cid" name="cid">';
//        //
//        echo '<h4 class="modal-title" id="myModalLabel" style="margin: 40px 125px 20px 125px">Update the User name</h4>';
//        echo '<input type="text" class="form-control" id="cname" name="cname" placeholder="Please input user name" style="margin-bottom:3rem !important; width:300px; margin-left: 100px;">';
//        echo '<input value=' . $item["user_id"] . ' style="display: none;" id="cid" name="cid">';
        echo '<button type="submit" class="btn btn-warning" id="update" name="update" value="update" style="margin-bottom:3rem !important;margin-left: 200px; !important;">Update</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';

    }

}

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
    <link rel="stylesheet" href="css/User.css">
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
                <a href="./index.php?param1=<?php echo $id_user ?>" class="sidebar_item">DashBoard</a>
                <a href="./category.php?param1=<?php echo $id_user ?>" class="sidebar_item">Category</a>
                <a href="./order.php?param1=<?php echo $id_user ?>" class="sidebar_item">Order</a>
                <a href="./product.php?param1=<?php echo $id_user ?>" class="sidebar_item">Product</a>
                <a href="./user.php?param1=<?php echo $id_user ?>" class="sidebar_item  a_active ">User</a>
                <a href="./coupon.php?param1=<?php echo $id_user ?>" class="sidebar_item">Coupon</a>
            </li>
        </ul>

        <ul class="list-unstyled CTAs">
            <li><a href="index.php?param1=<?php echo $id_user ?>" class="download">Back to HOME</a></li>
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
                                <div style="width: 80px;height: 30px"><a href="./profile.php?param1=<?php echo $id_user ?>" class="options">Profile</a></div>
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

        <div class="col-lg-8" id="contain">
            <table border="1" style="border-collapse: collapse;">
                <tbody>
                <tr>
                    <td style="width: 250px; height: 90px;font-weight: bold;">User ID</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> User Name</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> Email</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> Password</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> Payment Method</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> Shipping Method</td>
                    <td style="width: 250px; height: 90px;font-weight: bold"> Option</td>
                </tr>
                <?php
                myuser($con);
                ?>
                </tbody>
            </table>
        </div>

    </div>


</div>


<script>
    function show(id) {
        var el = document.getElementById(id)
        el.style.display = "block";
    }

    function hide(id) {
        var el = document.getElementById(id)
        el.style.display = "none";

    }
</script>

</div>

</body>
</html>
