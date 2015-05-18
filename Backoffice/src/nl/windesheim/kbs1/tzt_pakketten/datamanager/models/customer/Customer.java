package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer;

import com.google.gson.annotations.SerializedName;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Person;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public abstract class Customer extends Person {

    //This is used for gson-serialization
    @SerializedName("is_business")
    private boolean isBusiness = this instanceof BusinessCustomer;

    private transient List<Package> packages;

    public List<Package> getPackages() {
        return Collections.unmodifiableList(packages != null ? packages : new ArrayList<>());
    }

}
