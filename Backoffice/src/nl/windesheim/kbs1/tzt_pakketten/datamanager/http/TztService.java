package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.TrainCourier;
import retrofit.Callback;
import retrofit.http.*;

import java.util.List;

public interface TztService {
    @POST("/auth.php")
    void login(@Body LoginRequest body, Callback<LoginResponse> token);

    @GET("/customers.php")
    void getCustomers(Callback<List<Customer>> customers);

    @GET("/traincouriers.php")
    void getTrainCouriers(Callback<List<TrainCourier>> trainCouriers);

    @GET("/packages.php")
    void getPackages(@Query("customer_id") int customerId, Callback<List<nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package>> callback);

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
