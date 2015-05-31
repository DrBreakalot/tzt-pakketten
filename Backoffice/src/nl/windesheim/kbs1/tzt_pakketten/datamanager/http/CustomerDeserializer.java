package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonParseException;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.BusinessCustomer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.ConsumerCustomer;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.customer.Customer;

import java.lang.reflect.Type;

public class CustomerDeserializer implements JsonDeserializer<Customer> {
    @Override
    public Customer deserialize(JsonElement jsonElement, Type type, JsonDeserializationContext jsonDeserializationContext) throws JsonParseException {
        if (jsonElement.getAsJsonObject().has("is_business") && jsonElement.getAsJsonObject().get("is_business").getAsBoolean()) {
            return jsonDeserializationContext.deserialize(jsonElement, BusinessCustomer.class);
        } else {
            return jsonDeserializationContext.deserialize(jsonElement, ConsumerCustomer.class);
        }
    }
}
