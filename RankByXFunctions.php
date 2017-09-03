<?php

class RankByXFunctions
{
    private $wordsInputArray;
    private $maxcols = 4;
    private $sizeOfWordsInputArray;
    private $wordProcessor;

    public function __construct($wordsInputArray)
    {
        $this->wordsInputArray = $wordsInputArray;
        $this->sizeOfWordsInputArray = count($this->wordsInputArray);
        $this->wordProcessor = new wordProcessor(" ", "telugu");
    }

    function getHitCountBetweenWordsSimple($word1, $word2)
    {
        $lengthOfWord = strlen($word1);
        $this->wordProcessor->setWord($word2, "telugu");

        for ($j = 0; $j <= $lengthOfWord; $j++) {
            $char = substr($word1, $j, 1);
            $hitCount = $this->wordProcessor->containsChar($char);

            if ($hitCount) {
                return $char;
            }
        }

        return null;
    }

    function getHitCountBetweenWordsAdvance($word1, $word2)
    {
        $charactersFound = array();
        $lengthOfWord = strlen($word1);
        $this->wordProcessor->setWord($word2, "telugu");
        $count = 0;

        for ($j = 0; $j <= $lengthOfWord; $j++) {
            $char = substr($word1, $j, 1);
            $hitCount = $this->wordProcessor->containsChar($char);

            if ($hitCount) {
                $count++;
                array_push($charactersFound, $char);
            }
        }

        return $charactersFound;
    }

    function getHitCount($isSimpleMode)
    {
        for ($firstWord = 0; $firstWord < $this->sizeOfWordsInputArray; $firstWord++) {
            $count = 0;
            echo $this->wordsInputArray[$firstWord] . "-";
            $lengthOfWord = strlen($this->wordsInputArray[$firstWord]);

            for ($secondWord = 0; $secondWord < $this->sizeOfWordsInputArray; $secondWord++) {
                if ($firstWord == $secondWord)
                    continue;

                echo $this->wordsInputArray[$secondWord] . "-";
                $this->wordProcessor->setWord($this->wordsInputArray[$secondWord], "telugu");

                for ($j = 0; $j <= $lengthOfWord; $j++) {
                    $char = substr($this->wordsInputArray[$firstWord], $j, 1);
                    $hitCount = $this->wordProcessor->containsChar($char);

                    if ($hitCount) {
                        $count++;

                        if ($isSimpleMode)
                            break;
                    }
                }
            }
            echo $count;
            echo "<br />\n";
        }

        return $count;
    }

    function wordContainsChar($word, $char)
    {
        $this->wordProcessor->setWord($word, "telugu");
        return $this->wordProcessor->containsChar($char);
    }


    function generateSolutionTable()
    {
        for ($arrayIndex = 0; $arrayIndex < $this->sizeOfWordsInputArray; $arrayIndex++) {
            echo "<tr>";

            for ($column = 0; $column < $this->maxcols; $column++) {

                if ($column == 0)
                    echo "<td>" . $this->wordsInputArray[$arrayIndex] . "</td>";
                else
                    echo "<td>Filler</td>";
            }
            echo "</tr>";
        }
    }

    function boldLetterInWord($word, $letter) {
        return preg_replace("/$letter/i", '<b>$0</b>', $word);
    }
}