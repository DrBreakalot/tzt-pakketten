package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.AddressLocation;

public class Person {

    private String name;
    private String email;
    private AddressLocation address;

    public String getName() {
        return name;
    }

    public String getEmail() {
        return email;
    }

    public AddressLocation getAddress() {
        return address;
    }

}
