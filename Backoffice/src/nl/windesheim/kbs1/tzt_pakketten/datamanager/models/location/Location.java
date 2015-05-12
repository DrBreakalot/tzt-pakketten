package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location;

public abstract class Location {

    private String name;
    private Double latitude;
    private Double longitude;

    public void setName(String name) {
        this.name = name;
    }

    public void setLatitude(Double latitude) {
        this.latitude = latitude;
    }

    public void setLongitude(Double longitude) {
        this.longitude = longitude;
    }

    public String getName() {
        return name;
    }

    public Double getLatitude() {
        return latitude;
    }

    public Double getLongitude() {
        return longitude;
    }

}
