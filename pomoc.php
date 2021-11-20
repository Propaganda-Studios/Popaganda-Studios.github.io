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
    <title>Pomoc</title>
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
            if(isset($_POST["imie"]))
            {
                $imie=$_POST["imie"];
                $tresc=$_POST["pomoc"];
                $email=$_POST["email"];

                $polaczenie=mysqli_connect("mysql.cba.pl","CyklKrebsa","krysti4n69XD","daxpl");
                $zapytanie=sprintf("INSERT INTO pomoc VALUES ('','%s','%s','%s');",
                                    mysqli_real_escape_string($polaczenie,$imie),
                                    mysqli_real_escape_string($polaczenie,$email),
                                    mysqli_real_escape_string($polaczenie,$tresc)
                                );
                $przygotowanie = mysqli_prepare($polaczenie,$zapytanie);

                if(mysqli_execute($przygotowanie))
                {
                    print("<h2>Dziękujemy za przesłanie zgłoszenia</h2>");
                }
                else
                {
                    print("<h2>Wystąpił błąd - proszę spróbować za chwilę</h2>");
                }
                mysqli_close($polaczenie);
            }
            else
            {
                print
                ('
                    <form id="ifForm" action="pomoc.php" method="POST">
                        <h2>Imię/nick</h2>
                            <input type="text" name="imie" required placeholder="Imię / Nick">
                        <h2>E-mail kontaktowy:</h2>
                            <input type="email" name="email" required placeholder="e-mail">
                        <h2>Jak chcesz pomóc?</h2>
                            <input type="text" name="pomoc" required placeholder="W czym możesz pomóc ?">
                        <br />
                        <br />
                        <input type="submit" value="Zgłoś się!" id="b_zapisz">
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