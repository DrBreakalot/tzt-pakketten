<?php
require_once 'helper/authhelper.php';
require_once('helper/userhelper.php');

$user = requireUser();
$packages = getPackages();
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
            <div class="midden" id="middenpakketten">
                <table>
                    <tr>
                        <th>Aangeboden op</th>
                        <th>Van</th>
                        <th>Naar</th>
                        <th>Status</th>
                        <th>Prijs</th>
                    </tr>
                    <?php
                    if ($packages) {
                        foreach ($packages as $package) {
                            $state = $package['state'];
                            switch($state) {
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

                            echo "<tr onclick=\"window.document.location='pakket.php?id=" . $package['id'] . "'\">";

                            echo "<td>" . formatDate($package['enter_date']) . "</td>";
                            echo "<td>" . formatLocation($package['route']['from']) . "</td>";
                            echo "<td>" . formatLocation($package['route']['to']) . "</td>";
                            echo "<td>" . $state . "</td>";
                            echo "<td>" . formatPrice($package['paid_price']) . "</td>";

                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>

<?php
function formatDate($date) {
    return date('d-m-Y', strtotime($date));
}

function formatLocation($location) {
    if (array_key_exists('name', $location) && strlen($location['name'] > 0)) {
        return $location['name'];
    }
    if (array_key_exists('city', $location) && strlen($location['city']) > 0) {
        return $location['city'];
    }
    return $location['latitude'] . ',' . $location['longitude'];
}

function formatPrice($priceInCents) {
    return '€' . number_format($priceInCents / 100, 2, ',', '.');
}