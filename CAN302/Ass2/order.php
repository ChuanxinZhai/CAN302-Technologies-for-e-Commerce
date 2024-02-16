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
$id = mypost('order_id');
$date = mypost('date');
$user_id = mypost('user_name');
$product_details = mypost('product_details');
$total_price = mypost('total_price');
$status = mypost('status');
$payment_method = mypost('payment_method');
$shipping_address = mypost('shipping_address');
$str = $_SERVER["QUERY_STRING"];
$id_user = explode('=',$str)[1];
$sql = "SELECT user.name as name FROM `user` WHERE user_id= '" . $id_user . "' ";
//$query2 = mysqli_query($con,$sql);
$query = $con->query($sql);
$result1 = $query->fetch()[0];





//delete order action
if (myget('action') == 'delete') {
    $id = myget('id');
    // $sql = "DELETE FROM `order` WHERE order_id = $id";
    //delete the order and order_detail with the same id in the database
    $sql = "DELETE FROM `order_detail` WHERE order_id = $id;DELETE FROM `order` WHERE order_id = $id";
    $query = $con->query($sql);
    if ($query) {
        //alert success and jump to the same page
        //show $id_user

        echo '<script>alert("Delete successfully!");window.location.href="order.php?param='.$id_user.'&id=' . $row["order_id"] . '&page=' . ceil($row["order_id"] / 5) . '";</script>';

        // echo '<div class="alert alert-success">Delete successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Delete failed!</div>';
    }
}

// change address action, it will pop up a window with input to let user input the new address
if (myget('action') == 'change_address') {
    $id = myget('id');
    echo '<script>var new_address = prompt("Please input the new address:");window.location.href="order.php?param='.$id_user.'&action=change_address&id=' . $id . '&new_address=" + new_address;</script>';
}



//cancel order action
if (myget('action') == 'cancel') {
    $id = myget('id');
    $sql = "UPDATE `order` SET status = 4 WHERE order_id = $id";
    $query = $con->query($sql);
    if ($query) {
        echo '<div class="alert alert-success">Cancel successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Cancel failed!</div>';
    }
}

//ship order action
if (myget('action') == 'ship') {
    $id = myget('id');
    $sql = "UPDATE `order` SET status = 2 WHERE order_id = $id";
    $query = $con->query($sql);
    if ($query) {
        echo '<div class="alert alert-success">Ship successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Ship failed!</div>';
    }
}

//complete order action
if (myget('action') == 'complete') {
    $id = myget('id');
    $sql = "UPDATE `order` SET status = 3 WHERE order_id = $id";
    $query = $con->query($sql);
    if ($query) {
        echo '<div class="alert alert-success">Complete successfully!</div>';
    } else {
        echo 'div class="alert alert-danger">Complete failed!</div>';
    }
}

//update address action
if (isset($_POST['new_address'], $_POST['order_id'])) {
    // process the new address update here
    $new_address = $_POST['new_address'];
    $id = $_POST['order_id'];
    // update the database with new address
    $sql = "UPDATE `order` SET shipping_address = '$new_address' WHERE order_id = $id";
    $query = $con->query($sql);
    if ($query) {
        echo '<div class="alert alert-success">Address updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Address updated failed!</div>';
    }
}






