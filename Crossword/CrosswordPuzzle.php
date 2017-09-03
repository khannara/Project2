<?php
	require("CrosswordPuzzleMaker.php");
	require("word_processor.php");
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
		// Set starting variables gotten from post
		$title = $_POST["title"];
		$subtitle = $_POST["subtitle"];
		$height = $_POST["height"];
		$width = $_POST["width"];
		$puzzleType = $_POST["puzzletype"];
		$wordHintList = $_POST["wordInput"];
		
		// Set defaults if they weren't set on index page
		if($title == "" || $title == null){
			$title = "Crossword Puzzle";
		}
		
		if($subtitle == "" || $subtitle == null){
			$subtitle = "SILC Crossword";
		}
		
		if($height == 0 || $height == null){
			$height = 10;
		}
		else if($height > 100){
			$height = 100;
		}
		
		if($width == 0 || $width == null){
			$width = 10;
		}
		else if($width > 100){
			$width = 100;
		}
		
		// Create an array of words paired with hints
		// $words[i][0] is the word, $words[i][1] is the hint
		$words = generateWordList($wordHintList);
		
		// Creates a few Crossword Puzzles and then keeps the one with the most placed words
		$crosswordMaker = new CrosswordPuzzleMaker($width, $height, $words);

		// Get puzzle/solution details from the Crossword Maker
		$solution = $crosswordMaker->getSolution();
		$puzzle = $crosswordMaker->getPuzzle();
		$puzzleNumbers = $crosswordMaker->getPuzzleNumbers();
		$fillinHintList = $crosswordMaker->getFillInHints();		
		$unplacedWords = $crosswordMaker->getUnplacedWords();
		$skeletonHints = $crosswordMaker->getSkeletonHints();
		
		// Set count values to 0 - used for setting the middle divider between Across and Down hint lists
		// Depending on which value has the highest count determines if the border will appear on right/left side.
		$wordsAcrossCount = 0;
		$wordsDownCount = 0;
	}
	// If visiting for the first time by skipping the index page redirect them to it
	else{
		$url = "index.php";
		
		header("Location: ".$url);
		die();
	}
	
	// Generates the word list for words paired with hints
	// Splits word from hint by taking the sides from the first comma, then trims extra space from each
	// Returns array in format word[i][0] = word, word[i][1] = hint
	function generateWordList($wordInput){
		$words = [];
		$wordLine = [];
		
		$lines = explode("\n", $wordInput);
		
		foreach($lines as $line){
			
			$word = strtolower(trim(strstr($line, ',', true)));
			$hint = trim(ltrim(strstr($line, ','), ','));
			

			if(!(empty($word) || empty($hint))){				
				$wordLine[0] = $word;
				$wordLine[1] = $hint;
				array_push($words, $wordLine);
			}
			
		}
		
		return $words;
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
    <link rel="stylesheet" type="text/css" href="spectrum.css">
    <script type="text/javascript" src="spectrum.js"></script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    
    <title>Crossword Puzzle</title>
	
	<style>	
		.jumbotron {
			background-image: url("silcHeader.png");
			-webkit-background-size: 100% 100%;
			-moz-background-size: 100% 100%;
			-o-background-size: 100% 100%;
			background-size: 100% 100%;
			height: 179px;
		}
		
		table.crossword tr td {
			width: 48px;
			height: 48px;
			font-size: 1.875em;
			vertical-align:middle;
			text-align: center;
		}
		
		table.puzzle tr td {
			width: 48px;
			height: 48px;
			font-size: 20px;
			vertical-align: top;
			text-align: left;
		}

		td.filled {
			border: 2px solid #000000;
			background-color: #EEEEEE;
		}
		
		td.unfilled {
			border: 0px solid #FFFFFF; 
			background-color: #FFFFFF;
			visibility: hidden;
		}    

        .wordhints{
            border: 2px solid #000000; 
            overflow:auto;
        }
        
        .wordhints .crosswordHintsBorderAcross{
            border-right: 2px dashed #000000; 
        }
		
		.wordhints .crosswordHintsBorderDown{
            border-left: 2px dashed #000000; 
        }
		
		.warningmessage {
            border: 2px solid red;
            color: red;
            overflow: auto;
        }
	</style>
</head>
<body>
    <div class="container-fluid">
        <div class="jumbotron" id="jumbos">
        </div>
		<div align="center" class="warningmessage" 
			<?php
				// Display warning message only if there are unplaced words
				if(sizeof($unplacedWords) == 0){
					echo('style="display: none;"');
				}
			?>>
			<div class="col-sm-12">
				<h3> Warning - The following words could not be placed </h3>
				<?php
					// Print unplaced words
					foreach($unplacedWords as $word) {
						echo("<h4>".$word."</h3>");
						
					}
				?>
			</div>	
        </div>
		<br>
        <div class="panel">
            <div class="panel-group">
                <div class="panel panel-primary">
					
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Crossword Puzzle</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div align="center">
                            <h3><?php echo($title);?></h3>
                        </div>
                        <div align="center">
                            <h4><?php echo($subtitle);?></h4>
                        </div>
                        <div align="center">
                            <table id="grid" class="crossword puzzle crosswordPuzzle">
								<?php
									// Print the crossword puzzle
									foreach ($puzzle as $key => $row) 
									{		
										echo'<tr>';
										foreach ($row as $k => $val){
											if($val != "0"){
												echo'<td class="filled">'.$val.'</td>
												';
											}
											else{
												echo'<td class="unfilled"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
												';
											}
										}
										echo'</tr>';
									}
								?>
							</table>
                        </div>
						<div align="center">
                            <table id="grid" class="crossword puzzle skeletonPuzzle">
								<?php
									// Print the crossword puzzle
									foreach ($puzzle as $key => $row) 
									{		
										echo'<tr>';
										foreach ($row as $k => $val){
											if($val != "0"){
												echo'<td class="filled">&nbsp;&nbsp;&nbsp;&nbsp;</td>
												';
											}
											else{
												echo'<td class="unfilled"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
												';
											}
										}
										echo'</tr>';
									}
								?>
							</table>
                        </div>
						<br><br>
						<h2 align="center"> Hints <h2>
						<div align="center" class="wordhints crosswordHints">
							<div class="col-sm-6 crosswordHintsBorderAcross">
								<div class="row">
									<div class="col-sm-12">
										<h3>Across</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" style="text-align:left;">
										<?php
											// Print hints going across for crossword puzzle
											foreach($puzzleNumbers as $placedWord) {
												if($placedWord[3] == "right"){
													echo("<h4>".$placedWord[5].") ".$placedWord[6]."</h4><br>");
													$wordsAcrossCount++;
												}
											}
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-6 crosswordHintsBorderDown">
								<div class="row">
									<div class="col-sm-12">
										<h3>Down</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" style="text-align:left;">
										<?php
											// Print hints going down for crossword puzzle
											foreach($puzzleNumbers as $placedWord) {
												if($placedWord[3] == "down"){
													echo("<h4>".$placedWord[5].") ".$placedWord[6]."</h4><br>");
													$wordsDownCount++;
												}
											}
										?>                                        
									</div>
								</div>
							</div>
                        </div>
						<div align="center" class="wordhints fillinHints">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<h3>Fill-In Words</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" style="text-align:left;">
										<?php
										
											// Print hints going across for fillin puzzle
											// Print 4 categories per row

											$currentNum = null;
											$currentIteration = 0;
											
											foreach($fillinHintList as $placedLocation) {
												if($currentNum != $placedLocation[4]){
													$currentNum = $placedLocation[4];
													
													// If 4th iteration then start new row
													if($currentIteration % 4 == 0){
														// If first time looping, start first row
														if($currentIteration == 0){
															echo('<div class="row">');
														}
														// Close previous length div and previous row, start new row
														else{
															echo('</div></div><div class="row">');
														}
														// Start new length div
														echo('<div class="col-sm-3"><h3><u>'.$currentNum.' Length Words</u></h3>');
													}
													// Close previous length div, start new one
													else{
														echo('</div><div class="col-sm-3"><h3><u>'.$currentNum.' Length Words</u></h3>');
													}													
													
													$currentIteration++;
												}
												
												// Place word
												echo("<h4>".$placedLocation[0]."</h4>");
											}
											
											// Close current row and length divs
											echo('</div></div>');
										?>
									</div>
								</div>
							</div>
						</div>
						<div align="center" class="wordhints skeletonHints">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<h3>Skeleton Characters</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12" style="text-align:center;">
										<?php
											foreach($skeletonHints as $char){
												echo(' '.$char.' ');
											}
										?>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div align="center"><h2>Crossword Options</h2></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3>Puzzle Options</h3>
                                        </div>
                                    </div>
                                    <div align="left">
                                        <div class="row">
                                            <div class="col-sm-12" >
                                                <input type="checkbox" class="showSolutionCheckbox" onchange="solutionCheckboxChange()" checked> Show Solution
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="checkbox" class="showBlankSquaresCheckbox"  onchange="blankSquareCheckboxChange()"> Show blank squares
                                            </div>
                                        </div>
										<br>
										<div class="row">
											<div class="col-sm-3">
												<select class="form-control" id="puzzletype" name="puzzletype" onchange="puzzleHintsChange()">
													<option value="crossword" <?php if($puzzleType == "crossword"){echo('selected="selected"');} ?>>Crossword</option>
													<option value="fillin" <?php if($puzzleType == "fillin"){echo('selected="selected"');} ?>>Fill-In</option>
													<option value="skeleton" <?php if($puzzleType == "skeleton"){echo('selected="selected"');} ?>>Skeleton</option>
												</select>
											</div>
										</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3>Look Options</h3>
                                        </div>
                                    </div>
                                    <div align="left" >
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Blank Square Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='blankSquareColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Letter Square Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterSquareColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Letter Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='letterColor'/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-6" >
                                                <label>Line Color</label>
                                            </div>
                                            <div class="col-sm-6" >
                                                <input type="text" class='lineColor'/>
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
									<div align="center"><h2>Crossword Solution</h2></div>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div align="center">
								<h3><?php echo($title);?></h3>
							</div>
							<div align="center">
								<h4><?php echo($subtitle);?></h4>
							</div>
							<div align="center">
								<table id="grid" class="crossword">
									<?php
										// Display the solution
										foreach ($solution as $key => $row) 
										{		
											echo'<tr>';
											foreach ($row as $k => $val){
												if($val != "0"){
													echo'<td class="filled">'.$val.'</td>
													';
												}
												else{
													echo'<td class="unfilled"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
													';
												}
											}
											echo'</tr>';
										}
									?>
								</table>
							</div>
							<br><br>
							<h2 align="center"> Words <h2>
							<div align="center" class="wordhints">
								<div class="col-sm-6 crosswordHintsBorderAcross">
									<div class="row">
										<div class="col-sm-12">
											<h3>Across</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12" style="text-align:left;">
											<?php
												// Display the solution words going across
												foreach($puzzleNumbers as $placedLocation) {
													if($placedLocation[3] == "right"){
														echo("<h4>".$placedLocation[5].") ".$placedLocation[0]."</h4><br>");
													}
												}
											?>
										</div>
									</div>
								</div>
								<div class="col-sm-6 crosswordHintsBorderDown">
									<div class="row">
										<div class="col-sm-12">
											<h3>Down</h3>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12" style="text-align:left;">
											<?php
												// Display the solution words going down
												foreach($puzzleNumbers as $placedLocation) {
													if($placedLocation[3] == "down"){
														echo("<h4>".$placedLocation[5].") ".$placedLocation[0]."</h4><br>");
													}
												}
											?>                                        
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
	// Set default spectrum elements
	$(".blankSquareColor").spectrum({
		color: "#FFFFFF",
		change: function(color) {
			$(".unfilled").css("background-color", color.toHexString());
		}
	});

	$(".letterSquareColor").spectrum({
		color: "#EEEEEE",
		change: function(color) {
			$(".filled").css("background-color", color.toHexString());
		}
	});
	
	$(".letterColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".filled").css("color", color.toHexString());
		}
	});
	
	$(".lineColor").spectrum({
		color: "#000000",
		change: function(color) {
			$(".filled").css("border", "2px solid " + color.toHexString());
			
			// Only change hidden lines if they're showing - need to remain white for copy and pasting to word if hidden
			if($(".unfilled").css("visibility") === "visible"){
				$(".unfilled").css("border", "2px solid " + color.toHexString());
			}
			
		}
	});

	// Set the way the middle border in the across/down hints section works
	// The border needs to be positioned on the side with the most words since the border does not fill the whole space otherwise
	<?php
		if($wordsAcrossCount >= $wordsDownCount){
			echo('$(".crosswordHintsBorderAcross").css("border-right", "2px solid #000000");');
			echo('$(".crosswordHintsBorderDown").css("border-left", "0px solid #000000");');
		}
		else{
			echo('$(".crosswordHintsBorderAcross").css("border-right", "0px solid #000000");');
			echo('$(".crosswordHintsBorderDown").css("border-left", "2px solid #000000");');
		}
		
		if($puzzleType == "crossword"){
			echo('$(".crosswordHints").show();');
			echo('$(".fillinHints").hide();');
			echo('$(".skeletonHints").hide();');
			
			echo('$(".crosswordPuzzle").show();');
			echo('$(".skeletonPuzzle").hide();');
		}
		else if($puzzleType == "fillin"){
			echo('$(".crosswordHints").hide();');
			echo('$(".fillinHints").show();');
			echo('$(".skeletonHints").hide();');
			
			echo('$(".crosswordPuzzle").show();');
			echo('$(".skeletonPuzzle").hide();');
		}
		else{
			echo('$(".crosswordHints").hide();');
			echo('$(".fillinHints").hide();');
			echo('$(".skeletonHints").show();');
			
			echo('$(".crosswordPuzzle").hide();');
			echo('$(".skeletonPuzzle").show();');
		}
	?>

	$(".crossword").css("border", "2px solid " + $(".lineColor").spectrum('get').toHexString());
	
	// Updates the solution section to hidden/visable on check box update
	function solutionCheckboxChange(){
		if($('.showSolutionCheckbox').is(":checked")){  
			$(".solutionSection").show();
		}
		else{
			$(".solutionSection").hide();
		}
	}
	
	// Updates the solution section to hidden/visable on check box update
	function blankSquareCheckboxChange(){
		if($('.showBlankSquaresCheckbox').is(":checked")){  
			$(".unfilled").css("visibility", "visible");
			$(".unfilled").css("border", "2px solid " + $(".lineColor").spectrum('get').toHexString());

		}
		else{
			$(".unfilled").css("visibility", "hidden");
			$(".unfilled").css("border", "0px solid #FFFFFF"); //+ $(".lineColor").spectrum('get').toHexString());
		}
	}
	
	// Updates puzzle to show solution or fill-in puzzle hints
	function puzzleHintsChange(){
		if($('#puzzletype').val() == "crossword"){  
			$(".crosswordHints").show();
			$(".fillinHints").hide();
			$(".skeletonHints").hide();
			
			$(".crosswordPuzzle").show();
			$(".skeletonPuzzle").hide();
		}
		else if($('#puzzletype').val() == "fillin"){  
			$(".crosswordHints").hide();
			$(".fillinHints").show();
			$(".skeletonHints").hide();
			
			$(".crosswordPuzzle").show();
			$(".skeletonPuzzle").hide();
		}
		else{
			$(".crosswordHints").hide();
			$(".fillinHints").hide();
			$(".skeletonHints").show();
			
			$(".crosswordPuzzle").hide();
			$(".skeletonPuzzle").show();
		}
	}
</script>
</html>