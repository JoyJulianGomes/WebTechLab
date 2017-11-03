<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign up</title>
        <style>
            .inputBox{
                width : 30%;
                margin: 15% 40% 0% 40%;/*this one also works*/
                /*margin: 15% auto;*/
                /*border: 1px solid red;*/
            }
            .inputBox form table tr td{
                /*border: 1px solid yellow;*/
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
            {
				return ($pass==$confPass)?true:false;
			}
			
            function checkIfNameAlreadyExists($name)
            {
				//return true when match exists
				//return false when name is unique
				
                //converting variable to stored information format
                $name="name:".$name;
                //echo $name."  ";
                //opening fie in read mode if fails throws error
                $file = fopen("myFile.txt", 'r') or die("File opening error");
                //while file pointer has not reached end of file character
                while(!feof($file))
				{
                    $match=false;
                    //returns an string instance $line where each entry is a line - ends with \n or [caret return] character in the $file 
                    $line = fgets($file);
                    //returns an array of substring by seperating a sring by ";" identifier
					$semiSep = explode(";", $line);
                    
                    //for every instance in $semiSep(sub string endded with ;) as $sentence
					foreach($semiSep as $sentence)
					{
						//echo $sentence."<br>";
                        //echo "<br>";
                        //splits $sentence by ',' and stores in $unit array
                        
                        $unit = explode(",", $sentence);
                        //echo "if(".$unit[0]." == ".$name.")";
						if($unit[0]==$name)
						{
                            //echo "Username already in use<br>";
                            //echo "true<br>";
                            $match = true;
							fclose($file);
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
                //echo "<h2>inside writeCredential</h2><br>";
                //write in file 
                //if pass and confpass matches
                //and the name is unique
                
                //THIS IS IMPORTANT BECAUSE IF THEY ARE NOT EXPLICITLY DECLARED GLOBAL,
                //THEN THE VARIABLE USED WILL BE CONSIDERED AS LOCAL VAR AND WONT MAKE OUT TO THE OUTPUT
                GLOBAL $nameErr, $passErr, $confPassErr;
				if(checkPassAndConfpassMatch($pass, $confPass)){
				
					if(!checkIfNameAlreadyExists($name))
					{
                        //echo $str;
						$file = fopen("myFile.txt", 'a') or die("File opening error");
						if(fwrite($file, $str))
						{
							//echo "writting successfull<br>";
                        }
                        else
                        {
                            //echo "<h1>Something F*cked Up >_< \nsudo unclock -de_FREAKING_bug_mode<h1>";
                        }
                        fclose($file);
                        //echo "<h2>writeCredential ended</h2><br>";
                        return true;
                        /*
                            It's raining cats and dog today.
                            I dont like rain at all. 
                            Probably bracause I live in Dhaka
                        */
					}
					
					else{
                        //echo "Name already exists";
                        $nameErr = "Name already in use";
                        //echo "<h2>writeCredential ended</h2><br>";
						return false;
					}
				}
				else{
                    //echo "Pass and Conf Pass do not match";
                    $passErr = "Pass and Conf Pass do not match";
                    //echo "<h2>writeCredential ended</h2><br>";
					return false;
				}
                
            }
            
//--------------------------------------void main(){-------------------------------------//
            echo "Current Directory:". $_SERVER["PHP_SELF"]."<br>"; 
            
            $name = $email = $pass = $confPass = $str= "";//initializing vars
            $nameErr = $emailErr = $passErr = $confPassErr = "";
            $fillFlag = false;
            
            //echo "<h1>inside main()</h1><br>";
            if($_SERVER['REQUEST_METHOD']=='POST')
            {//CAUTION::POST must be Capitalized and use /'/ single quation
                
                //if any field is empty show an error
                /*This if else block is no longer needed because of using "required" attribute in form
                if(empty($_POST['name']))
                {
                    $nameErr = "Field Required!!";
                    $fillFlag = true;
                }
                else
                {
                    $name = "".inputFiltering($_POST['name']);
                }
                if(empty($_POST['email']))
                {
                    $emailErr = " Field Required!!";
                    $fillFlag = true;
                }
                else
                {
                    $email = inputFiltering($_POST['email']);
                }
                if(empty($_POST['pass']))
                {
                    $passErr = " Field Required!!";
                    $fillFlag = true;
                }
                else
                {
                    $pass = inputFiltering($_POST['pass']);
                }
                if(empty($_POST['confPass']))
                {
                    $confPassErr = " Field Required!!";
                    $fillFlag = true;
                }
                else
                {
                    $confPass = inputFiltering($_POST['confPass']);
                }
                
                //if all fields are filled then write
                if($fillFlag==false)
                {
                    $str = "name:".$name.",email:".$email.",pass:".$pass.";\n";
                    //echo $str."<br>";
                    writeCredential($str, $name, $pass, $confPass);
                }
                */
                $name = inputFiltering($_POST['name']);
                $email = inputFiltering($_POST['email']);
                $pass = trim($_POST['pass']);
                $confPass = trim($_POST['confPass']);
                $str = "name:".$name.",email:".$email.",pass:".$pass.";\n";
                //echo $str."<br>";
                writeCredential($str, $name, $pass, $confPass);
                /*
                echo $name."<br>";
                echo $email."<br>";
                echo $pass."<br>";
                echo $confPass."<br>";
                */
            }
            //echo "<h1>main() ended</h1><br>"
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