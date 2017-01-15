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

            <form class="form-horizontal form-verify" role="form" action="./index.php" method="post">
                <h2 class="form-verify-heading">验证</h2>
                <div class="form-group">
                    <label for="identification-number" class="col-sm-3 control-label">识别码</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="identification-number" name="identification_number" placeholder="识别码" required="" autofocus="" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="verify-code" class="col-sm-3 control-label">验证码</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="verify-code" placeholder="验证码" name="verify_code" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <img src="./captcha.php" id="captcha-image">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">验证</button>
                    </div>
                </div>
            </form>

            <div class="row">
                <?php if ($_GET['message']): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <strong>Warning!</strong><?= $_GET['message'] ?>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="./assets/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="./assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(() => {
                $('#captcha-image').click(function () {
                    $(this).attr('src', `./captcha.php?version=${Math.random()}`);
                })
            });
        </script>
    </body>
</html>