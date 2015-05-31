package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonParseException;
import nl.windesheim.kbs1.tzt_pakketten.datamanager.models.Money;

import java.lang.reflect.Type;

/**
 * Created by Dr Breakalot on 31-5-2015.
 */
public class MoneyDeserializer implements JsonDeserializer<Money> {
    @Override
    public Money deserialize(JsonElement jsonElement, Type type, JsonDeserializationContext jsonDeserializationContext) throws JsonParseException {
        if (jsonElement.isJsonPrimitive()) {
            return new Money(jsonElement.getAsInt());
        }
        return null;
    }
}
