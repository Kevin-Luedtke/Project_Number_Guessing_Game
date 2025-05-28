<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : "home";

if ($page == "home") {
    session_unset();
};

if ($_POST && $_GET['page'] != 'game') {
    $guess = $_POST['guess'];
    $_SESSION['guess'] = $guess;
};

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Guessing Game</title>
    <link rel="stylesheet" href="NGG_Style.css">

    <script>
    function validate(event) {
        event.preventDefault();
        let guess = document.getElementById("guess").value;
        let old_guess = <?php echo $_SESSION['guess']; ?>;
        let random = <?php echo $_SESSION['random']; ?>;
        let error = "";

        if (old_guess > random && guess > old_guess) {
            error += "Der Tipp war eben schon zu hoch.";
        }

        if (old_guess < random && guess < old_guess) {
            error += "Der Tipp war eben schon zu niedrig.";
        }

        if (error) {
            alert(error);
        } else {
            document.getElementById("reTipp").submit();
        }
    }
    </script>

</head>

<body>
    <div class="container">
        <header>
            <h2> Willkommen zum Number Guessing Game </h2>
        </header>
        <main>
            <?php
            if (empty($_GET['page'])) {
            ?>
            <div class="main"> <br>
                <p> Bei diesem Spiel kannst du eine geheime Zahl erraten, </p>
                <p> klicke auf Start um das Spiel zu beginnen. </p><br>
                <a class="start" href="number_guessing_Game.php?page=start"> Start </a> <br>
            </div>
            <?php
            } else if ($_GET['page'] == "start") {
            ?>
            <div class="main"> <br>
                <p> Wir legen jetzt eine geheime Zahl fest! </p>
                <p> Bitte gib die untere und obere Grenze an, </p>
                <p> in der sich die Zahl befinden soll. </p>
                <form action="number_guessing_Game.php?page=game" method="post"><br>
                    <label for="min"> von (0-50) </label><br>
                    <input type="number" name="min" id="min" min="0" max="50" placeholder="0" required><br><br>
                    <label for="max"> bis (51-100) </label><br>
                    <input type="number" name="max" id="max" min="51" max="100" placeholder="100" required><br><br>
                    <button type="submit"> Festlegen </button>
                </form>
            </div>
            <?php
            } else if ($_GET['page'] == "game") {
                $min = $_POST['min'];
                $max = $_POST['max'];
                $_SESSION['min'] = intval($min);
                $_SESSION['max'] = intval($max);
                $random = rand($min, $max);
                $_SESSION['random'] = intval($random);
                $_SESSION['tipp'] = 1;
            ?>
            <div class="main"> <br>
                <p> Die Geheimzahl liegt zwischen <?php echo $min ?> und <?php echo $max ?>.</p>
                <p> Bitte gib jetzt deinen Tipp ab. </p>
                <br>
                <form action="number_guessing_Game.php?page=guess" method="post">
                    <label for="guess"> Dein Tipp: </label><br>
                    <input type="number" name="guess" id="guess" min="<?php echo $min ?>" max="<?php echo $max ?>"
                        placeholder="0" required><br>
                    <br>
                    <button type="submit"> Tippen </button>
                </form>
            </div>
            <?php
            } else if ($_GET['page'] == "guess") {
                $random = $_SESSION['random'];
                $min = $_SESSION['min'];
                $max = $_SESSION['max'];
                $tipp = $_SESSION['tipp'];
                $range_percent = (abs($random - $guess)*100)/$max - $min;
                ?>
            <div class="main"> <br>
                <?php
                    if ($guess == $random) {
                    ?>
                <p> Klasse, du hast richtig geraten! 🎉</p>
                <br>
                <p> Du hast <?php echo $tipp?> Versuche benötigt.</p>
                <?php
                } else {
                    $_SESSION['tipp'] += 1;
                ?>
                <p> Schade, dein Tipp ist
                    <?php
                    if ($range_percent <= 2) {
                        echo 'etwas zu';
                    } else if ($range_percent > 2 && $range_percent <= 20) {
                        echo 'viel zu';
                    } else {
                        echo 'ultra';
                    }; 
                    echo ($guess < $random) ? ' niedrig' : ' hoch';
                    ?>
                    . </p> <br>
                <p></p> <br>
                <form id="reTipp" action="number_guessing_Game.php?page=guess" method="post">
                    <label for="guess"> Versuche es erneut! </label><br>
                    <input type="number" name="guess" id="guess" min="<?php echo $min?>" max="<?php echo $max?>"
                        placeholder="0" required><br>
                    <label>( Grenzen: <?php echo $min ?> / <?php echo $max ?> | letzter Tipp: <?php echo $guess ?>
                        )</label><br><br>
                    <button onclick="validate(event)"> Tippen </button>
                </form>
                <p> <?php echo $range_percent?></p>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>
            <br>
            <div class="btn"><a href="number_guessing_Game.php"> Neustarten </a></div>
        </main>
        <footer>
            <h3> &copy by Kevin </h3>
        </footer>
    </div>
</body>

</html>