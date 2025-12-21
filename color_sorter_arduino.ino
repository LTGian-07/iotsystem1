#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>

// ===== CẤU HÌNH THỜI GIAN NGỦ =====
unsigned long thoiGianRanh = 0;
const unsigned long CHO_NGU = 60000; 
bool dangNgu = false;

// ===== LCD & I2C =====
LiquidCrystal_I2C lcd(0x27, 16, 2);
#define SLAVE_ADDR 0x08

// ===== SERVO =====
Servo servo1, servo2;
int chan_servo1 = 3;
int chan_servo2 = 4;
bool cheDoThuCong = false; 

#define LED_MODE 13

// ===== TCS3200 =====
#define S0 5
#define S1 6
#define S2 7
#define S3 8
#define OUT 9

// Nút bấm & Cảm biến
#define BUTTON1 10
#define BUTTON2 11
#define BUTTON3 12
#define SENSOR_PIN 2

// Thông số màu (Giữ nguyên)
unsigned long R_min = 13800, R_max = 113888;
unsigned long G_min = 14500, G_max = 125000;
unsigned long B_min = 18800, B_max = 152380;

// THÊM 'volatile' để tránh lỗi đếm không tăng khi chạy lâu
volatile int countRed = 0;
volatile int countBlue = 0;
volatile int countYellow = 0;

// Hàm gửi dữ liệu cho ESP32
void requestEvent() {
  byte data[4];
  // Ép kiểu byte để truyền qua I2C (tối đa 255 mỗi loại màu)
  data[0] = (byte)(countRed > 255 ? 255 : countRed);
  data[1] = (byte)(countBlue > 255 ? 255 : countBlue);
  data[2] = (byte)(countYellow > 255 ? 255 : countYellow);
  data[3] = (byte)(cheDoThuCong ? 1 : 0); 
  Wire.write(data, 4);
}

void updateLCD() {
  if (dangNgu) return; 
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("r  b  y  s");
  
  long sum = (long)countRed + countBlue + countYellow; // Dùng long cho chắc chắn

  lcd.setCursor(0, 1);
  lcd.print(countRed);
  lcd.setCursor(3, 1);
  lcd.print(countBlue);
  lcd.setCursor(6, 1);
  lcd.print(countYellow);
  lcd.setCursor(9, 1);
  lcd.print(sum);
}

// ... Các hàm thucDay, diNgu, readFreq, convertFreq giữ nguyên ...

void thucDay() {
  if (dangNgu) {
    lcd.backlight();
    digitalWrite(S0, HIGH);
    digitalWrite(S1, LOW);
    dangNgu = false;
    updateLCD();
  }
  thoiGianRanh = millis();
}

void diNgu() {
  if (!dangNgu) {
    lcd.noBacklight();
    lcd.clear();
    lcd.print("System Sleeping");
    digitalWrite(S0, LOW);
    digitalWrite(S1, LOW);
    dangNgu = true;
  }
}

unsigned long readFreq(int s2, int s3) {
  digitalWrite(S2, s2);
  digitalWrite(S3, s3);
  unsigned long t = pulseIn(OUT, LOW, 250000);
  if (t == 0) t = 1;
  return 1000000UL / t;
}

int convertFreq(unsigned long f, unsigned long fMin, unsigned long fMax) {
  long v = map(f, fMin, fMax, 0, 255);
  return (int)constrain(v, 0, 255);
}

// Hàm đo màu đã tối ưu (Hạn chế dùng String để tránh tràn RAM)
int getMau() {
  unsigned long fR = readFreq(LOW, LOW);
  unsigned long fG = readFreq(HIGH, HIGH);
  unsigned long fB = readFreq(LOW, HIGH);
  int r = convertFreq(fR, R_min, R_max);
  int g = convertFreq(fG, G_min, G_max);
  int b = convertFreq(fB, B_min, B_max);

  if (r > 200 && g > 180 && b < 150) return 3; // Yellow
  if (r > 150 && r > g && r > b) return 1;    // Red
  if (b > 150 && b > r && b > g) return 2;    // Blue
  return 0; // Unknown
}

void setup() {
  Serial.begin(115200);
  pinMode(LED_MODE, OUTPUT);
  lcd.init();
  lcd.backlight();
  
  servo1.attach(chan_servo1);
  servo2.attach(chan_servo2);
  servo1.write(0); servo2.write(0);

  pinMode(S0, OUTPUT); pinMode(S1, OUTPUT);
  pinMode(S2, OUTPUT); pinMode(S3, OUTPUT);
  pinMode(OUT, INPUT);
  digitalWrite(S0, HIGH); digitalWrite(S1, LOW);

  pinMode(BUTTON1, INPUT_PULLUP);
  pinMode(BUTTON2, INPUT_PULLUP);
  pinMode(BUTTON3, INPUT_PULLUP);
  pinMode(SENSOR_PIN, INPUT);

  Wire.begin(SLAVE_ADDR);
  Wire.onRequest(requestEvent);
  
  thoiGianRanh = millis();
  updateLCD();
}

void loop() {
  // 1. Kiểm tra đánh thức
  if (digitalRead(BUTTON1) == LOW || digitalRead(BUTTON2) == LOW || digitalRead(BUTTON3) == LOW || digitalRead(SENSOR_PIN) == LOW) {
    thucDay();
  }

  // 2. Chế độ Thủ công
  if (cheDoThuCong) {
      if (digitalRead(BUTTON3) == LOW) { cheDoThuCong = false; digitalWrite(LED_MODE, LOW); delay(300); }
      if (digitalRead(BUTTON1) == LOW) { servo2.write(100); delay(500); servo2.write(0); }
      if (digitalRead(BUTTON2) == LOW) { servo1.write(100); delay(500); servo1.write(0); }
  } 
  // 3. Chế độ Tự động
  else {
    if (digitalRead(BUTTON3) == LOW) { cheDoThuCong = true; digitalWrite(LED_MODE, HIGH); delay(300); }
    
    if (digitalRead(SENSOR_PIN) == LOW) {
      delay(1500);
      int mauId = getMau(); // Lấy ID màu thay vì String
      
      if (mauId == 1) { // RED
        countRed++; updateLCD();
        delay(2900); servo1.write(100); delay(500); servo1.write(0); 
      }
      else if (mauId == 2) { // BLUE
        countBlue++; updateLCD();
        delay(6500); servo2.write(100); delay(500); servo2.write(0);
      }
      else if (mauId == 3) { // YELLOW
        countYellow++; updateLCD();
      }
      
      thoiGianRanh = millis(); // Reset thời gian rảnh sau khi xử lý xong vật
    }
  }

  // 4. Kiểm tra đi ngủ
  if (millis() - thoiGianRanh > CHO_NGU) {
    diNgu();
  }
}