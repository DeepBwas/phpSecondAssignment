<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<html lang="en">
    <head>
        <!-- Meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PHP assignment two - Restricted page.">
        <meta name="robots" content="noindex, nofollow">
        <title>Login Successful</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="./img/minihorsepower-favicon-96x96.png">
        <!-- Custom stylesheet -->
        <link rel="stylesheet" href="./css/style.css">
        <!-- Fontawesome icons -->
        <script src="https://kit.fontawesome.com/dc7a4e750d.js" crossorigin="anonymous"></script>
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
                    <!-- Welcome message -->
                    <div class="welcome">
                        <?php if(isset($_SESSION['username']) && isset($_SESSION['email'])): ?>
                            <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
                            <p>YOUR EMAIL: <?php echo $_SESSION['email']; ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Extra content -->
                    <div class="mini-link" id="miniLink">
                        <p>DISCOVER MORE <i class="fa-solid fa-arrow-right"></i></p>
                    </div>
                    <!-- Logout button -->
                    <div class="logout">
                        <form method="POST">
                            <button class="submit-btn" type="submit" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for displaying messages -->
        <div id="myModal" class="modal">
            <div class="modal2-content">
                <span class="close">&times;</span>
                <div id="modal-in">
                    <img class="pop-img2" id="modalImage" src="./img/T-Logo-MiniHorsePower.png" alt="The Logo">
                    <h3>Click On The Image</h3>
                    <p>By clicking on the image you will be redirected to 'minihorsepower.com', which is not part of this assignment. Please close this popup if you do not wish to proceed.</p>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('miniLink').addEventListener('click', function(){
                document.getElementById('myModal').style.display = 'block';
            });
            document.getElementById('modalImage').addEventListener('click', function(){
                window.open('http://www.minihorsepower.com', '_blank');
            });

            var modalContent = document.getElementById('modal-in');
            modalContent.style.textAlign = 'center';
            

            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];

            span.onclick = function(){
                modal.style.display = "none";
            };
        </script> 
    </body>
</html>
