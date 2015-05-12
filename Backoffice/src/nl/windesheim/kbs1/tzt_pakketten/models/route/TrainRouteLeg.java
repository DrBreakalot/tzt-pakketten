package nl.windesheim.kbs1.tzt_pakketten.models.route;

import nl.windesheim.kbs1.tzt_pakketten.models.location.StationLocation;

public class TrainRouteLeg extends RouteLeg<StationLocation> {
    
    private TrainCourier courier; 

    public TrainCourier getCourier() {
        return courier;
    }
    
}
