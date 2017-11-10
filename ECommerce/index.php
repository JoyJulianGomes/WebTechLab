<?php 
    //IMPORTANT CONVENTION: ALL $_SESSION AND $_COOKIE VARIABLES ARE LOWER CASE; NO UPPER CASE ALLOWED
    session_start();
    if(isset($_COOKIE['username'])){
        $name = $_SESSION['username'] = $_COOKIE['username'];
    }
    else{
        $name = $_SESSION['username'] = "not set";
    }
    //echo "<h1>Username: ".$name."</h1><br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <style>
        .inputBox{
            width : 30%;
            /*margin: 0% 40% 0% 40%;*//*this one also works*/
            margin: 15% auto;
            /*border: 1px solid red;*/
        }
        .inputBox form table tr td{
            border: 1px solid yellow;
        }
        #submit{
            width:100%;
        }
        .error{
            text-align: right;
        }
    </style>
</head>
<body>
    <?php
        echo "Current Directory:". $_SERVER["PHP_SELF"]."<br>"; 
        
        $pass = "";//initializing vars
        $nameErr = $passErr = "";
        
        function inputFiltering($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        function retrieveNamePass($name)
        {
            $name="name:".$name;
            $file = fopen("myFile.txt", 'r') or die("File opening error");
            while(!feof($file))
            {
                $line = fgets($file);
                $semiSep = explode(";", $line);
                
                foreach($semiSep as $sentence)
                {
                    $unit = explode(",", $sentence);
                    if($unit[0]==$name)
                    {                        
                        fclose($file);
                        return $unit[2];
                    }
                }
            }
            fclose($file);
            return null;
        }
        
//--------------------------------------void main(){-------------------------------------//
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            $name = "".inputFiltering($_POST['name']);
            $pass = trim($_POST['pass']);
            $passFromFile = retrieveNamePass($name);
            $pass = "pass:".$pass;
            echo $pass." ".$passFromFile;
            if($passFromFile != null)
            {
                if($passFromFile == $pass)
                {
                    echo "success";
                    $_SESSION['username']=$name;
                    setcookie("username", $name, time()+86400);//1day=86400 10mins=600
                    header("Location:itemPage.php");
                    exit;
                }
                else{$passErr = "Wrong Password";}
            }
            else{$nameErr = "username not found";}
        }
//--------------------------------------}------------------------------------------------//
    ?>
    <div class="inputBox">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <table>
                <tr><td>Username:</td><td> <input type="text"     name="name" value="<?php echo $name;?>" required></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $nameErr;?></div></td></tr>
                <tr><td>Password:</td><td> <input type="password" name="pass" required></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $passErr;?></div></td></tr>
                <tr><td colspan=2><input id = "submit" type="submit"value="Log In">       </td></tr>
                <tr><td colspan=2><a href="Reg.php">Create An Account</a></td></tr>
            </table>
        </form>
    </div>
    <div class="inputBox">
        <table>
            <tr><td>Username:</td><td>Password</td></tr>
            <tr><td>joybangla</td><td>jbd</td></tr>
            <tr><td>dan</td><td>langdon</td></tr>
        </table>
    </div>
    
</body>
</html>