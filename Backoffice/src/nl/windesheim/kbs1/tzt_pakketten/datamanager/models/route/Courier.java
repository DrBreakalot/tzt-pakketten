package nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;

import java.util.List;

public class Courier {

    private String name;
    private String description;
    private List<TariffCalculator> calculators;

    public String getName() {
        return name;
    }

    public String getDescription() {
        return description;
    }
    
    private TariffCalculator getCalculator(double distanceInKm) {
        if (calculators == null) {
            return null;
        }
        return calculators.stream()
                .filter((TariffCalculator calculator) -> distanceInKm >= calculator.getMinimumDistance() && distanceInKm < calculator.getMaximumDistance())
                .findAny()
                .orElse(null);
    }
    
    public Money getCost(double distanceInKm) {
        TariffCalculator calculator = getCalculator(distanceInKm);
        if (calculator == null) {
            return null;
        }
        
        return calculator.getTariff(distanceInKm);
    }

}
