<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>verify</title>

        <!-- Bootstrap -->
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/css/verify.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">

            <form class="form-verify" action="./index.php" method="post">
                <h2 class="form-verify-heading">验证</h2>
                <label for="verify-code" class="sr-only">验证码</label>
                <input id="verify-code" class="form-control" name="verify_code" placeholder="验证码" required="" autofocus="">
                <img src="./captcha.php">
                <button class="btn btn-lg btn-primary btn-block" type="submit">验证</button>
            </form>

<form class="form-horizontal form-verify" role="form">
    <h2 class="form-verify-heading">验证</h2>
    <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
    <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
    </div>
    </div>
    <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
    <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
    </div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    <div class="checkbox">
    <label>
    <input type="checkbox"> Remember me
    </label>
    </div>
    </div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-default">Sign in</button>
    </div>
    </div>
</form>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="./assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(() => {
                
            });
        </script>
    </body>
</html>