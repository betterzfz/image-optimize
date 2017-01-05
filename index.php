<?php
    session_start();
    if (!$_SESSION['verify_code']) {
        header('Location:./verify.php');exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>index</title>

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
                        <li class="active"><a href="/">for directory</a></li>
                        <li><a href="./for_file.php">for file</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">for directory</h1>
                    <form class="form-horizontal" id="directory-form">
                        <div class="form-group">
                            <label for="source-directory" class="col-sm-2 control-label">source directory</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="source-directory" name="source_directory" placeholder="source directory" />
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
                            <label for="once-number" class="col-sm-2 control-label">once number</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="once-number" name="once_number" value="5000" min="1" />
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
                    <div class="row" id="total-report" style="display: none">
                        <div class="col-sm-2">total file count:<span id="total-file-count"></span></div>
                        <div class="col-sm-2">total handled count:<span id="total-handled-count"></span></div>
                        <div class="col-sm-2">total success count:<span id="total-success-count"></span></div>
                        <div class="col-sm-2">total failure count:<span id="total-failure-count"></span></div>
                        <div class="col-sm-2">total batch number:<span id="total-batch-number"></span></div>
                        <div class="col-sm-2">current batch number:<span id="current-batch-number"></span></div>
                    </div>
                    <div class="row" id="total-progress">
                        
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
                    $(this).attr('disabled', 'disabled');
                    $(this).html('submitting...');
                    $('#info').html('');
                    $.ajax({
                        url: './handle_dir.php',
                        type: 'post',
                        data: $('#directory-form').serialize(),
                        dataType: 'json',
                        success: data => {
                            if (data.code == 0) {
                                $('#total-file-count').html(data.data.file_count);
                                $('#total-batch-number').html(data.data.batch_number);
                                $('#total-report').show();
                                let total_progress_html = '';
                                for (let i = 1; i <= data.data.batch_number; i++) {
                                    total_progress_html +=  '<div class="progress" id="progress-' + i + '">' +
                                                                '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>' +
                                                            '</div>';
                                }
                                $('#total-progress').html(total_progress_html);
                                ajax_batch_progress(1, data.data.batch_number);
                            } else {
                                $('#info').html('<div class="alert alert-danger alert-dismissible" role="alert">' +
                                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                                                    '<strong>Warning!</strong> ' + data.message +
                                                '</div>');
                            }
                        },
                        error: (XMLHttpRequest, textStatus, errorThrown) => {
                            console.log(XMLHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                });
                const progress_interval = setInterval(() => {
                    $.ajax({
                        url: './progress.php',
                        type: 'post',
                        data: { current_batch_number : $('#current-batch-number').html(), once_number : $('#once-number').val(), total_batch_number : $('#total-batch-number').html() },
                        dataType: 'json',
                        success: data => {
                            if (data.code == 0) {
                                $('#total-handled-count').html(data.data.total_handled_count);
                                $('#total-success-count').html(data.data.total_success_count);
                                $('#total-failure-count').html(data.data.total_failure_count);
                                $('#progress-' + data.data.current_batch_number).find('div').css('width', data.data.progress + '%');
                            } else {
                                console.log(data.message);
                            }
                        },
                        error: (XMLHttpRequest, textStatus, errorThrown) => {
                            console.log(XMLHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }, 1000);
            });
            
            let ajax_batch_progress = (current_batch_number, total_batch_number) => {
                $('#current-batch-number').html(current_batch_number);
                $.ajax({
                    url: './batch_progress.php',
                    type: 'post',
                    data: $('#directory-form').serialize(),
                    dataType: 'json',
                    success: data => {
                        if (data.code == 0) {
                            if (current_batch_number == total_batch_number) {
                                $('#info').html('<div class="alert alert-success alert-dismissible" role="alert">' +
                                                    '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
                                                    '<strong>Success!</strong> ' + data.message + '.' +
                                                '</div>');
                                
                                $('#submit').html('submitting');
                                $('#submit').attr('disabled', false);
                            } else {
                                $('#progress-' + current_batch_number).find('div').css('width', '100%');
                                ajax_batch_progress(++current_batch_number, total_batch_number);
                            }
                        } else {
                            alert(data.message);
                        }
                    },
                    error: (XMLHttpRequest, textStatus, errorThrown) => {
                        console.log(XMLHttpRequest);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }
        </script>
    </body>
</html>