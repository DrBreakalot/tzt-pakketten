package nl.windesheim.kbs1.tzt_pakketten.view.main;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.DataManager;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;

/**
 * Created by Wilco on 26-5-2015.
 */
public class CustomerPanel extends ListPanel<Customer> {

    private static final String FORMAT = "%03d - %s";

    public CustomerPanel() {
        super(object -> String.format(FORMAT, object.getId(), object.getName()));
        DataManager.getInstance().getCustomers((success, data) -> {
            if (success) {
                setElements(data);
            }
        });
    }

}
