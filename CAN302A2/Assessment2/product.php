<?php
//open database by PDO

$dbms='mysql';     //DBMS type
$host='localhost'; //Host name
$dbName='store_admin';    //database name
$user='root';      //database user
$pass='';          //database password
$dsn="$dbms:host=$host;dbname=$dbName";

try {
    $con = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}

$str = $_SERVER["QUERY_STRING"];
$user_id = explode('=',$str)[1];
$sql = "SELECT user.name as name FROM `user` WHERE user_id= '" . $user_id . "' ";
//$query2 = mysqli_query($con,$sql);
$query = $con->query($sql);
$result1 = $query->fetch()[0];

//a safe method to recieve get data
function myget($str) {
    $val = !empty($_GET[$str]) ? $_GET[$str] : '';
    return $val;
}

function mypost($str) {
    $val = !empty($_POST[$str]) ? $_POST[$str] : '';
    return $val;
}


if (isset($_POST['add'])) {
    add($con,mypost('pname'), mypost('pdescription'), mypost('pprice'), mypost('pstock'), mypost('pcategory_id'));
}

else if(isset($_GET['id'])){

    if($_GET['id']!=null){
        delete($con, myget('id'),$user_id);
    }
}
else if(isset($_POST['update'])){
    //change the category_name to category_id
    $sql="SELECT * FROM category WHERE name = '".mypost('pcategory_id')."'";
    $query = $con->query($sql);
    if($query->rowCount() == 0){
        echo '<div class="alert alert-danger">Category does not exist!</div>';
        return;
    }
    else{
        foreach($query as $row){
            $category_id = $row["category_id"];
        }
        update($con, mypost('pid'),mypost('pname'),  mypost('pdescription'), mypost('pprice'), mypost('pstock'), $category_id);
    }

}

function delete($db_con, $id,$user_id){
    $sql = "DELETE from `order_detail` WHERE product_id=$id;DELETE FROM `product` WHERE product_id=$id";
    $query = $db_con->query($sql);
//echo $sql;
    if ($query) {
        // display delete successfully and jump to the product page
        echo '<div class="alert alert-success">Delete successfully!</div>';

    } else {
        echo '<div class="alert alert-danger">Delete failed!</div>';

    }
}


function add($db_con, $name, $description, $price, $stock, $category_id){
    //get total number of product
    $sql1 = "SELECT product_id  FROM product order by product_id desc limit 1";
    $query1 = $db_con->query($sql1);
    $res = $query1->fetch();
    $id = isset($res['product_id']) ? $res['product_id'] + 1 : 1;
    //transfer $category_id to int write sql sentence
    $sql2="SELECT * FROM category WHERE name = '".$category_id."'";
    $query2 = $db_con->query($sql2);
    foreach($query2 as $row){
        $category_id = $row["category_id"];
    }
    $sql = "INSERT INTO `product` (`product_id`,`name`,`description`,`stock`,`price`,`image`,`category_id`) VALUES ($id,'".$name."','{$description}', $stock,$price, ' ',{$category_id})";
    $query = $db_con->query($sql);
//    echo $sql;
    if ($query) {
        echo '<div class="alert alert-success">Add successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Add failed!</div>';
    }
}

function update($db_con,  $id,$name, $description, $price, $stock, $category_id){
    $sql="UPDATE `category` SET `category_id`='".$category_id."' WHERE `category_id` = '".$category_id."';
    UPDATE `product` SET `name` = '".$name."', `description` = '".$description."', `price` = '".$price."', `stock` = '".$stock."', `category_id` = '".$category_id."' WHERE `product_id` = ".$id."";

    echo $sql;
    $query = $db_con->query($sql);
    if ($query) {
        echo '<div class="alert alert-success">Update successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Update failed!</div>';
    }
}


