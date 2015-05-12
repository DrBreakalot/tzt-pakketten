package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class BusinessCustomer extends Customer {

    @SerializedName("kvk_number")
    private String kvkNumber;

    private transient List<Invoice> invoices;

    public void setKvkNumber(String kvkNumber) {
        this.kvkNumber = kvkNumber;
    }

    public void setInvoices(List<Invoice> invoices) {
        this.invoices = invoices;
    }

    public String getKvkNumber() {
        return kvkNumber;
    }
    
    public List<Invoice> getInvoices() {
        return Collections.unmodifiableList(invoices != null ? invoices : new ArrayList<>());
    }

}
