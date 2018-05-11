/**
 * @file temperature_monitor.ino
 * @author Jakub Handzus
 * @date 1 Apr 2018
 * @brief ESP software for measuring and sending the temperature to server.
 *
 * @see https://github.com/JakubHandzus/Bachelors-thesis
 */

// ~~~~~~~~~~~~~ Libraries ~~~~~~~~~~~~~
#include <EEPROM.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <sha256.h> 				// https://github.com/daknuett/cryptosuite2
#include <DHT.h>    				// https://github.com/adafruit/DHT-sensor-library and https://github.com/adafruit/Adafruit_Sensor



// ~~~~~~~~~~~~~~ Defines ~~~~~~~~~~~~~~
#define AP_SSID "ESP"
#define AP_PASS ""
#define LED      D0
#define DHT_PIN  D1
#define BUTTON_PIN D2
#define DHT_TYPE DHT22
// #define DEBUG


#ifdef DEBUG
#define DBG_PRINT_LN(a) Serial.println(a)
#define DBG_PRINT(a) Serial.print(a)
#else
#define DBG_PRINT_LN(a)
#define DBG_PRINT(a)
#endif

// ~~~~~~~~~ Struct declaration ~~~~~~~~~
typedef struct {
	String addr = "";
	String api_key = "";
	String ssid = "";
	String password = "";
	String id = "";
} sensorSettings;

// ~~~~~~~~~~~~~~ Global ~~~~~~~~~~~~~~
sensorSettings sensor_gl;
ESP8266WebServer server(80);
DHT dht(DHT_PIN, DHT_TYPE);

// ~~~~~~~~~~~~~ Functions ~~~~~~~~~~~~~
// EEPROM

/**
 * @brief      Saves a byte to EEPROM.
 *
 * @param[in]  address  Adress number
 * @param[in]  val      Byte value
 */
void saveByte(int address, byte val) {
  EEPROM.write(address, val);
  EEPROM.commit();
}

/**
 * @brief      Reads a string from EEPROM.
 *
 * @param[in]  address  Adress number
 * @param[in]  length   Length of string
 *
 * @return     { description_of_the_return_value }
 */
String readStr(byte address, int length) {
	String tmp;
	for (int i = 0; i < length; i++) {
		tmp += (char) EEPROM.read(address + i);
	}
	return tmp;
}

/**
 * @brief      Saves a string to EEPROM.
 *
 * @param[in]  address  Adress number
 * @param[in]  length   Length of string
 * @param[in]  str      The string
 */
void saveStr(byte address, int length, String str) {
	for (int i = 0; i < length; i++) {
		EEPROM.write(i + address, str.charAt(i));
	}
	EEPROM.commit();
}

/**
 * @brief      Saves device configuration to EEPROM.
 *
 * @param      sensor  Sensor settings
 */
void saveToEEPROM(sensorSettings *sensor) {
	if (sensor->api_key.length() == 34 && sensor->ssid.length() > 0 && sensor->id.length() > 0 && sensor->addr.length() > 0) {

		saveByte(0, 40);
		saveByte(1, sensor->ssid.length()); 	// Length of ssid
		saveByte(2, sensor->password.length()); // Length of password
		saveByte(3, sensor->id.length()); 		// Length of id
		saveByte(4, sensor->addr.length()); 	// Length of server address
		saveStr(5, 34, sensor->api_key);
		saveStr(39, sensor->ssid.length(), sensor->ssid);
		saveStr(39 + sensor->ssid.length(), sensor->password.length(), sensor->password);
		saveStr(39 + sensor->ssid.length() + sensor->password.length(), sensor->id.length(), sensor->id);
		saveStr(39 + sensor->ssid.length() + sensor->password.length() + sensor->id.length(), sensor->addr.length(), sensor->addr);

	}
}

/**
 * @brief      Reads device configuration from EEPROM.
 *
 * @param      sensor  Sensor settings
 */
void readFromEEPROM(sensorSettings *sensor) {
	int ssid_length = (size_t) EEPROM.read(1);
	int pass_length = (size_t) EEPROM.read(2);
	int id_length = (size_t) EEPROM.read(3);
	int addr_length = (size_t) EEPROM.read(4);

	sensor->api_key = readStr(5, 34);
	sensor->ssid = readStr(39, ssid_length);
	sensor->password = readStr(39 + ssid_length, pass_length);
	sensor->id = readStr(39 + ssid_length + pass_length, id_length);
	sensor->addr = readStr(39 + ssid_length + pass_length + id_length, addr_length);

	DBG_PRINT("API-KEY: ");
	DBG_PRINT_LN(sensor->api_key);
	DBG_PRINT("SSID: ");		
	DBG_PRINT_LN(sensor->ssid);
	DBG_PRINT("password: ");
	DBG_PRINT_LN(sensor->password);
	DBG_PRINT("id: ");
	DBG_PRINT_LN(sensor->id);
	DBG_PRINT("addr: ");
	DBG_PRINT_LN(sensor->addr);

}

