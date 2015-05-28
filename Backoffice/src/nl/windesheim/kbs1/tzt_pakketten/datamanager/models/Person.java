package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.AddressLocation;

public class Person {

    private int id;
    private String name;
    private String email;
    private AddressLocation address;

    public int getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public String getEmail() {
        return email;
    }

    public AddressLocation getAddress() {
        return address;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setAddress(AddressLocation address) {
        this.address = address;
    }
}
