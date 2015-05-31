package nl.windesheim.kbs1.tzt_pakketten.datamanager.http;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;
import java.time.Duration;
import java.time.temporal.ChronoUnit;

/**
 * Created by Dr Breakalot on 31-5-2015.
 */
public class DurationDeserializer implements JsonDeserializer<Duration> {
    @Override
    public Duration deserialize(JsonElement jsonElement, Type type, JsonDeserializationContext jsonDeserializationContext) throws JsonParseException {
        if (jsonElement.isJsonPrimitive()) {
            return Duration.of(jsonElement.getAsLong(), ChronoUnit.SECONDS);
        }
        return null;
    }
}
