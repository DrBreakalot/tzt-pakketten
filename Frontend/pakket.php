<?php
require_once 'helper/authhelper.php';
require_once('helper/userhelper.php');

$package = null;
$user = requireUser();
if (array_key_exists('id', $_GET)) {
    if (array_key_exists('accept', $_GET)) {
        acceptPackage($_GET['id'], $_GET['accept'] === 'true');
    }
    $package = getPackage($_GET['id']);
}

?>

    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="stijl.css"/>
            <title>Home TZT</title>
        </head>
        <body>
            <div>
                <div id="logo">
                    <ul>
                        <li><a href="index.php" title="logo"><IMG SRC="Logo TZT.png" ALT="logo TZT" WIDTH="200"
                                                                  HEIGHT="74"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="menu">
                <ul>
                    <?php echo getMenu(); ?>
                </ul>
                <div class="midden" id="middenpakket">
                    <?php if ($package) { ?>
                        <table id="pakkettabel">
                            <tr id="pakketafmeting">
                                <th>Pakketafmeting</th>
                                <td colspan="3">
                                    <?php echo $package['width'] . 'x' . $package['depth'] . 'x' . $package['height'] ?>
                                </td>
                            </tr>
                            <tr id="pakketgewicht">
                                <th>Pakketgewicht</th>
                                <td>
                                    <?php echo $package['weight'] ?>kg
                                </td>
                                <th>Barcode</th>
                                <td>
                                    <?php echo $package['barcode'] ?>
                                </td>
                            </tr>
                            <tr/>
                            <tr id="pakketadresheader">
                                <th colspan="2">Van</th>
                                <th colspan="2">Naar</th>
                            </tr>
                            <tr id="pakketadres">
                                <th>Adres</th>
                                <td>
                                    <?php echo $package['route']['from']['address'] ?>
                                </td>
                                <th>Adres</th>
                                <td>
                                    <?php echo $package['route']['to']['address'] ?>
                                </td>
                            </tr>
                            <tr id="pakketplaats">
                                <th>Plaats</th>
                                <td>
                                    <?php echo $package['route']['from']['city'] ?>
                                </td>
                                <th>Plaats</th>
                                <td>
                                    <?php echo $package['route']['to']['city'] ?>
                                </td>
                            </tr>
                            <tr id="pakketstatus">
                                <th>Status</th>
                                <td>
                                    <?php $state = $package['state'];
                                    switch ($state) {
                                        case 'PREPARING':
                                            $state = 'Voorbereiden';
                                            break;
                                        case 'ACCEPTED':
                                            $state = 'Geaccepteerd';
                                            break;
                                        case 'CANCELED':
                                            $state = 'Geannuleerd';
                                            break;
                                        case 'EN_ROUTE':
                                            $state = 'Onderweg';
                                            break;
                                        case 'ARRIVED':
                                            $state = 'Bezorgd';
                                            break;
                                    }
                                    echo $state;
                                    ?>
                                </td>
                            </tr>
                            <tr id="pakketkosten">
                                <th>Kosten</th>
                                <td><?php echo formatPrice($package['paid_price']) ?></td>
                            </tr>
                            <?php if ($package['state'] === 'PREPARING') { ?>
                                <tr id="pakketknop">
                                    <td id="pakketaccepteer" colspan="2">
                                        <a href="pakket.php?id=<?php echo $package['id']?>&accept=true">Akkoord</a>
                                    </td>
                                    <td id="pakketweiger" colspan="2">
                                        <a href="pakket.php?id=<?php echo $package['id']?>&accept=false">Weigeren</a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        Pakket niet gevonden
                    <?php } ?>
                </div>
            </div>
        </body>
    </html>


<?php
function formatPrice($priceInCents)
{
    return '€' . number_format($priceInCents / 100, 2, ',', '.');
}