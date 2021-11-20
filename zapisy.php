<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="css/lightbox.css">
    <link rel="stylesheet" href="css/main-game.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/favicon.png">
    <title>Zapisy</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logo-pro.png" alt="logo">
            <div class="shadow-text">Propaganda <span>Leauge</span></div>
        </div>            
    </header>
    <nav>
        <ul class="first-list">
            <li><a href="index.html">Home</a></li>
            <li><a href="team.html">Team</a></li>
            <li><a href="game.html">Games</a></li> 
            <li><a href="propagandaLeauge.html">Propaganda Leauge</a></li> 
            <li><a href="aboutUs.html">About Us</a></li>
        </ul>
    </nav>
    <main>
       
    <?php
            if(isset($_POST["nazwa"]))
            {
                $nazwa=$_POST["nazwa"];
                $logo=$_POST["logo"];
                $email=$_POST["email"];
                $cs=isset($_POST["cs"]);
                $lol=isset($_POST["lol"]);
                $g1=$_POST["gracz1"];
                $g2=$_POST["gracz2"];
                $g3=$_POST["gracz3"];
                $g4=$_POST["gracz4"];
                $g5=$_POST["gracz5"];

                $polaczenie=mysqli_connect("mysql.cba.pl","CyklKrebsa","krysti4n69XD","daxpl");
                if($polaczenie==false)
                {
                    die("Nie polaczono z baza dachych");
                }
                $zapytanie=sprintf("INSERT INTO zgloszenia VALUES ('','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');",
                                    mysqli_real_escape_string($polaczenie,$nazwa),
                                    mysqli_real_escape_string($polaczenie,$logo),
                                    mysqli_real_escape_string($polaczenie,$email),
                                    mysqli_real_escape_string($polaczenie,$cs),
                                    mysqli_real_escape_string($polaczenie,$lol),
                                    mysqli_real_escape_string($polaczenie,$g1),
                                    mysqli_real_escape_string($polaczenie,$g2),
                                    mysqli_real_escape_string($polaczenie,$g3),
                                    mysqli_real_escape_string($polaczenie,$g4),
                                    mysqli_real_escape_string($polaczenie,$g5)
                                );
                $przygotowanie = mysqli_prepare($polaczenie,$zapytanie);

                if(mysqli_execute($przygotowanie))
                {
                    print("<h2>Dziękujemy za przesłanie zgłoszenia, oto Twoje zaproszenia do turnieju:</h2>");
                    if(isset($_POST['lol']) && $_POST['lol'])
                    {
                        print('<a href="https://www.faceit.com/pl/championship/cbb84c97-ecf5-4d87-aa33-8dcd29f02437/Propaganda%2520League">TURNIEJ FACEIT CS:GO</a><br />');
                    }
                    if(isset($_POST['cs']) && $_POST['cs'])
                    {
                        print('<a href="https://www.faceit.com/pl/championship/cbb84c97-ecf5-4d87-aa33-8dcd29f02437/Propaganda%2520League">TURNIEJ FACEIT CS:GO</a><br />');
                    }
                    
                    print("<h2>W razie pytań napisz do nas na studios.propaganda@gmail.com</h2>");
                }
                else
                {
                    print("<h2>Wystąpił błąd - proszę spróbować za chwilę lub napisz na studios.propaganda@gmail.com </h2>");
                }

                
            }
            else
            {
                print
                ('
                    <form id="ifForm2" action="zapisy.php" method="POST">
                        <h2>Nazwa drużyny:</h2>
                            <input type="text" name="nazwa" required placeholder="Nazwa drużyny">
                        <h2>Link do loga:</h2>
                            <input type="text" name="logo" required placeholder="Log">
                        <h2>E-mail kontaktowy:</h2>
                            <input type="email" name="email" required placeholder="e-mail">
                        <h2>Wybierz grę:</h2>
                            <input type="checkbox" id="cs_box" name="cs" >
                            <label for="cs_box"><img src="img/cs.png" alt="CS:GO" id="wybor"></label>

                            <input type="checkbox" id="lol_box" name="lol">
                            <label for="lol_box"><img src="img/lol.png" alt="LOL" id="wybor"></label>
                        <h2>Kapitan:</h2>
                            <input type="text" name="gracz1" required placeholder="Kapitan">
                        <h2>Pozostali gracze:</h2>
                            <input type="text" name="gracz2" required>
                            <input type="text" name="gracz3" required>
                            <input type="text" name="gracz4" required>
                            <input type="text" name="gracz5" required>
                        <br />
                        <br />
                        <input type="submit" value="Zapisz się!" id="b_zapisz">
                    </form>
                ');
            }
            
        ?>
    </main>
    <footer>
        <div class="footer-left">
            <div class="shadow-text-footer">Propaganda <span>Studios</span></div>
            <img src="img/logo-pro.png" alt="logo">
        </div>
        <div class="footer-right">
            <div class="info">
                <a href="">FaceBook</a>
                <a href="https://www.youtube.com/channel/UCZUEIYwa9tLdKlyakhEoodg">Youtube</a>
                <p>studios.propaganda@gmail.com</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="js/lightbox-plus-jquery.min.js"></script>
</body>
</html>