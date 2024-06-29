<?php
// INFO: Zeichne das Spielfeld (3x3) und bilde dynamisch den aktuellen Zustand davon ab  

function drawBoard($board) {
    for ($row = 0; $row < 3; $row++) { 
        echo "<tr>";
        for ($column = 0; $column < 3; $column++) { 
            // Bereite die aktuelle Position als String vor, z. B.: 2-1
            $position = $row . "-" . $column;
            // Lese das Zeichen an der Position aus, 'x' || 'o' || ''
            $sign = $board[$row][$column];
    
            // Funktionalität um bereits genutzte Felder zu deaktivieren ($sign != "" ? "disabled" : "")
            // strpos prüft ob das strong tag enthalten ist (was Gewinn anzeigt) und ändert dann die Farbe
            // der Gewinnbuttons auf rot.  
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

