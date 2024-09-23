<!-- INFO: HTML Struktur des Spielfelds -->

<?php 
require_once __DIR__ . '/../router.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../public/index.php");
    exit();
} 
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='x-ua-compatible' content='ie=edge'>
    <title>Tic-Tac-Toe</title>
    <meta name='super game classic' content=''>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
  </head>
  
  <body>
    <h2 class="mt-4 text-center"> Tic-Tac-Toe </h2>

    <!-- Spielfeld -->
    <div class='container mt-4 d-flex justify-content-center align-items-center'>
      <table class="border border-black border-3">
        <form action='../router.php' method='post'>
          <!-- Rufe init mit der Methode GET in process.php auf -->
          <?php init("GET") ?>
          <!-- Simuliere POST, wenn der Computer am Zug ist und noch kein Gewinn vorliegt -->
          <?php isset($_SESSION["modus"]) && Helper::isEven($_SESSION["round"] + $_SESSION["beginner"]) && !isset($_SESSION["winMsg"]) && init("POST") ?>
        </form>
      </table>
    </div>
 
    <!-- Rücksetzbutton   -->
    <div class='container mt-4 d-flex justify-content-center align-items-center'>
        <form action='../router.php' method='post'>
        <button type='submit' name='reset' class="btn btn-outline-success m-4"> Zurücksetzen </button>
      </form>
    </div>

    <!-- Feld für Nachricht über Spielausgang   -->
    <div class="container d-flex justify-content-center align-items-center">
      <?php echo isset($_SESSION["winMsg"]) ? $_SESSION["winMsg"] : "" ?>
    </div>

    <!-- <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script> -->
  </body>
</html>