// WiFi

/**
 * @brief      Connects to wifi.
 *
 * @param      sensor  Sensor settings
 */
void connectWiFi(sensorSettings *sensor) {
	// connect to wifi
	WiFi.disconnect();
	WiFi.mode(WIFI_STA);
	WiFi.begin(sensor->ssid.c_str(), sensor->password.c_str());
	DBG_PRINT("\nConnecting to |");	
	DBG_PRINT(sensor->ssid);
	DBG_PRINT("| password: |");
	DBG_PRINT(sensor->password);
	DBG_PRINT_LN("|");
	while (WiFi.status() != WL_CONNECTED) {
		delay(1000);
		DBG_PRINT(".");
	}
	DBG_PRINT_LN("\n\tConnected!");
}

/**
 * @brief      Start sensor in setup mode.
 */
void sensorSetup() {
	// Detach interrupt when is button already clicked
	detachInterrupt(digitalPinToInterrupt(BUTTON_PIN));
	// Turn on LED
	digitalWrite(LED, LOW);
	
	DBG_PRINT_LN("Create AP");
	// create AP
	createAP();
	// Setup and start server
	server.on("/reg", sensorRegistration);
	server.begin();
	DBG_PRINT_LN("HTTP server started");
	while(1) {
		server.handleClient();
		delay(1000);
	}
}

/**
 * @brief      Creates an Access Point.
 */
void createAP() {
	WiFi.mode(WIFI_AP_STA);
	IPAddress apIP(192, 168, 42, 1);
	IPAddress apMask(255, 255, 255, 0);
	WiFi.softAPConfig(apIP, apIP, apMask);
	WiFi.softAP(AP_SSID, AP_PASS);

	IPAddress myIP = WiFi.softAPIP();
	DBG_PRINT("AP IP address: ");
	DBG_PRINT_LN(myIP);
}

/**
 * @brief      Start sensor in normal mode.
 */
void sensorStartup() {

	// Enable interupt on button's pin for restarting and reseting sensor
	attachInterrupt(digitalPinToInterrupt(BUTTON_PIN), resetSensor, RISING);
	// Turn off LED
	digitalWrite(LED, HIGH);
	
	readFromEEPROM(&sensor_gl);
	connectWiFi(&sensor_gl);

	// If sensor does not send registration message
	if (EEPROM.read(0) == 40) {
		sendRegistrationMsg(&sensor_gl);
	}
}

/**
 * @brief      Creates an identifier.
 *
 * @return     created identifier
 */
String createId() {
	String id = String(ESP.getChipId());
	id += ":";
	id += String(WiFi.macAddress());
	return id;
}

/**
 * @brief      Check sensor's registration data from url: Api-Key, Wi-Fi and IP address. 
 */
void sensorRegistration() {

	sensorSettings old_settings, new_settings;

	String error;
	bool boolApi_key = false, boolAddr = false, previousWiFi = false;
	readFromEEPROM(&old_settings);

	for (int i = 0; i < server.args(); i++) {

		// Api_key
		if (server.argName(i) == "api_key") {
			if (server.arg(i).length() == 34){
				new_settings.api_key = server.arg(i);
				boolApi_key = true;
			}
			else {
				error += "Wrong format of Api-key\n";
			}
		}

		// We-Fi SSID
		else if (server.argName(i) == "ssid") {
			if (server.arg(i).length() > 0) {
				new_settings.ssid = server.arg(i);
			}
			// empty SSID
			else {
				// no previous
				if (old_settings.ssid == "") {
					error += "Invalid SSID\n";
				}
				// has previous settings
				else {
					previousWiFi = true;
				}
			}
		}

		// Wi-Fi password
		else if (server.argName(i) == "pass") {
			if (server.arg(i).length() > 0) {
				new_settings.password = server.arg(i);
			}
		}

		// Server address
		else if (server.argName(i) == "addr") {
			if (server.arg(i).length() > 0) {
				new_settings.addr = server.arg(i);
				boolAddr = true;
			}
		}

		// Undefined
		else {
			error += "Undefined parameter\n";
		}
	}

	if (!boolApi_key) {
		error += "Api-key is missing\n";
	}

	if (!boolAddr) {
		error += "Server address is missing\n";
	}

	// If there is error
	if (error != "") {
		server.send(400, "text/plain", error);
		return;
	}

	if (previousWiFi) {
		new_settings.ssid = old_settings.ssid;
		new_settings.password = old_settings.password;
	}

	new_settings.id = createId();
	saveToEEPROM(&new_settings);

	// Succesful Response
	server.send(200, "text/plain", "Succesful");
	delay(1000);
	server.close();

	// Turn off LED
	digitalWrite(LED, HIGH);

	delay(100);
	DBG_PRINT_LN("ESP Restart");
	ESP.restart();

}