function myproduct($db_con,$user_id){
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = $page < 1 ? 1 : $page;
    $page_size = 5;
    $start = ($page -1)  * $page_size;

    $sql = "SELECT * FROM product limit {$start},{$page_size}";
    $query = $db_con->query($sql);
    $list = $query->fetchAll();
    $index=1;

    foreach($list as $row){
        echo '<tr>';
        echo '<td scope="row">'.$row["product_id"].'</td>';
        echo '<td>'.$row["name"].'</td>';
        echo '<td>'.$row["description"].'</td>';
        echo '<td>'.$row["price"].'</td>';
        echo '<td>'.$row["stock"].'</td>';
        // echo '<td>'.$row["category_id"].'</td>';
        //get the name based on the category_id
        $sql2 = "SELECT * FROM category WHERE category_id = ".$row["category_id"];
        $query2 = $db_con->query($sql2);
        foreach($query2 as $row2){
            echo '<td>'.$row2["name"].'</td>';
        }

        echo '<td>';

        echo '<a class="btn btn-info" href="javascript:show('.$row["product_id"].');" style="margin-right: 2rem !important; width:80px">Update</a>';
        echo '<div id='.$row["product_id"].' class="modal" style="width:500px; margin:auto;">';
        echo '<div class="modal-content">';
        echo '<span class="close"><a href="javascript:hide('.$row["product_id"].');">&times;</a></span>';

        echo '<form method="post">';
        echo '<h4 class="modal-title" id="myModalLabel" style="margin-bottom:2rem !important; margin-top:20px">Update the product</h4>';
        echo 'Product ID: <input type="text" class="form-control" id="pid" name="pid" placeholder="Please input product ID" style="margin-bottom:1rem !important; width:260px; margin:auto" value="'.$row["product_id"].'" readonly>';
        echo 'Product name: <input type="text" class="form-control" id="pname" name="pname" placeholder="Please input product name" style="margin-bottom:1rem !important; width:260px; margin:auto">';
        echo 'Description: <input type="text" class="form-control" id="pdescription" name="pdescription" placeholder="Please input product description" style="margin-bottom:1rem !important; width:260px; margin:auto">';
        echo 'Price: <input type="text" class="form-control" id="pprice" name="pprice" placeholder="Please input price" style="margin-bottom:1rem !important; width:260px; margin:auto">';
        echo 'Stock: <input type="text" class="form-control" id="pstock" name="pstock" placeholder="Please input stock" style="margin-bottom:2rem !important; width:260px; margin:auto">';
        // category
        // echo 'Category: <select id="pcategory_id" name="pcategory_id" style="margin-bottom:2rem !important; width:200px;">
        //     <option value = "Select">Please select category</option>



        //     // <option value = "Book">Book</option>
        //     // <option value = "Computer">Computer</option>
        //     </select>';
        //input the product name if right update the product, else throw error
        echo 'Category: <input type="text" class="form-control" id="pcategory_id" name="pcategory_id" placeholder="Please input category" style="margin-bottom:2rem !important; width:260px; margin:auto">';

        echo'<button type="submit" class="btn btn-warning" id="update" name="update" value="update" style="margin-bottom: 3rem !important; margin-left: 25rem !important;">Confirm</button>';


        echo '</form>';

        echo '</div>';
        echo '</div>';

        echo '<a class="btn btn-danger" href="?param=' .$user_id. '&id='.$row["product_id"].'" onclick="return del();" style="margin-right: 2rem !important; width:80px">Delete</a>';
        echo '</td>';
        echo '</tr>';
        $index=$index+1;

    }
    $count_sql = "SELECT count('product_id') num FROM product";
    $res_query = $db_con->query($count_sql);
    $res_count = $res_query->fetch();
    $total = $res_count['num'];

    $page_count = ceil($total / $page_size);
    $page = $page > $page_count ? $page_count : $page;
    $offset = ($page - 1) * $page_size;
    $sql .= " LIMIT $offset, $page_size";

    echo '</tbody>';
    echo '</table>';

    echo '<div class="row">';
    echo '<div class="col-md-12 text-center">';
    echo '<ul class="pagination">';
    echo '<li><a href="?param=' .$user_id. '&page=' . ($page - 1) . '"> < </a></li>';
    //show the page number with highlight
    for ($i = 1; $i <= $page_count; $i++) {
        if ($i == $page) {
            echo '<li class="active"><a href="?param=' .$user_id. '&page=' . $i . '">' . $i . '</a></li>';
        } else {
            echo '<li><a href="?param=' .$user_id. '&page=' . $i . '">' . $i . '</a></li>';
        }
    }
    echo '<li><a href="?param=' .$user_id. '&page=' . ($page + 1) . '"> > </a></li>';
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
                <a href="./index.php?param1=<?php echo $user_id ?>" class="sidebar_item">DashBoard</a>
                <a href="./category.php?param1=<?php echo $user_id ?>" class="sidebar_item">Category</a>
                <a href="./order.php?param1=<?php echo $user_id ?>" class="sidebar_item">Order</a>
                <a href="./product.php?param1=<?php echo $user_id ?>" class="sidebar_item a_active">Product</a>
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
                                <div style="width: 80px;height: 30px"><a href="profile.php?param1=<?php echo $user_id ?>" class="options">Profile</a></div>
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

            <a class="btn btn-warning" href="javascript:show('new_product');" style="margin-right: 2rem !important; margin-bottom:2rem !important">Add</a>

            <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th scope="col" style='text-align: center;'>ID</th>
                    <th scope="col" style='text-align: center;'>Product name</th>
                    <th scope="col" style='text-align: center;'>Description</th>
                    <th scope="col" style='text-align: center;'>Price</th>
                    <th scope="col" style='text-align: center;'>Stock</th>
                    <th scope="col" style='text-align: center;'>Category</th>
                    <th scope="col" style='text-align: center;'>Option</th>
                </tr>
                </thead>
                <tbody>
                <?php
                myproduct($con,$user_id);
                ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<div id="new_product" class="modal" style="width: 500px; margin: auto; display: none;text-align: center">
    <div class="modal-content">
		<span class="close">
			<a href="javascript:hide('new_product');">×</a>
		</span>
        <form method="post">
            <h4 class="modal-title" id="myModalLabel" style="margin-bottom:2rem !important; margin-top:20px">Add the product</h4>
            Product name:
            <input type="text" class="form-control" id="pname" name="pname" placeholder="Please input product name" style="margin-bottom:1rem !important; width:260px; margin:auto">
            Description:
            <input type="text" class="form-control" id="pdescription" name="pdescription" placeholder="Please input product description" style="margin-bottom:1rem !important; width:260px; margin:auto">
            Price:
            <input type="text" class="form-control" id="pprice" name="pprice" placeholder="Please input price" style="margin-bottom:1rem !important; width:260px; margin:auto">
            Stock:
            <input type="text" class="form-control" id="pstock" name="pstock" placeholder="Please input stock" style="margin-bottom:2rem !important; width:260px; margin:auto">
            Category:
            <input type="text" class="form-control" id="pcategory_id" name="pcategory_id" placeholder="Please input category" style="margin-bottom:2rem !important; width:260px; margin:auto">
            <button type="submit" class="btn btn-warning" id="add" name="add" value="add" style="margin-bottom: 3rem !important; margin-left: 25rem !important;">Confirm</button>
        </form>
    </div>
</div>
<script src="js/jquery-331.min.js"></script>
<script language="javascript">

    function del() {
        if (confirm("Are you sure to delete?")) {
            return true;
        } else {
            return false;
        }
    }

    // show the form
    function show(id){
        var el = document.getElementById(id)
        el.style.display = "block";
    }

    // hide the form
    function hide(id){
        var el = document.getElementById(id)
        el.style.display = "none";
    }

</script>


</body>
</html>