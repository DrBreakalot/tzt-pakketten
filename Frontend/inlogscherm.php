<!DOCTYPE html>
<html>



    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stijl.css" />
        <title>Home TZT</title>
    </head>
    <body>
        <div>
            <div id="logo">
                <ul>  
                    <li><a href="index.php" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200" HEIGHT="74"></a></li>  

                </ul>  

            </div>

            <span id="rcorners2">
                <a href=""><img src="magnifier_16.png" width="16" height="16"></a>
                search
            </span>
        </div>
        <div id="menu">  
            <ul>  
                <li><a href="index.php" title="Home">Home</a></li>  
                <li><a href="/onze diensten/" title="Onze diensten">Onze diensten</a></li>  
                <li><a href="/mijn pakket/" title="Mijn Pakket">Mijn Pakket</a></li>  
                <li><a href="contact" title="Contact">Contact</a></li>

            </ul>
            <span id="inlogscherm">

                <form id="InlogForm" method="get" action="verwerk.php">

                    <label for "gebruikersnaam">Gebruikersnaam:</label>
                    <input type="text" name="gebruikersnaam"><br/><br/>
                    <label for "wachtwoord">Wachtwoord:</label>
                    <input type="text" name="wachtwoord"><br/><br/>

                    <span id="tekstWachtwoordVergeten" href="" method="get" action="verwerk1.php">
                        Wachtwoord vergeten? Klik hier</span><br/>
                    <br/>
                    <input id="inloggen2" type="submit" value="Inloggen">
                </form>
            </span>




        </div>



        <?php
        // put your code here
        ?>

    </body>
</html>