// order_list which also contain paging function, one page 5 rows
function order_list($db_con,$id_user)
{
    $sql = "SELECT o.order_id, o.date, o.status, o.payment_method, o.shipping_address, u.name AS user_name, SUM(p.price * od.amount) AS total_price, GROUP_CONCAT(p.name, ' x', od.amount) AS product_details
    FROM `order` o
    JOIN `user` u ON o.user_id = u.user_id
    JOIN `order_detail` od ON o.order_id = od.order_id
    JOIN `product` p ON od.product_id = p.product_id
    GROUP BY o.order_id
    ";
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
    // if the query is empty, return false
    if (!$query) {
        return false;
    } else {
        foreach ($query as $row) {

            echo '<tr>';
            echo '<td >' . $row["order_id"] . '</td>';
            echo '<td>' . $row["date"] . '</td>';
            echo '<td>' . $row["product_details"] . '</td>';
            echo '<td>' . $row["total_price"] . '</td>';

            echo '<td>' . $row["user_name"] . '</td>';

            // 1-paid, 2-shipped, 3-completed, 4-canceled
            if ($row["status"] == 1) {
                echo '<td style="color: blue"> Paid </td>';
            } else if ($row["status"] == 2) {
                echo '<td style="color: orange"> Shipped </td>';
            } else if ($row["status"] == 3) {
                echo '<td style="color: green"> Completed </td>';
            } else if ($row["status"] == 4) {
                echo '<td style="color: red"> Canceled </td>';
            }

            // 1-Wechat pay, 2-Alipay, 3-Credit card
            if ($row["payment_method"] == 1) {
                echo '<td> Wechat pay </td>';
            } else if ($row["payment_method"] == 2) {
                echo '<td> Alipay </td>';
            } else if ($row["payment_method"] == 3) {
                echo '<td> Credit card </td>';
            }


            if ($row["status"] == 1) {
                //display the shiping address and change address button in the same td
                echo '<td>' . $row["shipping_address"] . '<br><a class="btn btn-primary" data-toggle="modal" data-target="#change-address-modal">Change Address</a>';

                // modal form HTML
                echo '<div class="modal fade" id="change-address-modal" tabindex="-1" role="dialog" aria-labelledby="change-address-label" aria-hidden="true">';
                echo '<div class="modal-dialog" role="document">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="change-address-label">Change Shipping Address</h5>';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<form method="post">';
                echo '<div class="form-group">';
                echo '<label for="new-address">New Address:</label>';
                echo '<input type="text" class="form-control" id="new-address" name="new_address">';
                echo '</div>';
                echo '<input type="hidden" name="order_id" value="' . $row["order_id"] . '">';
                echo '<button type="submit" class="btn btn-primary">Save Changes</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

            } else {
                echo '<td>' . $row["shipping_address"] . '</td>';
            }
            echo '<td>';

            //1-paid (change address,status), 2-shipped(change status), 3-completed, 4-canceled
            if ($row["status"] == 1) {
                //add a shipping buttom
                echo '<a class="btn btn-warning" href="#" onclick="if(confirm(\'Are you sure to ship this order?\')){window.location.href=\'order.php?param=' . $id_user. '&action=ship&id=' . $row["order_id"] . '&page=' . ceil($row["order_id"] / 5) . '\'}">Ship</a>';
                //add a calcel buttom
                echo '<a class="btn btn-info" href="#" onclick="if(confirm(\'Are you sure to cancel this order?\')){window.location.href=\'order.php?param=' . $id_user . '&action=cancel&id=' . $row["order_id"] . '&page=' . ceil($row["order_id"] / 5) . '\'}">Cancel</a>';
            } else if ($row["status"] == 2) {
                //add a complete buttom
                echo '<a class="btn btn-success" href="#" onclick="if(confirm(\'Are you sure to complete this order?\')){window.location.href=\'order.php?param=' . $id_user . '&action=complete&id=' . $row["order_id"] . '&page=' . ceil($row["order_id"] / 5) . '\'}">Complete</a>';

            }




            //for the delete button, call delete action
            echo '<a class="btn btn-danger" href="#" onclick="if(confirm(\'Are you sure to delete this order?\')){window.location.href=\'order.php?param=' . $id_user. '&action=delete&id=' . $row["order_id"] . '&page=' . ceil($row["order_id"] / 5) . '\'}">Delete</a>';

            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';
    echo '</table>';


    echo '<div class="row">';
    echo '<div class="col-md-12 text-center">';
    echo '<ul class="pagination">';
    echo '<li><a href="order.php?param=' . $id_user. '&page=' . ($page - 1) . '"> < </a></li>';
    //show the page number with highlight
    for ($i = 1; $i <= $page_count; $i++) {
        if ($i == $page) {
            echo '<li class="active"><a href="order.php?param=' . $id_user. '&page=' . $i . '">' . $i . '</a></li>';
        } else {
            echo '<li><a href="order.php?param=' . $id_user. '&page=' . $i . '">' . $i . '</a></li>';
        }
    }
    echo '<li><a href="order.php?param=' .$id_user. '&page=' .($page + 1). '"> > </a></li>';
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
    <title>CAN302 Lab3</title>
    <link rel="stylesheet" href="styles/bootstrap-337.min.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="css/Nav.css">
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
                <a href="./order.php?param1=<?php echo $id_user ?>" class="sidebar_item a_active">Order</a>
                <a href="./product.php?param1=<?php echo $id_user ?>" class="sidebar_item">Product</a>
                <a href="./user.php?param1=<?php echo $id_user ?>" class="sidebar_item ">User</a>
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
                                <div style="width: 80px;height: 30px"><a href="profile.php?param1=<?php echo $id ?>" class="options">Profile</a></div>
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
        <div class="container">
            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th scope="col" style='text-align: center;'>ID</th>
                    <th scope="col" style='text-align: center;'>Date</th>
                    <th scope="col" style='text-align: center;'>Product details</th>
                    <th scope="col" style='text-align: center;'>Total price</th>
                    <th scope="col" style='text-align: center;'>User</th>
                    <th scope="col" style='text-align: center;'>Status</th>
                    <th scope="col" style='text-align: center;'>Payment method</th>
                    <th scope="col" style='text-align: center;'>Shipping address</th>
                    <th scope="col" style='text-align: center;'>Option</th>

                </tr>
                </thead>
                <tbody>
                <?php
                order_list($con,$id_user);
                ?>
                <!--                 </tbody>-->
                <!--    </table>-->

        </div>
    </div>
</div>

</body>

</html>