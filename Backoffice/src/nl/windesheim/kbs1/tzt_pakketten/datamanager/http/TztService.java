package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import retrofit.Callback;
import retrofit.http.Body;
import retrofit.http.POST;

/**
 * Created by Wilco on 12-5-2015.
 */
public interface TztService {
    @POST("/auth.php")
    void login(@Body LoginRequest body, Callback<LoginResponse> token);

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