/**
 * @brief      Reset configuration and restart server.
 */
void resetSensor() {
	saveByte(0, 0);
 	ESP.restart();
}

/**
 * @brief      Sends a registration message to server.
 *
 * @param      sensor  Sensor settings
 */
void sendRegistrationMsg(sensorSettings *sensor) {
	DBG_PRINT_LN("Sending registration msg");
	
	send:
	while(1) {
		HTTPClient http;
		http.begin("http://" + sensor->addr + "/sensor/identificate");
		DBG_PRINT_LN("http://" + sensor->addr + "/sensor/identificate");
		http.addHeader("Content-Type", "application/x-www-form-urlencoded");
		String message = "api_key=" + sensor->api_key + "&id=" + sensor->id;
		DBG_PRINT_LN(message);

		int httpCode = http.POST(message);
		DBG_PRINT_LN(httpCode);
		
		String response = http.getString();

		if (httpCode > 0 ) {
			if(httpCode == HTTP_CODE_OK) {
	            if (response == "OK") {
	            	saveByte(0,42);
	            	http.end();
	            	break;
	            }
	            else {
	            	http.end();
	            	DBG_PRINT_LN("Error in message");
					delay(10000);
	            }
	        }
	        else {
	        	http.end();
	        	DBG_PRINT_LN("Negative server respond");
				delay(10000);
	        }
		}
		else {
			http.end();
			DBG_PRINT_LN("Server not found");
			delay(10000);
		}	
	}
}

/**
 * @brief      Gets the temperature from sensor (DHT).
 *
 * @return     The temperature
 */
String getTemperature() {
	float temperature = dht.readTemperature();
	return String(temperature);
}

/**
 * @brief      Creates and sends sensor temperature.
 *
 * @param      sensor  Sensor settings
 */
void sendTemperature(sensorSettings *sensor) {
	String temperature = getTemperature();
	HTTPClient http;
	http.begin("http://" + sensor->addr + "/temperature/post");
	DBG_PRINT_LN("http://" + sensor->addr + "/temperature/post");
	http.addHeader("Content-Type", "application/x-www-form-urlencoded");
	Sha256.init();
	Sha256.print(temperature + sensor->api_key + sensor->id);

	String message = "temperature=" + temperature + "&api_key=" + sensor->api_key + "&hash=" + stringSha256(Sha256.result());
	DBG_PRINT_LN(message);

	int httpCode = http.POST(message);
	DBG_PRINT_LN(httpCode);
	String response = http.getString();
	DBG_PRINT_LN(response);
	http.end();
}

/**
 * @brief      Converts SHA256 result to string.
 *
 * @param      sha256  Result of SHA256 hash
 *
 * @return     String represents hash
 */
String stringSha256(uint8_t * sha256) {
    char tmp[1];
    String strSha256;

    for (int i=0; i<32; i++) {
        sprintf(tmp, "%.2X",sha256[i]);
        strSha256.concat(tmp);
    }

    return strSha256 ;
}

// ~~~~~~~~~~~~~ Setup ~~~~~~~~~~~~~
/**
 * @brief      Program setup.
 */
void setup() {
	#ifdef DEBUG
	Serial.begin(115200);
	#endif

	DBG_PRINT_LN("Inicialize");
	EEPROM.begin(512);
	dht.begin();
	WiFi.mode(WIFI_OFF);
	delay(10);
	pinMode(LED, OUTPUT);
	
	DBG_PRINT_LN();
	DBG_PRINT_LN("Startup");
	if ( EEPROM.read(0) != 42 && EEPROM.read(0) != 40) {
		sensorSetup();
	}
	else {
		sensorStartup();
	}
}


// ~~~~~~~~~~~~~ Loop ~~~~~~~~~~~~~
/**
 * @brief      Sends temperature to server in loops.
 */
void loop() {
	sendTemperature(&sensor_gl);

	delay((60*1000));

}
