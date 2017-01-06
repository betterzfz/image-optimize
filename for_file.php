<?php
    session_start();
    if (!$_SESSION['verify_code']) {
        header('Location:./verify.php');
    }
    if (is_array($_POST) && !empty($_POST)) {
        $common_config = json_decode(file_get_contents('./config/common.json'), true);
        if ($_POST['identification_number'] != $common_config['identification_number']) {
            header('Location:./verify.php?message=invalid identification number');
        }
        if ($_POST['verify_code'] != $_SESSION['verify_code']) {
            header('Location:./verify.php?message=invalid verify code');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>image optimize demo</title>

        <!-- Bootstrap -->
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/css/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">image optimize</a>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li><a href="/">for directory</a></li>
                        <li class="active"><a href="./for_file.php">for file</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">for file</h1>
                    <form class="form-horizontal" id="file-form">
                        <div class="form-group">
                            <label for="source-file" class="col-sm-2 control-label">source file</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="source-file" name="source_file" placeholder="source file" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="destination-directory" class="col-sm-2 control-label">destination directory</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="destination-directory" name="destination_directory" placeholder="destination directory" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quality" class="col-sm-2 control-label">quality</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="quality" name="quality" value="100" max="100" min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dest-width" class="col-sm-2 control-label">dest width</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="dest-width" name="dest_width" value="0" min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dest-height" class="col-sm-2 control-label">dest height</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="dest-height" name="dest_height" value="0" min="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="flag" class="col-sm-2 control-label">flag</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="flag" name="flag">
                                    <option value="0">no</option>
                                    <option value="1">equal scaling</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-primary" id="submit">submit</button>
                            </div>
                        </div>
                    </form>
                    <div class="row" id="info">
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="./assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(() => {
                $('#submit').click(function () {
                    const this_element = $(this);
                    this_element.attr('disabled', 'disabled');
                    this_element.html('submitting...');
                    $('#info').html('');
                    $.ajax({
                        url: './handle_file.php',
                        type: 'post',
                        data: $('#file-form').serialize(),
                        dataType: 'json',
                        success: data => {
                            if (data.code == 0) {
                                $('#info').html('<div class="alert alert-success alert-dismissible" role="alert">' +
                                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                                                    '<strong>Success!</strong> ' + data.message +
                                                '</div>');
                            } else {
                                $('#info').html('<div class="alert alert-danger alert-dismissible" role="alert">' +
                                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                                                    '<strong>Warning!</strong> ' + data.message +
                                                '</div>');
                            }
                            this_element.html('submitting');
                            this_element.attr('disabled', false);
                        },
                        error: (XMLHttpRequest, textStatus, errorThrown) => {
                            console.log(XMLHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                });
            });
        </script>
    </body>
</html>