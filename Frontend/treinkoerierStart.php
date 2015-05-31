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
            <span id="welkom">
                <a href=""></a>
            </span>
        </div>
        <div id="menu">  
            <ul>
                <?php echo getMenu(); ?>
            </ul>
            <div class="midden">


                <a href="inlogSchermTreinkoerier.php">
                    <input id="inloggenTreinkoerier" type="button" value="Inloggen Treinkoerier" />
                </a>
                <a href="registreerTreinkoerier.php">
                    <input id="registrerenTreinkoerier" type="button" value="Registreren treinkoerier" />
                </a>
                </a>

            </div>




        </div>



        <?php
        // put your code here
        ?>

    </body>
</html>


