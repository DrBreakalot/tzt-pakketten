package nl.windesheim.kbs1.tzt_pakketten.models.customer;

import nl.windesheim.kbs1.tzt_pakketten.models.*;
import nl.windesheim.kbs1.tzt_pakketten.models.Package;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class Customer extends Person {

    private List<nl.windesheim.kbs1.tzt_pakketten.models.Package> packages;

    public List<Package> getPackages() {
        return Collections.unmodifiableList(packages != null ? packages : new ArrayList<>());
    }

}
