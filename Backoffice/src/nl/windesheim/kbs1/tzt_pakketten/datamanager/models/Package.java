package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

import com.google.gson.annotations.SerializedName;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.Route;

import java.time.Instant;
import java.time.temporal.Temporal;
import java.time.temporal.TemporalAccessor;

public class Package {

    @SerializedName("width")
    private double width;

    @SerializedName("height")
    private double height;

    @SerializedName("depth")
    private double depth;

    @SerializedName("weight")
    private double weight;

    @SerializedName("barcode")
    private String barcode;

    @SerializedName("enter_date")
    private TemporalAccessor enterDate;

    @SerializedName("paid_price")
    private Money paidPrice;

    @SerializedName("state")
    private PackageState state;

    @SerializedName("route")
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

    public TemporalAccessor getEnterDate() {
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
