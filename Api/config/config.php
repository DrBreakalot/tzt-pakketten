<?php

class Config {
    
    const DB_HOST = "localhost";
    const DB_NAME = "TZT";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const GOOGLE_API_KEY = "AIzaSyDW4TYDn82RKQ3VQFi1z_LZ6co2yeFTrSQ";

    const TRAIN_COURIER_PAYMENT = 400;
    const TRAIN_STATION_PAYMENT = 100;
    const TRAIN_COST = 600;//Config::TRAIN_COURIER_PAYMENT + 2 * Config::TRAIN_STATION_PAYMENT;
}