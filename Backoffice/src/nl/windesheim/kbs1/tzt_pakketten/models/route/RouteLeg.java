package nl.windesheim.kbs1.tzt_pakketten.models.route;

import nl.windesheim.kbs1.tzt_pakketten.models.Money;
import nl.windesheim.kbs1.tzt_pakketten.models.location.Location;

import java.time.Instant;
import java.time.Duration;

public class RouteLeg<T extends Location> {

    private Money cost;
    private Duration duration;
    private Instant start;
    private T fromLocation;
    private T toLocation;

    public Money getCost() {
        return cost;
    }

    public Duration getDuration() {
        return duration;
    }

    public Instant getStart() {
        return start;
    }

    public T getFromLocation() {
        return fromLocation;
    }

    public T getToLocation() {
        return toLocation;
    }

}
