<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign up</title>
        <style>
            .inputBox{
                width : 30%;
                margin: 15% 40% 0% 40%;
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
            function inputFiltering($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
			
            function checkPassAndConfpassMatch($pass, $confPass)
            {return ($pass==$confPass)?true:false;}
			
            function checkIfNameAlreadyExists($name)
            {
                $name="name:".$name;
                $file = fopen("myFile.txt", 'r') or die("File opening error");
                $match=false;
                while(!feof($file))
				{   
                    $line = fgets($file);
                    $semiSep = explode(";", $line);
                    
                    foreach($semiSep as $sentence)
					{
						$unit = explode(",", $sentence);
                        if($unit[0]==$name)
						{
                            $match = true;
							break;
						}
                    }
                    if($match)
                    {
                        break;
                    }
                }
                fclose($file);
                return $match;
			}
			
            function writeCredential($str, $name, $pass, $confPass)
            {
                GLOBAL $nameErr, $passErr, $confPassErr;
				if(checkPassAndConfpassMatch($pass, $confPass)){
				    if(!checkIfNameAlreadyExists($name))
					{
                        $file = fopen("myFile.txt", 'a') or die("File opening error");
						if(fwrite($file, $str))
						{
                            //echo "writting successfull<br>";
                            fclose($file);
                            return true;
                        }
                        else
                        {
                            //echo "<h1>Something F*cked Up >_< \nsudo unclock -de_FREAKING_bug_mode<h1>";
                            fclose($file);
                            return false;
                        }
					}
					else{
                        $nameErr = "Name already in use";
                        return false;
					}
				}
				else{
                    $passErr = "Pass and Conf Pass do not match";
                    return false;
				}
            }
//--------------------------------------void main(){-------------------------------------//
            echo "Current Directory:". $_SERVER["PHP_SELF"]."<br>"; 
            $name = $email = $pass = $confPass = $str= "";//initializing vars
            $nameErr = $emailErr = $passErr = $confPassErr = "";
            
            if($_SERVER['REQUEST_METHOD']=='POST')
            {
                $name = inputFiltering($_POST['name']);
                $email = inputFiltering($_POST['email']);
                $pass = trim($_POST['pass']);
                $confPass = trim($_POST['confPass']);
                $str = "name:".$name.",email:".$email.",pass:".$pass.";\n";
                writeCredential($str, $name, $pass, $confPass);
            }
//--------------------------------------}------------------------------------------------//
        ?>
        <div class="inputBox">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <table>
                    <tr><td>Name:            </td><td> <input type="text"     name="name" required> </td></tr>
                    <tr><td colspan=2><div class="error"> <?php echo $nameErr;?></div></td></tr>
                    <tr><td>Email:           </td><td> <input type="text"     name="email" required></td></tr>
                    <tr><td colspan=2><div class="error"> <?php echo $emailErr;?></div></td></tr>
                    <tr><td>Password:        </td><td> <input type="password" name="pass" required> </td></tr>
                    <tr><td colspan=2><div class="error"> <?php echo $passErr;?></div></td></tr>
                    <tr><td>Confirm Password:</td><td> <input type="password" name="confPass" required></td></tr>
                    <tr><td colspan=2><div class="error"> <?php echo $confPassErr;?></div></td></tr>
                    <tr><td colspan=2><input id = "submit" type="submit"value="Sign up"></td></tr>
                </table>
            </form>
        </div>
    </body>
</html>