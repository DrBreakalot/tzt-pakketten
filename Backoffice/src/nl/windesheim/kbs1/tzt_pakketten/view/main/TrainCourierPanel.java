package nl.windesheim.kbs1.tzt_pakketten.view.main;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.DataManager;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.TrainCourier;

/**
 * Created by Wilco on 26-5-2015.
 */
public class TrainCourierPanel extends ListPanel<TrainCourier> {

    private static final String FORMAT = "%03d - %s";

    public TrainCourierPanel() {
        super(object -> String.format(FORMAT, object.getId(), object.getName()));
        DataManager.getInstance().getTrainCouriers((success, data) -> {
            if (success) {
                setElements(data);
            }
        });
    }

}
