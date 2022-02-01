<?php 
require"connection.php";
if(isset($_POST['register'])){
    $user_exist_query="SELECT * FROM `registered_user` WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
    $result = mysqli_query($conn,$user_exist_query);

    if($result){

        if(mysqli_num_rows($result)>0){
            #if any user has already taken usename or email
            $result_fetch=mysqli_fetch_assoc($result);

            if($result_fetch['username']==$_POST['username']){
                #error for username already  registered 
                echo"<script>
            alert('$result_fetch[username] - Username Already Taken');
            window.location.href='index.php';
            </script>";
            }

            else{
                echo"<script>
                alert('$result_fetch[username] - Username Already Taken');
                window.location.href='index.php';
                </script>";
            }
        }

        else{
            

        }
    }
    
    else{
        echo"<script>
        alert('cannot Run Query');
        window.location.href='index.php';
        </script>";
    }
}
?>