<?php 
    //-------------Essentials-------------------------//
    session_start();

    function createDatabaseConnection()
    {
        $con = new mysqli("localhost", "root", "", "test");
        if ($con->connect_error) 
        {
            die("Database Connection Failed: " . $con->connect_error);
            return null;
        }
        return $con;
    }
    $dbconnection = createDatabaseConnection();
    function inputFiltering($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    //-----------------------------------------------//
    
    function retrieveInfo($UnameCheck)
    {
        GLOBAL $dbconnection;
        //Preparing and binding qurery
        $stmt = $dbconnection->prepare("SELECT `firstname`, `lastname`, `email`, `password` FROM `credentials` where `username` = ?");
        //$stmt = "SELECT `firstname`, `lastname`, `email`, `password` FROM `credentials` where `username` = $UnameCheck";
        $stmt->bind_param("s", $UnameCheck);
        //Executing Query
        $stmt->execute();

        //Loading Results
        $result = $stmt->get_result();
        if($result->num_rows > 0){ //name exists
            $row = $result->fetch_assoc();
            $credentials = array("fname" => $row["firstname"], "lname" => $row["lastname"], "email" => $row["email"], "pass"=>$row["password"]);
            return $credentials;
        }
        else
        { //name does not exists
            return null;
        }
    }
    if($_SERVER['REQUEST_METHOD']!='POST')
    {$cred = retrieveInfo($_SESSION['username']);}
    
    function getUname(){ return $_SESSION['username'];}
    function getFname(){ GLOBAL $cred; return $cred['fname'];}
    function getLname(){ GLOBAL $cred; return $cred['lname'];}
    function getEmail(){ GLOBAL $cred; return $cred['email'];}
    function getPass() { GLOBAL $cred; return $cred['pass'];}
    function updateDatabase($uname, $fname, $lname, $email, $pass)
    {
        GLOBAL $dbconnection;
        //Preparing and binding qurery
        $stmt = $dbconnection->prepare("UPDATE `credentials` SET `FIRSTNAME` = ?, `LASTNAME` = ?, `EMAIL` = ?, `PASSWORD` = ? WHERE `credentials`.`USERNAME` = ?");
        $stmt->bind_param("sssss", $fname, $lname, $email, $pass, $uname);

        //Executing Query
        return ($stmt->execute())?true: false;
    }

    if($_SERVER['REQUEST_METHOD']=='POST')
    {          
        //filtering input
        $uname = "".inputFiltering($_POST['uname']);
        $fname = "".inputFiltering($_POST['fname']);
        $lname = "".inputFiltering($_POST['lname']);
        $email = "".inputFiltering($_POST['email']);
        $pass  = trim($_POST['pass']);
        if(updateDatabase($uname, $fname, $lname, $email, $pass))
        {
            echo "<script>giveUpdateSuccessfullAlert()</script>";
        }
        else 
        {
            echo "<script>giveUpdateUnSuccessfullAlert()</script>";
        }
        
        $cred = retrieveInfo($_SESSION['username']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>update profile</title>
    <script>
        function giveUpdateSuccessfullAlert(){
            alert("Information Updated");
        }
        function giveUpdateUnSuccessfullAlert(){
            alert("Information Updated Failed");
        }
    </script>
</head>
<body>
    
    <div class="inputBox">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <table>
                <tr><td>User Name: </td><td><?php echo getUname()?></td></tr>
                <input type="hidden" name="uname" value=<?php echo getUname()?>>
                
                <tr><td>First Name: </td><td> <input type="text"     name="fname" value="<?php echo getFname()?>"  required> </td></tr>
                
                <tr><td>Last Name: </td><td> <input type="text"     name="lname" value="<?php echo getLname()?>"  required> </td></tr>
                
                <tr><td>Email: </td><td> <input type="text"     name="email" value="<?php echo getEmail()?>"  required></td></tr>
                
                <tr><td>Password: </td><td> <input type="password" name="pass" value="<?php echo getPass()?>"  required> </td></tr>
                
                <tr><td colspan=2><input id = "submit" type="submit"value="Sign up"></td></tr>
            </table>
        </form>
    </div>

    <a href="logout.php">logout</a>
</body>

</html>