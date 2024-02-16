<?php
//open database by PDO

$dbms = 'mysql';     //DBMS type
$host = 'localhost'; //Host name
$dbName = 'store_admin';    //database name
$user = 'root';      //database user
$pass = '';          //database password
$dsn = "$dbms:host=$host;dbname=$dbName";

try {
    $con = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
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


if (isset($_POST['add'])) {
    add($con, mypost('name'));
} else if (isset($_POST['delete'])) {
    delete($con, mypost('did'));
} else if (isset($_POST['update'])) {
    update($con, mypost('cname'), mypost('cid'));
}


function delete($db_con, $id)
{
    $sql = "DELETE FROM `category` WHERE category_id = $id ";
    $query = $db_con->query($sql);
}

function add($db_con, $name){
    $s = "SELECT IF(MAX(category_id) IS NULL, 0, MAX(category_id)) AS maxid FROM category";
    $q = $db_con->query($s);
    $id = 1;
    foreach($q as $row){
        $id = $id + $row["maxid"];
    }

    $sql = "INSERT INTO `category` (`category_id`,`name`) VALUES ('".$id."','".$name."')";
    $query = $db_con->query($sql);
}

function update($db_con, $name, $id)
{
    $sql = "UPDATE `category` SET `name` = '" . $name . "' WHERE `category_id` = $id ";
    $query = $db_con->query($sql);
}

function mycategory($db_con,$user_id){
    $sql = "SELECT * FROM category";
    $query = $db_con->query($sql);
    $index=1;
    foreach($query as $row){
        echo '<tr>';
        echo '<td scope="row">'.$index.'</td>';
        echo '<td>'.$row["name"].'</td>';
        echo '<td>';
        echo '<div style="margin-right: 2rem !important; display:inline-block; text-align:center;">';
        echo '<a class="btn btn-info" href="javascript:show('.$row["category_id"].');">Update</a>';
        echo '<div id='.$row["category_id"].' class="modal" style="width:500px; margin:auto;">';
        echo '<div class="modal-content">';
        echo '<span class="close"><a href="javascript:hide('.$row["category_id"].');">&times;</a></span>';
        echo '<form method="post">';
        echo '<h4 class="modal-title" id="myModalLabel" style="margin-bottom:3rem !important; margin-top:20px">Update the category name</h4>';
        echo '<input type="text" class="form-control" id="cname" name="cname" placeholder="Please input category name" style="margin-bottom:3rem !important; width:300px; margin-left: 100px;">';
        echo '<input value='.$row["category_id"].' style="display: none;" id="cid" name="cid">';
        echo '<button type="submit" class="btn btn-warning" id="update" name="update" value="update" style="margin-bottom:3rem !important;">Update</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div style="display:inline-block; text-align:center;">';
        echo '<form method="post">';
        echo '<input value='.$row["category_id"].' style="display:none;" id="did" name="did">';
        echo '<button type="submit" class="btn btn-danger" id="delete" name="delete" value="delete" onclick="return del();">Delete</button>';
        echo '</form>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        $index=$index+1;

    }

}

$str = $_SERVER["QUERY_STRING"];
$user_id = explode('=',$str)[1];
$sql = "SELECT user.name as name FROM `user` WHERE user_id= '" . $user_id . "' ";
//$query2 = mysqli_query($con,$sql);
$query = $con->query($sql);
$result1 = $query->fetch()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Srore admin</title>
    <link rel="stylesheet" href="styles/bootstrap-337.min.css">
    <link rel="stylesheet" href="css/Nav.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <script src="js/jquery-331.min.js"></script>
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
                <a href="./index.php?param1=<?php echo $user_id ?>" class="sidebar_item ">DashBoard</a>
                <a href="./category.php?param1=<?php echo $user_id ?>" class="sidebar_item a_active">Category</a>
                <a href="./order.php?param1=<?php echo $user_id ?>" class="sidebar_item">Order</a>
                <a href="./product.php?param1=<?php echo $user_id ?>" class="sidebar_item">Product</a>
                <a href="./user.php?param1=<?php echo $user_id ?>" class="sidebar_item ">User</a>
                <a href="./coupon.php?param1=<?php echo $user_id ?>" class="sidebar_item">Coupon</a>
            </li>
        </ul>

        <ul class="list-unstyled CTAs">
            <li><a href="index.php?param1=<?php echo $user_id ?>" class="download">Back to HOME</a></li>
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
                                <div style="width: 80px;height: 30px"><a href="./profile.php?param1=<?php echo $user_id ?>"
                                                                         class="options">Profile</a></div>
                                <div style="width: 80px;height: 30px; border-top: 1px grey solid"><a href="./login.php"
                                                                                                     class="options">Log
                                        out</a></div>
                            </div>
                        </div>

                        <img src="assets/user-filling.svg" class="navbar-right" id="user-icon" alt="">
                        <p style="display: inline" class="navbar-right" id="user-name">
                            <?php
                            echo $result1
                            ?>
                        </p>
                        <img src="assets/search.svg" class="navbar-right"
                             style="width: 40px;height: 40px;margin-right: 10px;margin-top: 4px" alt="">
                    </div>


                </div>

            </div>
        </nav>


        <div class="container">
            <form class="form-inline" role="form" style='margin-bottom:2rem !important;' action="" method="post">
                <input type="text" class="form-control" id="name" name="name" placeholder="Please input category name"
                       style='width:300px'>
                <button type="submit" class="btn btn-warning" style='margin-left: 1rem !important;' id="add" name="add"
                        value="add">Add
                </button>
            </form>

            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th scope="col" style='text-align: center;'>ID</th>
                    <th scope="col" style='text-align: center;'>Category name</th>
                    <th scope="col" style='text-align: center;'>Option</th>

                </tr>
                </thead>
                <tbody>
                <?php
                mycategory($con,$user_id);
                ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script language="javascript">

    function del() {
        if (confirm("Are you sure to delete?")) {
            return true;
        } else {
            return false;
        }
    }

    function show(id) {
        var el = document.getElementById(id)
        el.style.display = "block";
    }

    function hide(id) {
        var el = document.getElementById(id)
        el.style.display = "none";

    }

</script>


</body>
</html>