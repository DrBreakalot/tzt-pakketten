package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import com.google.gson.annotations.SerializedName;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.location.AddressLocation;

import java.time.Instant;
import java.time.Duration;
import java.time.temporal.TemporalAccessor;
import java.util.Collections;
import java.util.List;
import java.util.ArrayList;

public class Route {

    @SerializedName("actual_cost")
    private Money actualCost;

    @SerializedName("start")
    private TemporalAccessor start;

    @SerializedName("from")
    private AddressLocation fromAddress;

    @SerializedName("to")
    private AddressLocation toAddress;

    @SerializedName("legs")
    private List<RouteLeg<?>> routeLegs;

    public Money getActualCost() {
        return actualCost;
    }

    public TemporalAccessor getStart() {
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
                .reduce(Duration.ZERO, Duration::plus);
    }

    public List<RouteLeg<?>> getRouteLegs() {
        return Collections.unmodifiableList(routeLegs != null ? routeLegs : new ArrayList<>());
    }

}
