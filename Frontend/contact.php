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
            <div class="midden" id="middenContact">
                <div id="contactGegevens">Contactgegevens<br/><br/>
                TZT Nederland<br/>
                Campus 2-6<br/>
                8017 CA Zwolle<br/>
                <br/>
                Tel: 0900-8899<br/>
                E-mail: tzt@tzt.nl<br/>
                
                </div>
                
                
            </div>
        </div>
    </body>
</html>

