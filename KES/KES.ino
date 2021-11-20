#define gasesInpitPin 5

#include <ESP8266WiFi.h>  
#include <OneWire.h>
#include <DallasTemperature.h>
 
String ssid     ="";//WI-FI SSID
String password = "";//Password
const int analogPin = A0; 

float bpmUpdateTime = 0;//kiedy ma sie zaktualizowac bpm
float timeSum=0;
float lastTime = 0;
int minimum = 565;
int  heartbeatDuringMeasurement=0;
int bps = 0;

///////
bool isRising=false;
float lastRead=0;
int iddqd=0;

float tempUno = 0;
float tempDos = 0;
int gases = 0;

OneWire oneWire(4);
DallasTemperature sensors(&oneWire);
 
WiFiServer server(80);
 
void setup() 
{
  pinMode(gasesInpitPin,INPUT);
  
  Serial.begin(115200);
  sensors.begin();
  
  WiFi.begin(ssid, password);
  delay(5000);
  while (WiFi.status() != WL_CONNECTED) 
  {
    Serial.println("Connection error - you can enter SSID and password separated by a space");
    delay(5000);
    if(Serial.available())
    {
      String aa;
      String bb;
      aa = Serial.readStringUntil(' ');
      bb = Serial.readStringUntil(' ');
      WiFi.disconnect();
      WiFi.begin(aa, bb);
    }
  }
  server.begin();
  Serial.println("Connect");
  Serial.println("My IP: ");
  Serial.println(WiFi.localIP());   
}

void serwerStuff()
{
   WiFiClient client = server.available();
  if (!client) 
  {
    return;
  }
  
  int timewate = 0;
  while(!client.available())
  {
    delay(1);
    timewate = timewate +1;
    if(timewate>1800)
    {
      client.stop();
      return;
    }
  }
  String query = client.readStringUntil('\r');
  client.flush();

  client.println("HTTP/1.1 200 OK");
  client.println("Content-Type: text/html");
  client.println("");

  
  if (query.indexOf("/ID=1") != -1)  
  {
    client.println(tempUno);
  }
  else if (query.indexOf("/ID=2") != -1)  
  {
    client.println(tempDos);
  }
  else if (query.indexOf("/ID=3") != -1)  
  {
    client.println(gases);
  }
  else if (query.indexOf("/ID=4") != -1)  
  {
    client.println(bps);
  }  
  else if (query.indexOf("/BPS=UP") != -1)  
  {
    minimum+=5;
  }
  else if (query.indexOf("/BPS=DOWN") != -1)  
  {
    minimum-=5;
  }
  else
  {
    client.println("<!DOCTYPE html>\n");
    client.println("<html>");
    client.println("<head>");
    client.println("<meta charset=\"utf-8\">");
    client.println("<title>KES web server</title>");
    client.println("</head>");
    client.println("<body>");
    client.println("<h1>KES web server</h1>");
    ////
    client.println("<b>Temp #1:</b>");
    client.println("<p id=\"tempUno\">"+String(tempUno)+"</p><br />");
    
    client.println("<b>Temp #2:</b>");
    client.println("<p id=\"tempUno\">"+String(tempDos)+"</p><br />");
    
    client.println("<b>Gases:</b>");
    client.println("<p id=\"gaz\">"+String(gases)+"</p><br />");
  
    client.println("<b>Pulse:</b>");
    client.println("<p id=\"gaz\">"+String(bps)+" Przy bramce szumów wynoszącej:"+String(minimum)+"</p><br />");
    ////
    client.println("</body>");
    client.println("</html>");
    
  }
}

void loop() 
{
  float dataFromPin = analogRead(analogPin);
  
  if(dataFromPin>lastRead)
  {
    isRising=true;
  }
  else
  {
    if(isRising==true)
    {
      timeSum+=(millis()-lastTime);
       heartbeatDuringMeasurement++;
      lastTime = millis();
      iddqd=700;
    }
    isRising=false;
  }
  Serial.print(iddqd);
  Serial.print(",");
  Serial.println(dataFromPin);
  iddqd=0;
  lastRead=dataFromPin;
  
  if(millis()>bpmUpdateTime)
  {
    bps=int(60000/(timeSum/ heartbeatDuringMeasurement));
    bpmUpdateTime = millis()+10000;
    timeSum=0;
     heartbeatDuringMeasurement=0;
  }
  

  ////
  sensors.requestTemperatures(); //Pobranie temperatury czujnika
  tempUno = sensors.getTempCByIndex(0);
  tempDos = sensors.getTempCByIndex(1);
  gases = digitalRead(gasesInpitPin);
  ////
  
  serwerStuff();
  delay(25);
}
