package nl.windesheim.kbs1.tzt_pakketten.datamanager;

import nl.windesheim.kbs1.tzt_pakketten.Config;
import nl.windesheim.kbs1.tzt_pakketten.http.TztService;
import retrofit.Callback;
import retrofit.RequestInterceptor;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Wilco on 12-5-2015.
 */
public class DataManager {

    private final TztService service;

    private String authToken;

    private static final DataManager instance = new DataManager();

    public static DataManager getInstance() {
        return instance;
    }

    private DataManager() {
        RequestInterceptor interceptor = request -> {
            if (authToken != null) {
                request.addHeader(Config.API_AUTH_TOKEN_HEADER, authToken);
            }
        };

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setEndpoint(Config.API_URL)
                .setLogLevel(RestAdapter.LogLevel.FULL)
                .setRequestInterceptor(interceptor)
                .build();

        service = restAdapter.create(TztService.class);
    }

    public void login(String email, String password) {
        service.login(new TztService.LoginRequest(email, password), new Callback<TztService.LoginResponse>() {
            @Override
            public void success(TztService.LoginResponse loginResponse, Response response) {
                authToken = loginResponse.token;
            }

            @Override
            public void failure(RetrofitError retrofitError) {

            }
        });
    }


}
