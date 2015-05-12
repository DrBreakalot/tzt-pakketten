package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location;

public class AddressLocation extends Location {

    private String address;
    private String city;
    private String postalCode;

    public void setAddress(String address) {
        this.address = address;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public void setPostalCode(String postalCode) {
        this.postalCode = postalCode;
    }

    public String getAddress() {
        return address;
    }

    public String getCity() {
        return city;
    }

    public String getPostalCode() {
        return postalCode;
    }

}
