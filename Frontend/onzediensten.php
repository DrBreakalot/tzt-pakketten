<?php
require_once('helper/userhelper.php');
?>

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
        </div>
        <div id="menu">  
            <ul>
                <?php echo getMenu(); ?>

            </ul>
            <div class="midden" id="middenOnzediensten">
                <span id="pakketdiensten">Pakketdiensten</span><br/><br/>
                <div id="pakketdienstenTekst">Door onze unieke concept mogen wij ons de duurzaamste transportbedrijf
                    van Nederland noemen. Hoe wij dat doen? Nou, door de helft van onze kilometers over het spoor te
                    vervoeren op trajecten die daar sowieso rijden.</div><br/><br/>
                <span id="extraVerzekering">Extra verzekering</span><br/><br/>
                <div id="extraVerzekeringTekst">Goederen zijn tot € 100,00 verzekerd. Boven dit bedrag kunt u tegen 
                    vergoeding bijverzekeren tot een waarde van € 1.000,00.</div><br/><br/>
                <span id="hoeWerktHet">Uw transport in 5 minuten geregeld!</span><br/><br/>
                <div id="hoeWerktHetTekst">
                    Stap 1: Login of registreer u als nieuwe gebruiker<br/><br/>
                    Stap 2: Kies voor "Een pakket aanbieden"<br/><br/>
                    Stap 3: Vul uw pakket- en aflevergegevens in.<br/><br/>
                    Stap 4: Als u akkoord bent met de prijs is uw transport geregeld.
                </div>
            </div>

        </div>

        <?php
        // put your code here
        ?>

    </body>
</html>




