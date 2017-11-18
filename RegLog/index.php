<?php session_start();?>
<!DOCTYPE html>
<html>
    <head>
        <title>Hotel Spotter</title>
        <style>
            body{
                margin:10;
                padding:10;
            }
            .LogReg{
                font-size: 25px;
                text-align: center;
            }
            .LogReg a{
                float: right;
                align-content: center;
                /*margin: top right buttom left*/
                margin:0px 0px 0px 5px;
            }
            nav a{
                float:unset;
                align-content: center;
            }
        </style>
    </head>
    <body>
        <div class="LogReg">
            Welcome to Hotel Picker
            <a href="login.php">Login</a>
            <a href="reg.php">Sign up</a>
        </div>
    </body>
</html>