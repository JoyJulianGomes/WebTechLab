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
        
        $name = $pass = "";//initializing vars
        $nameErr = $passErr = "";
        $fillFlag = false;

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
            if(empty($_POST['name']))
            {
                $nameErr = "Field Required!!";
                $fillFlag = true;
            }
            else{$name = "".inputFiltering($_POST['name']);}
            
            if(empty($_POST['pass']))
            {
                $passErr = " Field Required!!";
                $fillFlag = true;
            }
            else{$pass = inputFiltering($_POST['pass']);}
            
            //if all fields are filled then check proccess start
            if($fillFlag== false)
            {                
                $passFromFile = retrieveNamePass($name);
                $pass = "pass:".$pass;
                if($passFromFile != null)
                {
                    if($passFromFile == $pass){echo "success";}
                    else{$passErr = "Wrong Password";}
                }
                else{$nameErr = "username not found";}
            }
        }
//--------------------------------------}------------------------------------------------//
    ?>
    <div class="inputBox">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <table>
                <tr><td>Username:</td><td> <input type="text"     name="name"></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $nameErr;?></div></td></tr>
                <tr><td>Password:</td><td> <input type="password" name="pass"></td></tr>
                <tr><td colspan=2><div class="error"> <?php echo $passErr;?></div></td></tr>
                <tr><td colspan=2><input id = "submit" type="submit"value="Log In">       </td></tr>
            </table>
        </form>
    </div>
    
</body>
</html>