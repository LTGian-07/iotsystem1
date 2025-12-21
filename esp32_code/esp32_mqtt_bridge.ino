#include <Wire.h>
#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>

// --- THÔNG TIN CẤU HÌNH ---
const char* ssid = "realme C25";
const char* password = "lethigiang0711";
const char* mqtt_server = "broker.emqx.io";

// Topic theo config.php của bạn
const char* topic_publish = "iot/color_sorter/product";
const char* topic_command = "iot/color_sorter/command";

WiFiClient espClient;
PubSubClient client(espClient);

void setup() {
  Serial.begin(115200);
  Wire.begin(21, 22); // SDA=GPIO21, SCL=GPIO22
  setup_wifi();
  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);
}

void setup_wifi() {
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) { delay(500); Serial.print("."); }
  Serial.println("\nWiFi Connected!");
}

// Khi nhấn nút trên Web, Web gửi lệnh qua MQTT, ESP32 sẽ nhận ở đây
void callback(char* topic, byte* payload, unsigned int length) {
  String message = "";
  for (int i = 0; i < length; i++) message += (char)payload[i];
  Serial.println("Lệnh nhận được: " + message);
  
  // Bạn có thể gửi lệnh này xuống Arduino qua I2C nếu cần
}

void reconnect() {
  while (!client.connected()) {
    if (client.connect("ESP32_Color_Sorter")) {
      client.subscribe(topic_command);
    } else {
      delay(5000);
    }
  }
}

void loop() {
  if (!client.connected()) reconnect();
  client.loop();

  static unsigned long lastUpdate = 0;
  if (millis() - lastUpdate > 3000) { // 3 giây cập nhật 1 lần
    lastUpdate = millis();
    
    Wire.requestFrom(0x08, 4); // Yêu cầu 4 byte từ Arduino
    if (Wire.available() >= 4) {
      int r = Wire.read();
      int b = Wire.read();
      int y = Wire.read();
      int mode = Wire.read();

      StaticJsonDocument<200> doc;
      doc["red"] = r;
      doc["blue"] = b;
      doc["yellow"] = y;
      doc["mode"] = (mode == 1 ? "MANUAL" : "AUTO");
      doc["total"] = (r + b + y);

      char buffer[256];
      serializeJson(doc, buffer);
      client.publish(topic_publish, buffer);
      Serial.println("Đã gửi dữ liệu lên Web: " + String(buffer));
    }
  }
}