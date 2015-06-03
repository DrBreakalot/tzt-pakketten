package nl.windesheim.kbs1.tzt_pakketten.view.overview;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.DataManager;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;

import javax.swing.*;

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

    @Override
    protected void onDoubleClick(Customer element) {
        super.onDoubleClick(element);
        new PackageSelector((JFrame)SwingUtilities.getWindowAncestor(this), false, element).setVisible(true);
    }
}
