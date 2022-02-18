<?php 
require("connection.php");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

# send mail : 
function sendMail($email,$v_code){
    require ('PHPMailer/PHPMailer.php');
    require ('PHPMailer/SMTP.php');
    require ('PHPMailer/Exception.php');

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'jhonparker09602@gmail.com';                     //SMTP username
        $mail->Password   = 'Hello@123';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('jhonparker09602@gmail.com', 'India');
        $mail->addAddress($email);     //Add a recipient
    
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification from India';
        $mail->Body    = "Thanks for Registration! 
        Click the link below to verify the email address
        <a href='http://localhost/login_register/verify.php?email=$email&v_code=$v_code'>Verify</a>";
        
    
        $mail->send();
        return true;
    } 
    catch (Exception $e) {
        return false;
    }
}







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
            if($result_fetch['is_verified']==1)
            {
                if(password_verify($_POST['password'],$result_fetch['password']))
            {

               $_SESSION['logged_in']=true;
               $_SESSION['username']=$result_fetch['username'];
               header('location:index.php');


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
                alert('E-mail  not Verified');
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
            $v_code = bin2hex(random_bytes(16));

            $query="INSERT INTO `registered_user`(`full_name`, `username`, `email`, `password`,`verification_code`, `is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";
            if(mysqli_query($conn,$query) && sendMail($_POST['email'],$v_code))
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
                alert('Server Down');
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