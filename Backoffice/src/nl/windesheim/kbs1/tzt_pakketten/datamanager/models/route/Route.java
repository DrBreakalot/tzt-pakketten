package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.AddressLocation;

import java.time.Instant;
import java.time.Duration;
import java.util.Collections;
import java.util.List;
import java.util.ArrayList;

public class Route {

    private Money actualCost;
    private Instant start;
    private AddressLocation fromAddress;
    private AddressLocation toAddress;
    private List<RouteLeg<?>> routeLegs;

    public Money getActualCost() {
        return actualCost;
    }

    public Instant getStart() {
        return start;
    }

    public AddressLocation getFromAddress() {
        return fromAddress;
    }

    public AddressLocation getToAddress() {
        return toAddress;
    }
    
    public Money getCalculatedCost() {
        return new Money(
                getRouteLegs().stream()
                        .mapToInt((RouteLeg<?> leg) -> leg.getCost().intValue())
                        .sum()
        );
    }

    public Duration getCalculatedDuration() {
        return getRouteLegs().stream()
                .map(RouteLeg::getDuration)
                .reduce(Duration.ZERO, (Duration old, Duration other) -> old.plus(other));
    }

    public List<RouteLeg<?>> getRouteLegs() {
        return Collections.unmodifiableList(routeLegs != null ? routeLegs : new ArrayList<>());
    }

}
