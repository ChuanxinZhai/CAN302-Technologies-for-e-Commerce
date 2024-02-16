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


//receive query parameters.
$Email = mypost('email');
$Password = mypost('password');
$Name = mypost('name');
$count = "SELECT MAX(user_id) FROM `user`";
$query = mysqli_query($con, $count);
$result= (int)mysqli_fetch_all($query)[0][0];

if (isset($_POST['add'])) {
    $sql = "INSERT INTO `user` (`user_id`, `name`, `email`, `password`, `payment_method`, `shipping_address`, `isAdmin`) VALUES ($result+1 , '".$Name."', '".$Email."', '".$Password."',1 ,123, 1)";
    mysqli_query($con,$sql);
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
    <script>
        $(document).ready(function (){
            //email校验
            const email = document.getElementById('email')
            const email_tooltip = document.getElementById('email_tooltip')
            // const pattern=/[\w]+@[A-Za-z]+(\.[A-Za-z0-9]+){1,2}/i;
            email.addEventListener("focusout", ()=>{
                if (!email.value != '') email_tooltip.style.display = 'block'
            })
            email.addEventListener("focus", ()=>{email_tooltip.style.display = 'none'})

            //name校验
            const name = document.getElementById('name')
            const name_tooltip = document.getElementById('name_tooltip')
            name.addEventListener("focusout", ()=>{
                if (!name.value != '') name_tooltip.style.display = 'block'
            })
            name.addEventListener("focus", ()=>{name_tooltip.style.display = 'none'})

            //psw校验
            const psw = document.getElementById('password')
            const psw_tooltip = document.getElementById('psw_tooltip')
            const pattern3=/^(?=.{6})/i;
            psw.addEventListener("focusout", ()=>{
                if (!psw.value.match(pattern3)) psw_tooltip.style.display = 'block'
            })
            psw.addEventListener("focus", ()=>{psw_tooltip.style.display = 'none'})
        })

    </script>

</head>
<body>
<div class="container">
    <h1>Register to start !</h1>
    <form action="" method="post">
        <div class="form-control">
            <div class="tooltip" id="email_tooltip">Please enter the correct format of email</div>
            <input type="text" name="email" id="email" required>
            <label for="email">Email</label>
        </div>

        <div class="form-control">
            <div class="tooltip" id="name_tooltip">The name cannot be empty</div>
            <input type="text" name="name" id="name" required>
            <label for="name">Name</label>
        </div>
        <div class="form-control">
            <div class="tooltip" id="psw_tooltip">The password cannot be smaller than 6 chars</div>
            <input type="text" name="password" id="password" required>
            <label for="password">Password</label>
        </div>

        <button class="btn" type="submit" id="add" name="add">Create</button>
        <p class="text">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

<script src="js/login.js"></script>
</body>
</html>
