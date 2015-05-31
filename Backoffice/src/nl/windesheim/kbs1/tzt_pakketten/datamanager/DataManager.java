package nl.windesheim.kbs1.tzt_pakketten.datamanager;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import nl.windesheim.kbs1.tzt_pakketten.Config;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.http.*;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.route.TrainCourier;
import retrofit.Callback;
import retrofit.RequestInterceptor;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;
import retrofit.converter.GsonConverter;

import java.time.Duration;
import java.time.Instant;
import java.time.temporal.TemporalAccessor;
import java.util.List;

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

        Gson gson = new GsonBuilder()
                .registerTypeAdapter(Customer.class, new CustomerDeserializer())
                .registerTypeAdapter(TemporalAccessor.class, new TemporalAccessorDeserializer())
                .registerTypeAdapter(Money.class, new MoneyDeserializer())
                .registerTypeAdapter(Duration.class, new DurationDeserializer())
                .create();

        RestAdapter restAdapter = new RestAdapter.Builder()
                .setEndpoint(Config.API_URL)
                .setLogLevel(RestAdapter.LogLevel.FULL)
                .setRequestInterceptor(interceptor)
                .setConverter(new GsonConverter(gson))
                .build();

        service = restAdapter.create(TztService.class);
    }

    public void login(String email, String password, NoDataCallback callback) {
        service.login(new TztService.LoginRequest(email, password), new Callback<TztService.LoginResponse>() {
            @Override
            public void success(TztService.LoginResponse loginResponse, Response response) {
                authToken = loginResponse.token;
                callback.onDone(true);
            }

            @Override
            public void failure(RetrofitError retrofitError) {
                callback.onDone(false);
            }
        });
    }

    public void getCustomers(DataCallback<List<Customer>> callback) {
        service.getCustomers(new RetroCallback<>(callback));
    }

    public void getTrainCouriers(DataCallback<List<TrainCourier>> callback) {
        service.getTrainCouriers(new RetroCallback<>(callback));
    }

    public void getPackages(Customer customer, DataCallback<List<nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Package>> callback) {
        service.getPackages(customer.getId(), new RetroCallback<>(callback));
    }

    public interface NoDataCallback {
        void onDone(boolean success);
    }

    public interface DataCallback<T> {
        void onDone(boolean success, T data);
    }

    private static class RetroCallback<T> implements Callback<T> {

        private DataCallback<T> callback;

        private RetroCallback(DataCallback<T> callback) {
            this.callback = callback;
        }

        @Override
        public void success(T t, Response response) {
            callback.onDone(true, t);
        }

        @Override
        public void failure(RetrofitError retrofitError) {
            callback.onDone(false, null);
        }
    }

}
