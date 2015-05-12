package nl.windesheim.kbs1.tzt_pakketten;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.DataManager;

/**
 *
 * @author Wilco Wolters
 */
public class Main {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        DataManager.getInstance().login("test", "test");
    }
    
}
