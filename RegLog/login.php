<?php 
    //IMPORTANT CONVENTION: ALL $_SESSION AND $_COOKIE VARIABLES ARE LOWER CASE; NO UPPER CASE ALLOWED
    session_start();
    if(isset($_COOKIE['username'])){
        $uname = $_SESSION['username'] = $_COOKIE['username'];
    }
    else{
        $uname = $_SESSION['username'] = "";//"not set";
    }
    //echo "<h1>Username: ".$uname."</h1><br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <style>
        .inputBox{
            width : 30%;
            margin: 10% 45% 0% 35%;/*this one also works*/
            /*margin: 15% auto 0% auto;*/
            /*border: 1px solid red;*/
        }
        .inputBox form table tr td{
            border: 1px solid yellow;
        }
        .inputBox table{
            width:100%;
        }
        #submit{
            width:100%;
        }
        .error{
            text-align: right;
        }
        .welcome{
            text-align:center;
        }
    </style>
    <script>
            function giveAlert(){
                alert("You are Registered");
            }
    </script>
</head>
<body>
    <?php
        //echo "Current Directory:". $_SERVER["PHP_SELF"]."<br>"; 
        
        $pass = $fname = "";//initializing vars
        $unameErr = $passErr = "";
        
        function inputFiltering($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        /*
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
        */
        function retrieveNamePassFromDB($UnameCheck)
        {
            //Creating Database connection
            $con = new mysqli("localhost", "root", "", "test");
            if ($con->connect_error) 
            {
                die("Connection failed: " . $con->connect_error);
            }

            //Preparing and binding qurery
            $stmt = $con->prepare("SELECT `password`, `firstname` FROM `credentials` where `username` = ?");
            $stmt->bind_param("s", $UnameCheck);
            //Executing Query
            $stmt->execute();
            //Loading Results
            $result = $stmt->get_result();
            if($result->num_rows > 0){ //name exists
                $row = $result->fetch_assoc();
                $pass = $row["password"];
                $fname = $row["firstname"];
                $credentials = array("pass"=>$pass, "fname" => $fname);
                /*
                begin debug purpose
                $file = fopen("debug.txt", 'a') or die("File opening error");
                fwrite($file, $uname." ".$pass."\n");
                end debug purpose
                */
                return $credentials;
            }
            else{ //name does not exists
                return null;
            }
        }

//--------------------------------------void main(){-------------------------------------//
        if($_SERVER['REQUEST_METHOD']=='POST')
        {          
            //filtering input
            $name = "".inputFiltering($_POST['uname']);
            $pass = trim($_POST['pass']);
           
            //Retrieving Credential From File
            //$passFromFile = retrieveNamePass($uname);
            //$pass = "pass:".$pass;

            //Retrieving Credentials From Database
            $passFromFile = retrieveNamePassFromDB($name);
            if($passFromFile == null)echo "PassFromDB NOt available";
            //echo $pass." ".$passFromFile;
            if($passFromFile != null)
            {
                if($passFromFile["pass"] == $pass)
                {
                    echo "success";
                    $_SESSION['username']=$name;
                    $_SESSION['fname'] = $passFromFile["fname"];
                    setcookie("username", $name, time()+86400);//1day=86400 10mins=600
                    echo "<script>giveAlert()</script>";
                    header("Location:startpage.php");
                    exit;
                }
                else{$passErr = "Wrong Password";}
            }
            else{$nameErr = "username not found";}
        }
//--------------------------------------}------------------------------------------------//
    ?>
    <div class="inputBox">
        <div class="welcome">
            <h1>Welcome to Online Mobile Bazar</h1><br>
            <h3>Please Log in to continue</h3><br>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <table>
                <tr><td>Username:</td><td> <input type="text"     name="uname" value="<?php echo $uname;?>" required></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $unameErr;?></div></td></tr>
                <tr><td>Password:</td><td> <input type="password" name="pass" required></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $passErr;?></div></td></tr>
                <tr><td colspan=2><input id = "submit" type="submit"value="Log In">       </td></tr>
                <tr><td colspan=2 style="text-align:center"><a href="Reg.php">Create An Account</a></td></tr>
            </table>
        </form>
    </div>
</body>
</html>