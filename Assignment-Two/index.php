<!-- Code by Deep Biswas | 200554124 -->
<!-- CMPG | Georgian College, ON, Canada -->
<!DOCTYPE html>
<?php
ob_start();
session_start();
include 'database.php';

$test_query = "SELECT 1";
$test_result = mysqli_query($conn, $test_query);

if (!$test_result){
    die("Database test query failed: " . mysqli_error($conn));
}

if (isset($_POST['username']) && isset($_POST['register-email']) && isset($_POST['password']) && isset($_POST['tandc'])){
    if ($_POST['tandc'] == 'on'){
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'register-email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if (strlen($username) < 3 || strlen($username) > 20){
            $_SESSION['error'] = "Username must be between 3 and 20 characters.";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }
        if (strlen($password) < 6 || strlen($password) > 20){
            $_SESSION['error'] = "Password must be between 6 and 20 characters.";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }

        $checkUsernameQuery = "SELECT * FROM assignmenttwousers WHERE username = '$username'";
        $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);

        $checkEmailQuery = "SELECT * FROM assignmenttwousers WHERE email = '$email'";
        $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

        if ($checkUsernameResult && mysqli_num_rows($checkUsernameResult) > 0 && $checkEmailResult && mysqli_num_rows($checkEmailResult) > 0){
            $_SESSION['error'] = "Both username and email already exist!";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } elseif ($checkUsernameResult && mysqli_num_rows($checkUsernameResult) > 0){
            $_SESSION['error'] = "Username already exists!";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } elseif ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0){
            $_SESSION['error'] = "Email already exists!";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } elseif (!$checkUsernameResult || !$checkEmailResult){
            $_SESSION['error'] = "Query failed: " . mysqli_error($conn);
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        }

        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $password = hash('sha512', mysqli_real_escape_string($conn, $password));

        $query = "INSERT INTO assignmenttwousers (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)){
            $_SESSION['success'] = "User registered successfully!";
        }else{
            echo "Error: " . mysqli_error($conn);
        }
    }else{
        $_SESSION['error'] = "Something went wrong, please try again.";
    }
}
if (isset($_POST['email']) && isset($_POST['password'])){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $email = mysqli_real_escape_string($conn, $email);
    $password = hash('sha512', mysqli_real_escape_string($conn, $password));

    $query = "SELECT * FROM assignmenttwousers WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result){
        if (mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $user;
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header('Location: success.php');
            exit;
        }else{
            $_SESSION['error'] = "Invalid email or password.";
        }
    }else{
        $_SESSION['error'] = "Query failed: " . mysqli_error($conn);
    }
}
ob_end_flush();
?>
<html lang="en">
    <head>
        <!-- Meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PHP assignment two - signup and login">
        <meta name="robots" content="noindex, nofollow">
        <title>Signup or Login</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="./img/minihorsepower-favicon-96x96.png">
        <!-- Custom stylesheet -->
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <!-- Video banner section -->
        <div class="v-banner">
            <video autoplay muted loop>
                <source src="./video/after-effects-liquid-gradients-loop-animation.mp4" type="video/mp4">
            </video>
            <!-- Main content area -->
            <div class="hero">
                <div class="form-ctnr">
                    <div class="logo">
                        <img src="./img/T-Logo-MiniHorsePower.png" alt="The Logo">
                    </div>
                    <!-- Toggle buttons for login and signup -->
                    <div class="button-ctnr">
                        <div id="btn"></div>
                        <button id="toggleBtn" type="button" class="toggle-btn" onclick="login()">Log In</button>
                        <button id="toggleBtn2" type="button" class="toggle-btn" onclick="register()">Sign Up</button>
                    </div>
                    <!-- Social icons section -->
                    <div class="social-icons">
                        <img src="./img/apple-logo.png" alt="Apple">
                        <img src="./img/facebook-logo.png" alt="Facebook">
                        <img src="./img/google-logo.png" alt="Google">
                    </div>
                    <!-- Login and Signup forms -->
                    <div class="forms">
                        <!-- Login form -->
                        <form id="login" class="input-group" action="" method="POST">
                            <input type="email" class="input-field" name="email" placeholder="Email" required>
                            <input type="password" class="input-field" name="password" placeholder="Password" required autocomplete="current-password">
                            <input type="checkbox" class="ck-box"><span>Remember Password</span>
                            <button type="submit" class="submit-btn">Log In</button>
                        </form>
                        <!-- Signup form -->
                        <form id="register" class="input-group" action="" method="POST">
                            <input type="text" class="input-field" name="username" placeholder="Username">
                            <input type="email" class="input-field" name="register-email" placeholder="Email">
                            <input type="password" class="input-field" name="password" placeholder="Password" required autocomplete="current-password">
                            <input type="checkbox" class="ck-box" name="tandc" id="register-tandc"><span>I agree to the terms and conditions.</span>
                            <button type="submit" name="signup-btn" class="submit-btn">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for displaying messages -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <img class="pop-img" id="modalImage">
                    <p id="modalText"></p>
                </div>
            </div>
        </div>
        <!-- JavaScript code for form toggling, modal handling, and message display -->
        <script>
            var x = document.getElementById("login");
            var y = document.getElementById("register");
            var z = document.getElementById("btn");
            var toggleBtn = document.getElementById("toggleBtn");
            function register(){
                x.style.left = "-400px";
                y.style.left = "50px";
                z.style.left = "110px";
                toggleBtn2.style.color = "white";
                toggleBtn.style.color = "black";
            }
            function login(){
                x.style.left = "50px";
                y.style.left = "450px";
                z.style.left = "0px";
                toggleBtn.style.color = "white";
                toggleBtn2.style.color = "black";
            }
            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];

            span.onclick = function(){
                modal.style.display = "none";
            };

            window.onclick = function(event){
                if (event.target == modal){
                    modal.style.display = "none";
                }
            };

            // Function to show modal with message and image
            function showModal(message, imgSrc){
                document.getElementById('modalText').innerText = message;
                modalText.style.textAlign = "center";
                var img = document.getElementById('modalImage');
                img.src = imgSrc;
                modal.style.display = "block";
            }

            // Event listener for signup form submission, checks if terms and conditions checkbox is checked
            document.getElementById("register").addEventListener("submit", function(event){
                if (!document.getElementById("register-tandc").checked){
                    event.preventDefault();
                    showModal("Please agree to the terms and conditions.", './img/broken-link.png');
                }
            });

            <?php
                if (isset($_SESSION['error'])){
                    echo "showModal('{$_SESSION['error']}', './img/broken-link.png');";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])){
                    echo "showModal('{$_SESSION['success']}', './img/signup-success.png');";
                    unset($_SESSION['success']);
                }
            ?>
        </script>
    </body>
</html>
