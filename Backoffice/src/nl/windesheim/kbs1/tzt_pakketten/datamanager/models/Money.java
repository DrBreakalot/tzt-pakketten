package nl.windesheim.kbs1.tzt_pakketten.datamanager.models;

public class Money extends Number {

    private final int cents;

    public Money(int cents) {
        this.cents = cents;
    }

    public Money add(Money other) {
        return new Money(this.cents + other.cents);
    }

    public Money subtract(Money other) {
        return new Money(this.cents - other.cents);
    }

    @Override
    public int intValue() {
        return cents;
    }

    @Override
    public long longValue() {
        return cents;
    }

    @Override
    public float floatValue() {
        return cents;
    }

    @Override
    public double doubleValue() {
        return cents;
    }

}
