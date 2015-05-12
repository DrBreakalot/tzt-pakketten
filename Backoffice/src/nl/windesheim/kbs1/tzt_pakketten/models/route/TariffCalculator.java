package nl.windesheim.kbs1.tzt_pakketten.models.route;

import nl.windesheim.kbs1.tzt_pakketten.models.Money;

public class TariffCalculator {

    private double minimumDistance;
    private double maximumDistance;    

    private Money startCost;
    private Money perKilometer;
    private double startDistance;

    public double getMinimumDistance() {
        return minimumDistance;
    }

    public double getMaximumDistance() {
        return maximumDistance;
    }

    public Money getStartCost() {
        return startCost;
    }

    public Money getPerKilometer() {
        return perKilometer;
    }

    public double getStartDistance() {
        return startDistance;
    }

    public Money getTariff(double distance) {
        Money distanceBased = new Money((int) (Math.max(0, distance - startDistance) * perKilometer.doubleValue()));
        return startCost.add(distanceBased);
    }

}
