package nl.windesheim.kbs1.tzt_pakketten.view.overview;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;

import javax.swing.*;

/**
 *
 * @author Wilco
 */
public class PackageSelector extends javax.swing.JDialog {

    private JPanel customerPackagePanel;
    private Customer customer;

    /**
     * Creates new form PackageSelector
     */
    public PackageSelector(java.awt.Frame parent, boolean modal, Customer customer) {
        super(parent, modal);
        this.customer = customer;
        initComponents();
    }

    private void initComponents() {
        customerPackagePanel = new CustomerPackagePanel(customer);
        add(customerPackagePanel);

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);

        setTitle(customer.getName() + " - Pakketten");

        pack();
    }
}
