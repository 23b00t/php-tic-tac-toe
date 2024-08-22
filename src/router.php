<?php
// INFO: Router

// Überprüfe, ob keine Session aktiv ist, dann starte eine  
// session_status() === PHP_SESSION_NONE && session_start();

// Binde Dateien ein
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/src/models/HumanMove.php');
require_once(__ROOT__.'/src/helpers/Helper.php');
require_once(__ROOT__.'/src/models/Board.php');
require_once(__ROOT__.'/src/models/ComputerMove.php');
require_once(__ROOT__.'/src/controllers/GameController.php');
require_once(__ROOT__.'/src/controllers/LoginController.php');
require_once(__ROOT__.'/src/controllers/RegisterController.php');

// Bei POST an verarbeiten.php, rufe init mit POST als Argument auf 
$_SERVER["REQUEST_METHOD"] === "POST" && init("POST");

// HACK: Simuliere POST und GET per übergabe als String Argument an run
// --> Lösung für Züge durch Computergegener

function init($method) {
    if (!isset($_SESSION["board"])) {
        $_SESSION["board"] = (new Board())->new();
        $_SESSION["round"] = -1;
    }

    if (isset($_POST['login'])) {
        $username = $_POST['username'];  
        $password = $_POST['password'];      

        Login::loginUser($username, $password);
    }

    if (isset($_POST['register'])) {
        $username = $_POST['username'];  
        $password = $_POST['password'];      
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            Register::createUser($username, $password);
        } else {
            header('Location: views/register_form.php?error=Passwörter%20stimmen%20nicht%20überein');
            exit();
        }
    }
    
    $gameController = new GameController($_SESSION["board"], $_SESSION["round"]);
    $gameController->run($method);
}
?>
