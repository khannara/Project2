<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="RankByX.css" rel="stylesheet" type="text/css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    <title>RankByX</title>

</head>
<body>
<form target="_blank" action="RankBYX.php" method="post" class="form-horizontal">
    <div class="container-fluid">
        <div class="jumbotron" id="jumbos">
        </div>
        <div class="panel">
            <div class="panel-group">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Rank by Intersection</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">


                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <label class="control-label col-sm-9" style="text-align: left;">Enter your words here (one
                                per
                                line).
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="10" id="input" name="wordInput"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="charLabel" style="color:red;font-size:14px" name="charName" value="">
                                        <?php
                                        // If there is a warning message after input validation display message to user
                                        if (isset($warningMessage)) {
                                            echo($warningMessage);
                                        }
                                        ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center">
                                <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Rank Words">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>