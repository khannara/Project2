<?php
require("word_processor.php");
require("RankByXFunctions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Set starting variables gotten from post
    $wordInputFromTextBox = $_POST["wordInput"];

    $wordsArray = explode("\n", $wordInputFromTextBox);

    $rankByXFunction = new RankByXFunctions($wordsArray);
} else {
    $url = "index.php";

    header("Location: " . $url);
    die();
}
?>


<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- Spectrum -->
    <link href="RankByX.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="spectrum.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">

    <title>RankByX</title>
</head>
<body>
<div class="container-fluid">
    <div class="jumbotron" id="jumbos">
    </div>
    <br>
    <div class="panel">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12">
                            <div align="center"><h2>Rank By Intersection (Puzzle)</h2></div>
                        </div>
                    </div>
                </div>
                <p>Rank words by intersections.</p>
                <?php

                $rows = sizeof($wordsArray);

                for ($input = 0; $input < $rows; $input++) {
                    $incrementedValue = $input + 1;
                    echo "$incrementedValue. $wordsArray[$input]";
                    echo "<br />\n";
                }
                ?>
                <br>

                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12">
                            <div align="center"><h2>Rank By Intersection (Options)</h2></div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12" align="center">
                            <div class="col-sm-6">
                                <div class="row">
                                </div>
                                <div align="left">
                                    <div class="row">
                                        <div id="myRadioGroup">
                                            <input type="radio" name="rank" checked="checked" value="1"/>Simple Rank
                                            <input type="radio" name="rank" value="2"/>Advance Rank
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="panel panel-primary solutionSection">
                    <div class="panel-heading ">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Ranked By Intersection (Solution)</h2></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="Rank1" class="desc">
                    <?php
                    $hitCountSimple = $rankByXFunction->getHitCount(true);
                    ?>
                    <table align="center">
                        <tr>
                            <th>Rank</th>
                            <th>X Count</th>
                            <th>Word</th>
                            <th>Intersections</th>
                        </tr>
                        <?php
                        $rankByXFunction->generateSolutionTable();
                        ?>
                    </table>

                </div>

                <div id="Rank2" class="desc" style="display: none;">
                    <?php
                    $hitCountSimple = $rankByXFunction->getHitCount(false);
                    ?>
                    <table align="center">
                        <tr>
                            <th>Rank</th>
                            <th>X Count</th>
                            <th>Word</th>
                            <th>Intersections</th>
                        </tr>
                        <?php
                        //                        $rankByXFunction->generateSolutionTable();
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $("input[name$='rank']").click(function () {
            var test = $(this).val();

            $("div.desc").hide();
            $("#Rank" + test).show();
        });
    });

    // Set default spectrum elements
    $(".blankSquareColor").spectrum({
        color: "#FFFFFF",
        change: function (color) {
            $(".unfilled").css("background-color", color.toHexString());
        }
    });

    $(".letterSquareColor").spectrum({
        color: "#EEEEEE",
        change: function (color) {
            $(".filled").css("background-color", color.toHexString());
        }
    });

    $(".letterColor").spectrum({
        color: "#000000",
        change: function (color) {
            $(".filled").css("color", color.toHexString());
        }
    });

    $(".lineColor").spectrum({
        color: "#000000",
        change: function (color) {
            $(".filled").css("border", "2px solid " + color.toHexString());

            // Only change hidden lines if they're showing - need to remain white for copy and pasting to word if hidden
            if ($(".unfilled").css("visibility") === "visible") {
                $(".unfilled").css("border", "2px solid " + color.toHexString());
            }

        }
    });

    $(".crossword").css("border", "2px solid " + $(".lineColor").spectrum('get').toHexString());
</script>
</html>