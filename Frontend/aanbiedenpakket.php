<?php
require_once 'helper/authhelper.php';
require_once('helper/userhelper.php');

$user = requireUser();

$posted = $_SERVER["REQUEST_METHOD"] == "POST";
$error = null;
if ($posted) {
    $requiredValues = array(
        'gewicht',
        'breedte',
        'lengte',
        'hoogte',
        'vanplaatsnaam',
        'vanpostcode',
        'vanadres',
        'naarplaatsnaam',
        'naarpostcode',
        'naaradres',
    );
    $allset = true;
    foreach ($requiredValues as $key) {
        if (!valueSet($key, $_POST)) {
            $allset = false;
            break;
        }
    }
    if ($allset) {
        $result = offerPackage($_POST['gewicht'], $_POST['breedte'], $_POST['lengte'], $_POST['hoogte'], $_POST['vanplaatsnaam'], $_POST['vanpostcode'], $_POST['vanadres'], $_POST['naarplaatsnaam'], $_POST['naarpostcode'], $_POST['naaradres']);
        header('Location: pakket.php?id=' . $result['package_id']);
    }
}

/**
 * Check if the array key exists, and has a length of > 0
 * @param $key string The key in the array to check
 * @param $array array The array to check
 * @return bool true if the string at $array[$key] exists and has a length of greater than 0
 */
function valueSet($key, $array) {
    return (array_key_exists($key, $array) && strlen($array[$key]) > 0);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stijl.css"/>
        <title>TZT - Aanmelden pakket</title>
    </head>
    <body>
        <div>
            <div id="logo">
                <ul>
                    <li><a href="index.php" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200" HEIGHT="74"></a>
                    </li>
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
                <form>
                    <table id="aanbiedenpakkettable">
                        <tr>
                            <th colspan="2" id="pakketgegevensheader">Pakketgegevens</th>
                            <th colspan="2" id="pakketadresheader">Adresgegevens</th>
                        </tr>
                        <tr>
                            <td><label for="gewicht">Gewicht (kg)</label></td>
                            <td><input type="text" id="gewicht" name="gewicht" tabindex="1" value="<?php if ($posted) { echo $_POST['gewicht']; } ?>"></td>
                            <th>Van</th>
                        </tr>
                        <tr>
                            <td><label for="breedte">Breedte (cm)</label></td>
                            <td><input type="text" id="breedte" name="breedte" tabindex="2" value="<?php if ($posted) { echo $_POST['breedte']; } ?>"></td>
                            <td><label for="vanadres">Adres + huisnummer</label></td>
                            <td><input type="text" id="vanadres" name="vanadres" tabindex="5" value="<?php if ($posted) { echo $_POST['vanadres']; } ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="lengte">Lengte (cm)</label></td>
                            <td><input type="text" id="lengte" name="lengte" tabindex="3" value="<?php if ($posted) { echo $_POST['lengte']; } ?>"></td>
                            <td><label for="vanpostcode">Postcode</label></td>
                            <td><input type="text" id="vanpostcode" name="vanpostcode" tabindex="6" value="<?php if ($posted) { echo $_POST['vanpostcode']; } ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="hoogte">Hoogte (cm)</label></td>
                            <td><input type="text" id="hoogte" name="hoogte" tabindex="4" value="<?php if ($posted) { echo $_POST['hoogte']; } ?>"></td>
                            <td><label for="vanplaatsnaam">Plaatsnaam</label></td>
                            <td><input type="text" id="vanplaatsnaam" name="vanplaatsnaam" tabindex="7" value="<?php if ($posted) { echo $_POST['vanplaatsnaam']; } ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <th>Naar</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><label for="naaradres">Adres + huisnummer</label></td>
                            <td><input type="text" id="naaradres" name="naaradres" tabindex="8" value="<?php if ($posted) { echo $_POST['naaradres']; } ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><label for="naarpostcode">Postcode</label></td>
                            <td><input type="text" id="naarpostcode" name="naarpostcode" tabindex="9" value="<?php if ($posted) { echo $_POST['naarpostcode']; } ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><label for="naarplaatsnaam">Plaatsnaam</label></td>
                            <td><input type="text" id="naarplaatsnaam" name="naarplaatsnaam" tabindex="10" value="<?php if ($posted) { echo $_POST['naarplaatsnaam']; } ?>"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="2"><input formmethod="post" type="submit" value="Bereken kosten" id="inloggen2" tabindex="11"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>
