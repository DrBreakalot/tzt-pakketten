package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.StationLocation;

import java.time.Instant;
import java.time.temporal.TemporalAccessor;

public class TrainSection {

    private TemporalAccessor departure;
    private boolean repeating;
    private StationLocation fromStation;
    private StationLocation toStation;

    public TemporalAccessor getDeparture() {
        return departure;
    }

    public boolean isRepeating() {
        return repeating;
    }

    public StationLocation getFromStation() {
        return fromStation;
    }

    public StationLocation getToStation() {
        return toStation;
    }

}
