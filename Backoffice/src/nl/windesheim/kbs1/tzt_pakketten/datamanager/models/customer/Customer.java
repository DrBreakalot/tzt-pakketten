package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.*;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class Customer extends Person {

    private List<nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package> packages;

    public List<Package> getPackages() {
        return Collections.unmodifiableList(packages != null ? packages : new ArrayList<>());
    }

}
