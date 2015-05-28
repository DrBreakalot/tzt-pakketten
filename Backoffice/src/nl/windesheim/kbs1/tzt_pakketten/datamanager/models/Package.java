package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.Route;

import java.time.Instant;

public class Package {

    private double width;
    private double height;
    private double depth;
    private double weight;
    private String barcode;
    private Instant enterDate;
    private Money paidPrice;
    private PackageState state;
    private Route route;

    public double getWidth() {
        return width;
    }

    public double getHeight() {
        return height;
    }

    public double getDepth() {
        return depth;
    }

    public double getWeight() {
        return weight;
    }

    public String getBarcode() {
        return barcode;
    }

    public Instant getEnterDate() {
        return enterDate;
    }

    public Money getPaidPrice() {
        return paidPrice;
    }

    public PackageState getState() {
        return state;
    }

    public Route getRoute() {
        return route;
    }

    public enum PackageState {
        PREPARING,
        ACCEPTED,
        CANCELED,
        EN_ROUTE,
        ARRIVED
    }
}
