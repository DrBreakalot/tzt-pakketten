package nl.windesheim.kbs1.tzt_pakketten.view.overview;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.DataManager;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.AddressLocation;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.Location;

import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeFormatterBuilder;

public class CustomerPackagePanel extends ListPanel<nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package> {

    private static DateTimeFormatter formatter = new DateTimeFormatterBuilder().appendPattern("d MMM").toFormatter();

    public CustomerPackagePanel(Customer customer) {
        super(object -> String.format("%s : %s - %s", formatter.format(object.getEnterDate()), stringFromLocation(object.getRoute().getFromAddress()), stringFromLocation(object.getRoute().getToAddress())));
        DataManager.getInstance().getPackages(customer, (success, data) -> {
            if (success) {
                setElements(data);
            }
        });
    }

    private static String stringFromLocation(Location location) {
        String toReturn;

        if (!isNullOrEmpty(location.getName())) {
            toReturn = location.getName();
        } else if (location instanceof AddressLocation && !isNullOrEmpty(((AddressLocation) location).getCity())) {
            toReturn = ((AddressLocation) location).getCity();
        } else {
            toReturn = String.format("%4f,%4f", location.getLatitude(), location.getLongitude());
        }

        return toReturn;
    }

    private static boolean isNullOrEmpty(String string) {
        return string == null || string.length() == 0;
    }
}
