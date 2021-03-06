package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.Location;

import java.time.Instant;
import java.time.Duration;
import java.time.temporal.TemporalAccessor;

public class RouteLeg<T extends Location> {

    private Money cost;
    private Duration duration;
    private TemporalAccessor start;
    private T fromLocation;
    private T toLocation;

    public Money getCost() {
        return cost;
    }

    public Duration getDuration() {
        return duration;
    }

    public TemporalAccessor getStart() {
        return start;
    }

    public T getFromLocation() {
        return fromLocation;
    }

    public T getToLocation() {
        return toLocation;
    }

}
