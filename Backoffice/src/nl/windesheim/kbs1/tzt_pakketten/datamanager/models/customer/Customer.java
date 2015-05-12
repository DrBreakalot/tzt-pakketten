package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer;

import com.google.gson.annotations.SerializedName;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.*;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class Customer extends Person {

    @SerializedName("is_business")
    private boolean isBusiness = this instanceof BusinessCustomer;

    private transient List<nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package> packages;

    public List<Package> getPackages() {
        return Collections.unmodifiableList(packages != null ? packages : new ArrayList<>());
    }

}
