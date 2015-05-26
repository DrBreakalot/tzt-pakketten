package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.TrainCourier;
import retrofit.Callback;
import retrofit.http.Body;
import retrofit.http.GET;
import retrofit.http.POST;

import java.util.List;

/**
 * Created by Wilco on 12-5-2015.
 */
public interface TztService {
    @POST("/auth.php")
    void login(@Body LoginRequest body, Callback<LoginResponse> token);

    @GET("/customers.php")
    void getCustomers(Callback<List<Customer>> customers);

    @GET("/traincouriers.php")
    void getTrainCouriers(Callback<List<TrainCourier>> trainCouriers);

    class LoginRequest {

        private final String type = "OFFICE";
        private final String email;
        private final String password;

        public LoginRequest(String email, String password) {
            this.email = email;
            this.password = password;
        }
    }

    class LoginResponse {

        public String token;

    }
}
