<?php
// draw_board.php
function drawBoard($board) {
    $row = 0;
    $column = 0;

    for ($row=0; $row < 3; $row++) { 
        echo "<tr>";
        for ($column=0; $column < 3; $column++) { 
            $position = $row . "-" . $column;
            $sign = $board[$row][$column];
    
            // Funktionalität um bereits genutzte Felder zu deaktivieren ($sign != "" ? "disabled" : "")
            // strpos prüft ob das strong tag enthalten ist (was Gewinn anzeigt) und ändert dann die Farbe
            // der Gewinnbuttons auf rot  
            echo "<th style='width:120px; height:120px'>
                        <button name=$position value='$sign' class='btn "
                        . (strpos($sign, 'strong') ? "btn-danger" : "btn-outline-info") .
                        " pb-4 d-flex justify-content-center align-items-center' "
                        . (($sign != "" || isset($_SESSION["win"])) ? "disabled" : "") .
                        " type='submit' style='width:120px; height:120px; font-size:10rem;'> $sign </button>
                  </th>";
        
        }
    echo "</tr>";
    }
}

