package nl.windesheim.kbs1.tzt_pakketten.models.route;

import nl.windesheim.kbs1.tzt_pakketten.models.Person;
import nl.windesheim.kbs1.tzt_pakketten.models.TrainSection;

import java.util.List;

public class TrainCourier extends Person {

    private String bankAccount;
    private List<TrainSection> sections;

    public String getBankAccount() {
        return bankAccount;
    }

    public List<TrainSection> getSections() {
        return sections;
    }

}
