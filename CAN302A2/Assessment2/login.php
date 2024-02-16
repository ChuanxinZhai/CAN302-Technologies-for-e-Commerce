<?php
//open database by PDO

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAN302 Lab3</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/jquery-331.min.js"></script>
    <script src="js/bootstrap-337.min.js"></script>

</head>
<body>
<div class="container">
    <h1>Please Login</h1>
    <form action="" method="post">
<?php
if (isset($_POST['add'])){
    //receive query parameters.
    $Email = mypost('email');
    $Password = mypost('password');
    $sql = "SELECT * FROM `user` WHERE email= '" . $Email . "' ";
    $query = mysqli_query($con, $sql);
    $result = mysqli_fetch_all($query);

//判断result是否大于0
    if (count($result) > 0) {
        $id = $result[0][0];
        //判断密码是否正确
        if ($result[0][3] == $Password) {
            Header("Location: index.php?param1=$id");
        }
        else{
            echo '        <div id="tip" class="tooltip" style="position: absolute;width: 300px;height: 14px;top: 185px;display: block">Incorrect password</div>';
        }
    }
    else{
        echo '        <div id="tip" class="tooltip" style="position: absolute;width: 300px;height: 14px;top: 185px;display: block">Wrong account</div>';
    }

}
?>
        <div class="form-control">
            <input type="text" id="email" name="email" required>
            <label id="email">Email</label>
        </div>
        <div class="form-control">
            <input type="password" id="password" name="password" required>
            <label for="password">Password</label>
        </div>

        <button type="submit" class="btn" id="add" name="add">Login</button>
        <p class="text">Don't have an account? <a href="SignUp.php">Register</a></p>
    </form>
</div>

<script src="js/login.js"></script>
</body>
</html>
