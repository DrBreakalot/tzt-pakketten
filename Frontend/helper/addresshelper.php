<?php

function createAddress($addressline, $city, $postalCode, $name = null) {
    return array(
        'address' => $addressline,
        'city' => $city,
        'postal_code' => $postalCode,
        'name' => $name,
    );
}