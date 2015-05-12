package nl.windesheim.kbs1.tzt_pakketten.models.customer;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class BusinessCustomer extends Customer {

    private String kvkNumber;
    private List<Invoice> invoices;

    public String getKvkNumber() {
        return kvkNumber;
    }
    
    public List<Invoice> getInvoices() {
        return Collections.unmodifiableList(invoices != null ? invoices : new ArrayList<>());
    }

}
