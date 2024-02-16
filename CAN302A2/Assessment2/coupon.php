<?php
//open database by PDO

$dbms = 'mysql'; //DBMS type
$host = 'localhost'; //Host name
$dbName = 'store_admin'; //database name
$user = 'root'; //database user
$pass = ''; //database password
$dsn = "$dbms:host=$host;dbname=$dbName";

try {
    $con = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
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

//receive query parameters.
$coupon_id = mypost('coupon_id');
$code = mypost('code');
$discount = mypost('discount_percentage');
$ex_date = mypost('expiration_date');
$isUsed = mypost('isUsed');
$str = $_SERVER["QUERY_STRING"];
$id_user = explode('=',$str)[1];
$sql = "SELECT user.name as name FROM `user` WHERE user_id= '" . $id_user . "' ";
//$query2 = mysqli_query($con,$sql);
$query = $con->query($sql);
$result1 = $query->fetch()[0];


//Update voucher
if (isset($_POST['new_discount'], $_POST['new_voucher-code'],$_POST['new_user'],$_POST['new_expiration-date'],$_POST['coupon_id'])) {

    $new_vcode = $_POST['new_voucher-code'];
    $coupon_id = $_POST['coupon_id'];
    $new_dis_p = $_POST['new_discount'];
    $new_user_id = $_POST['new_user'];
    $new_ex_date = $_POST['new_expiration-date'];
    $sql ="UPDATE `user_coupon` uc
        INNER JOIN `coupon` c ON uc.coupon_id = c.coupon_id
        SET uc.user_id = '$new_user_id',
        c.code = '$new_vcode',
        c.discount_percentage = '$new_dis_p',
        c.expiration_date = '$new_ex_date'
        WHERE uc.coupon_id = $coupon_id;"  ;
    $query = $con->query($sql);

    if ($query) {

        echo '<div class="alert alert-success">Coupon information updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Coupon updated failed!</div>';
    }


}


//Delete voucher
if (myget('action') == 'delete') {
    $id = myget('coupon_id');
    $sql = "DELETE FROM `user_coupon` WHERE coupon_id = $coupon_id;DELETE FROM `coupon` WHERE coupon_id = $coupon_id";
    $query = $con->query($sql);
    if ($query) {
        //alert success and jump to the same page
        echo '<script>alert("Delete successfully!");window.location.href="coupon.php?param=' . $id_user . 'wi&id=' . $row["coupon_id"] . '&page=' . ceil($row["coupon_id"] / 5) . '";</script>';

    } else {
        echo '<div class="alert alert-danger">Delete failed!</div>';
    }
}


//Add voucher
if (myget('action') == 'add') {
    // Get the form data
    $code = $_POST['newc_voucher-code'];
    $discount_percentage = $_POST['newc_discount'];
    $expiration_date = $_POST['newc_expiration-date'];
    $user_id = $_POST['newc_user'];

    // Prepare the SQL statement to insert into the "coupon" table
    $coupon_id = $con->lastInsertId();
    echo $coupon_id;
    $insert_coupon_sql = "INSERT INTO coupon (coupon_id, code, discount_percentage, expiration_date, isUsed) VALUES ($coupon_id, $code, $discount_percentage, $expiration_date, $user_id)";
    $insert_coupon_stmt = $con->prepare($insert_coupon_sql);
    $is_used = 0; // Assuming that a new coupon is not used yet
    $insert_coupon_result = $insert_coupon_stmt->execute([$code, $discount_percentage, $expiration_date, $is_used]);

    if ($insert_coupon_result) {
        // If the insert into the "coupon" table is successful, get the ID of the newly inserted row


        // Prepare the SQL statement to insert into the "user_coupon" table
        $insert_user_coupon_sql = "INSERT INTO user_coupon (user_id, coupon_id) VALUES (?, ?)";
        $insert_user_coupon_stmt = $con->prepare($insert_user_coupon_sql);
        $insert_user_coupon_result = $insert_user_coupon_stmt->execute([$user_id, $coupon_id]);

        if ($insert_user_coupon_result) {
            // If the insert into the "user_coupon" table is successful, redirect to the same page with the new ID in the URL
            $page_number = ceil($coupon_id / 5); // Assuming that there are 5 coupons per page
            header("Location: coupon.php?id=$coupon_id&page=$page_number");
            exit();
            echo '<div class="alert alert-success">Add successfully!</div>';

        } else {
            echo '<div class="alert alert-danger">Failed to insert into user_coupon table.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Failed to insert into coupon table.</div>';
    }
}

function add_coupon($db_con)
{
    $sql = "SELECT c.coupon_id, c.code, c.discount_percentage, c.expiration_date, c.isUsed, uc.user_id AS coupon_user_id 
        FROM coupon c 
        LEFT JOIN user_coupon uc ON c.coupon_id = uc.coupon_id 
        GROUP BY c.coupon_id";

    $query = $db_con->query($sql);
    if (!$query) {
        return false;
    } else {
        //Add
        echo '<td>' . '<br><a class="btn btn-info" data-toggle="modal" data-target="#add-coupon-modal' . '">Add coupon</a>';

        // add Modal
        echo '<div class="modal fade" id="add-coupon-modal' . '" tabindex="-1" role="dialog" aria-labelledby="add-coupon-label' . '" aria-hidden="true">';
        echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="add-coupon-label' . '">Add coupon</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<form method="post">';
        echo '<div class="form-group">';
        echo '<label for="newc_discount">Discount:</label>';
        echo '<input type="number" class="form-control" id="newc_discount" name="newc_discount" value="' . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="newc_user">User:</label>';
        echo '<input type="text" class="form-control" id="newc_user" name="newc_user" value="' . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="newc_expiration-date">Expiration Date:</label>';
        echo '<input type="date" class="form-control" id="newc_expiration-date" name="newc_expiration-date" value="' . '">';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="newc_voucher-code">Voucher Code:</label>';
        echo '<input type="text" class="form-control" id="newc_voucher-code" name="newc_voucher-code" value="' . '">';
        echo '</div>';
        echo '<input type="hidden" name="coupon_id" value="' . '">';
        echo '<button type="submit" class="btn btn-primary">Add Coupon</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    }
}


function coupon_manage($db_con, $id_user)
{

    $sql = "SELECT c.coupon_id, c.code, c.discount_percentage, c.expiration_date, c.isUsed, uc.user_id AS coupon_user_id 
            FROM coupon c 
            LEFT JOIN user_coupon uc ON c.coupon_id = uc.coupon_id 
            GROUP BY c.coupon_id";

    $query = $db_con->query($sql);
    $total = $query->rowCount();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = $page < 1 ? 1 : $page;
    $page_size = 5;
    $page_count = ceil($total / $page_size);
    $page = $page > $page_count ? $page_count : $page;
    $offset = ($page - 1) * $page_size;
    $sql .= " LIMIT $offset, $page_size";
    $query = $db_con->query($sql);
    if (!$query) {
        return false;
    } else {
        foreach ($query as $row) {

            echo '<tr>';
            echo '<td >' . $row["coupon_id"] . '</td>';
            echo '<td>' . $row["code"] . '</td>';
            echo '<td>' . $row["discount_percentage"] . '</td>';
            echo '<td>' . $row["expiration_date"] . '</td>';

            //transfer coupon user id to name
            $coupon_user_id = $row["coupon_user_id"];
            $sql1 = "SELECT user.name as Name FROM user WHERE user_id = '$coupon_user_id'";
            $query1 = $db_con->query($sql1);
            $result1 = $query1->fetch();
            if ($result1 !== false) { // Check if $result1 is not false
                $coupon_user_name = $result1["Name"];
                echo '<td>' . $coupon_user_name . '</td>';
            } else {
                echo '<td></td>';
            }
            if ($row["isUsed"] == 0) {
                echo '<td>' . 'No' . '</td>';
            } else {
                echo '<td>' . 'Yes' . '</td>';
            }

            //Update
            echo '<td>' . '<br><a class="btn btn-primary" data-toggle="modal" data-target="#update-coupon-modal' . $row["coupon_id"] . '">Update</a>';

            // Update Modal
            echo '<div class="modal fade" id="update-coupon-modal' . $row["coupon_id"] . '" tabindex="-1" role="dialog" aria-labelledby="update-coupon-label' . $row["coupon_id"] . '" aria-hidden="true">';
            echo '<div class="modal-dialog" role="document">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="update-coupon-label' . $row["coupon_id"] . '">Update Coupon Information</h5>';
            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
            echo '<span aria-hidden="true">&times;</span>';
            echo '</button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post">';
            echo '<div class="form-group">';
            echo '<label for="new_discount">Discount:</label>';
            echo '<input type="number" class="form-control" id="new_discount" name="new_discount" value="' . $row["discount_percentage"] . '">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="new_user">User:</label>';
            echo '<input type="text" class="form-control" id="new_user" name="new_user" value="' . $row["coupon_user_id"] . '">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="new_expiration-date">Expiration Date:</label>';
            echo '<input type="date" class="form-control" id="new_expiration-date" name="new_expiration-date" value="' . $row["expiration_date"] . '">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="new_voucher-code">Voucher Code:</label>';
            echo '<input type="text" class="form-control" id="new_voucher-code" name="new_voucher-code" value="' . $row["code"] . '">';
            echo '</div>';
            echo '<input type="hidden" name="coupon_id" value="' . $row["coupon_id"] . '">';
            echo '<button type="submit" class="btn btn-primary">Update Coupon</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            //for the delete button, call delete action
            echo '<a class="btn btn-danger" href="#" onclick="if(confirm(\'Are you sure to delete this coupon?\')){window.location.href=\'coupon.php?param1=' . $id_user . '&action=delete&id=' . $row["coupon_id"] . '&page=' . ceil($row["coupon_id"] / 5) . '\'}">Delete</a>';

            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';
    echo '</table>';


    echo '<div class="row">';
    echo '<div class="col-md-12 text-center">';
    echo '<ul class="pagination">';
    echo '<li><a href="?param1=' . $id_user . '&page=' . ($page - 1) . '"> < </a></li>';
    //show the page number with highlight
    for ($i = 1; $i <= $page_count; $i++) {
        if ($i == $page) {
            echo '<li class="active"><a href="?param=' . $id_user . '&page=' . $i . '">' . $i . '</a></li>';
        } else {
            echo '<li><a href="?param=' . $id_user . '&page=' . $i . '">' . $i . '</a></li>';
        }
    }
    echo '<li><a href="?param=' . $id_user . '&page=' . ($page + 1) . '"> > </a></li>';
    echo '</ul>';
    echo '</div>';
    echo '</div>';

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coupon </title>
    <link rel="stylesheet" href="styles/bootstrap-337.min.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="css/Nav.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="js/jquery-331.min.js"></script>
    <script src="js/bootstrap-337.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //toolbar
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
                <a href="./user.php?param1=<?php echo $id_user ?>" class="sidebar_item ">User</a>
                <a href="./coupon.php?param1=<?php echo $id_user ?>" class="sidebar_item a_active">Coupon</a>
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
                                <div style="width: 80px;height: 30px"><a href="./profile.php?param1=<?php echo $id_user?>" class="options">Profile</a></div>
                                <div style="width: 80px;height: 30px; border-top: 1px grey solid"><a href="./login.php" class="options">Log out</a></div>
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

        <?php
        add_coupon($con);
        ?>
        <p class="blank-line"></p>
        <table border="1" style="border-collapse: collapse;">
            <tbody>
            <tr>
                <td style="width: 250px; height: 90px;font-weight: bold;"> ID</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> Voucher</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> Discount percentage</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> Expiration date</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> User</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> isUsed</td>
                <td style="width: 250px; height: 90px;font-weight: bold"> Option</td>
            </tr>
            <?php
            coupon_manage($con,$id_user);
            ?>
    </div>


</body>
</html>
                        