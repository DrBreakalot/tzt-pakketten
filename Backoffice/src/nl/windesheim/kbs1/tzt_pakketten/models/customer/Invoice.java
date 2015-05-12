package nl.windesheim.kbs1.tzt_pakketten.models.customer;

import nl.windesheim.kbs1.tzt_pakketten.models.*;
import nl.windesheim.kbs1.tzt_pakketten.models.Package;

import java.time.Duration;
import java.time.Instant;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class Invoice {

    private Instant date;
    private String number;
    private Duration paymentTerm;
    private List<nl.windesheim.kbs1.tzt_pakketten.models.Package> packages;

    public Instant getDate() {
        return date;
    }

    public String getNumber() {
        return number;
    }

    public Duration getPaymentTerm() {
        return paymentTerm;
    }

    public List<Package> getPackages() {
        return Collections.unmodifiableList(packages != null ? packages : new ArrayList<>());
    }

}
