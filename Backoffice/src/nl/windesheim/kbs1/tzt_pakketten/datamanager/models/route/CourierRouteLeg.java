package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.Location;

public class CourierRouteLeg extends RouteLeg<Location> {

    private Courier courier;
    
    public Courier getCourier() {
        return courier;
    }
}
