<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css2/animations.css">  
    <link rel="stylesheet" href="css2/main.css">  
    <link rel="stylesheet" href="css2/login.css">
        
    <title>Login</title>

    
    
</head>
<body>
    <?php

    //learn from w3schools.com
    //Unset all the server side variables

    session_start();

    $_SESSION["user"]="";
    $_SESSION["usertype"]="";
    
    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');

    $_SESSION["date"]=$date;
    

    //import database
    include("connection.php");

    



    if($_POST){

        $email=$_POST['useremail'];
        $password=$_POST['userpassword'];
        
        $error='<label for="promter" class="form-label"></label>';

        $result= $database->query("select * from webuser where email='$email'");
        if($result->num_rows==1){
            $utype=$result->fetch_assoc()['usertype'];
            if ($utype=='s'){
                // $checker = $database->query("select * from supervisor where semail='$email' and spassword='$password'");
                $result = $database->query("SELECT * FROM supervisor WHERE semail='$email'");

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $stored_hashed_password = $row['spassword']; // Retrieve the hashed password from the database
                
                    // Verify the entered password against the stored hashed password
                    if (password_verify($password, $stored_hashed_password)) {
                        // Password is correct
                        // Log the supervisor in
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 's';
                        header('location: supervisor/index.php');//Mana2 page for testing(until the supervvisor page is added <3)
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find any account for this email.</label>';
                }

            }elseif($utype=='a'){
                $checker = $database->query("select * from admin where aemail='$email' and apassword='$password'");
                if ($checker->num_rows==1){


                    //   Admin dashbord
                    $_SESSION['user']=$email;
                    $_SESSION['usertype']='a';
                    
                    header('location: admin/index.php');

                }else{
                    $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                }


            }elseif($utype=='t'){
                
                $result = $database->query("SELECT * FROM trainee WHERE temail='$email'");

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $stored_hashed_password = $row['tpassword']; // Retrieve the hashed password from the database
                    $stored_unencrypted_password = $row['tpassword']; // Retrieve the unencrypted password from the database
                
                    // Verify the entered password against the stored hashed password
                    if (password_verify($password, $stored_hashed_password)) {
                        // Password is correct
                        // Log the supervisor in
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 't';
                        header('location: trainee/index.php'); // Redirect to the trainee dashboard
                    } elseif ($password == $stored_unencrypted_password) {
                        // Password matches the unencrypted version
                        // You may want to log the user in or handle it as needed
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 't';
                        header('location: trainee/index.php'); // Redirect to the trainee dashboard
                    } else {
                        // Both hashed and unhashed password checks failed
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find any account for this email.</label>';
                }
                
                }
    
            
        }else{
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We cant found any acount for this email.</label>';
        }






        
    }else{
        $error='<label for="promter" class="form-label">&nbsp;</label>';
    }

    ?>





    <center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
         <tr>
                <td>
                    <p class="pic"></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="header-text">Welcome Back!</p>
                </td>
            </tr>

        <div class="form-body">
            <tr>
                <td>
                    <p class="sub-text">Login with your details to continue</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST" >
                <td class="label-td">
                    <label for="useremail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <label for="userpassword" class="form-label">Password: </label>
                </td>
            </tr>

            <tr>
                <td class="label-td">
                    <input type="Password" name="userpassword" class="input-text" placeholder="Password" required>
                </td>
            </tr>


            <tr>
                <td><br>
                <?php echo $error ?>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="submit" value="Login" class="login-btn btn-primary btn">
                </td>
            </tr>
        </div>
            <tr>
                <td>
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                    <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                    <br><br><br>
                </td>
            </tr>
                        
                        
    
                        
                    </form>
        </table>

    </div>
</center>
</body>
</html>