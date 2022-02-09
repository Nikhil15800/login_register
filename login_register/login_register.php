<?php 
require("connection.php");

#Login Part : 
if(isset($_POST['login']))
{
    $query = "SELECT * FROM `registered_user` WHERE `email` = '$_POST[user_name]' OR `username` = '$_POST[user_name]'";
    $result = mysqli_query($conn,$query);

    if($result)
    {
        if(mysqli_num_rows($result)==1)
        {
            $result_fetch = mysqli_fetch_assoc($result);
            if(password_verify($_POST['password'],$result_fetch['password']))
            {
                # if password match : 
                    echo"Correct";

            }
            else
            {
                # if incorrect password : 
                    echo"
                    <script>
                    alert('Incorrect Password ');
                    window.location.href='index.php';
                    ";

            }
        }
        else
        {
            echo"
            <script>
            alert('E-mail or username not register');
            window.location.href='index.php';
            ";
        }
    }
    else
    {
        echo"
        <script>
        alert('Cannot Run Query');
        window.location.href='index.php';
        ";
    }

}

# Registration Part :
if(isset($_POST['register'])){
    $user_exist_query="SELECT * FROM `registered_user` WHERE `username`='$_POST[username]' OR `email`='$_POST[email]'";
    $result = mysqli_query($conn,$user_exist_query);

    if($result){

        if(mysqli_num_rows($result)>0) # it will be executed when email and usename already already exist : 
        {
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
                #E-mail Already Register : 
                echo"<script>
                alert('$result_fetch[email] - Email Already Register');
                window.location.href='index.php';
                </script>";
            }
        }

        else   # it will be executed if no one has taken usename or email before : 
        {
            $password = password_hash($_POST['password'],PASSWORD_BCRYPT);
            $query="INSERT INTO `registered_user`(`full_name`, `username`, `email`, `password`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password')";
            if(mysqli_query($conn,$query))
            {
                # if data inserted successfully : 
                    echo"
                        <script>
                        alert('Registration Successfull : ');
                        window.location.href='index.php';
                        </script>";
            }
            else
            {
                #if data cannot be inserted : 
                echo"
                <script>
                alert('cannot Run Query');
                window.location.href='index.php';
                </script>";
            }
            
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