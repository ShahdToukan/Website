<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($con);
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/media.css">
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@300;600;800&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title> About Us</title>
</head>
<body>

<nav>
    <div class="row">
        <img src="assets/images/logo.png" alt="Artictic Taste logo" class="logo">

        <ul class="main-nav">
            <li>
                <a href="firstpage.html"> Home </a>
            </li>
            <li>
                <a href="artStyle.html"> Art Styles </a>
            </li>
            <li>
                <a href="aboutus.html"> About Us </a>
            </li>
            <li>
                <a href="sginup.html"> Sign Up </a>
            </li>
        </ul>

    </div>
</nav>



<section class="sign-up">

    <div id="login-box">
        <div class="left">
            <h3>Sign up</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">

        
            <input type="email" name="e-mail" placeholder="E-mail"   value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>


                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err; ?></span>

                            </div>


 <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">

            <input type="password" name="password2" placeholder="Retype password" class="form-control" value="<?php echo $confirm_password; ?>">

             <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <input class="btn btn-full" type="submit" name="signup_submit" value="Sign me up" />
        </div>

        <div class="right">
            <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
            <input class="btn btn-full" type="submit" name="signup_submit" value="Log In" />
        </div>
        <div class="or">OR</div>
    </div>
</section>

</body>
</html>
