package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.StationLocation;

public class TrainRouteLeg extends RouteLeg<StationLocation> {
    
    private TrainCourier courier; 

    public TrainCourier getCourier() {
        return courier;
    }
    
}
