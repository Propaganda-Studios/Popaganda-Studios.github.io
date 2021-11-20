#define cyfrowe_wejscie_gazu 5

#include <ESP8266WiFi.h>  
#include <OneWire.h>
#include <DallasTemperature.h>
 
String ssid     ="";//SSID sieci
String password = "";//Haslo
const int pinAnalogowy = A0; 

float aktualizacja = 0;//kiedy ma sie zaktualizowac bpm
float sumaCzasow=0;
float ostatnieUderzenie = 0;
int granica = 565;
int uderzeniaNaPomiar=0;
int bps = 0;
///////
bool czyRosnie=false;
float ostatniOdczyt=0;
int BINARKAELO=0;

float temperaturaUno = 0;
float temperaturaDos = 0;
int gazy = 0;

OneWire oneWire(4);
DallasTemperature sensors(&oneWire);
 
WiFiServer server(80);
 
void setup() 
{
  pinMode(cyfrowe_wejscie_gazu,INPUT);
  
  Serial.begin(115200);
  sensors.begin();
  
  WiFi.begin(ssid, password);
  delay(5000);
  while (WiFi.status() != WL_CONNECTED) 
  {
    Serial.println("Problem z polaczeniem - mozesz podac SSID i haslo rozdzielajac je spacja");
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
  Serial.println("Polaczono z siecia WI-FI");
  Serial.println("IP urzadzenia: ");
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
  String zapytanie = client.readStringUntil('\r');
  client.flush();

  client.println("HTTP/1.1 200 OK");
  client.println("Content-Type: text/html");
  client.println("");

  
  if (zapytanie.indexOf("/ID=1") != -1)  
  {
    client.println(temperaturaUno);
  }
  else if (zapytanie.indexOf("/ID=2") != -1)  
  {
    client.println(temperaturaDos);
  }
  else if (zapytanie.indexOf("/ID=3") != -1)  
  {
    client.println(gazy);
  }
  else if (zapytanie.indexOf("/ID=4") != -1)  
  {
    client.println(bps);
  }  
  else if (zapytanie.indexOf("/BPS=UP") != -1)  
  {
    granica+=5;
  }
  else if (zapytanie.indexOf("/BPS=DOWN") != -1)  
  {
    granica-=5;
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
    client.println("<b>Temperatura #1:</b>");
    client.println("<p id=\"tempUno\">"+String(temperaturaUno)+"</p><br />");
    
    client.println("<b>Temperatura #2:</b>");
    client.println("<p id=\"tempUno\">"+String(temperaturaDos)+"</p><br />");
    
    client.println("<b>Gazy:</b>");
    client.println("<p id=\"gaz\">"+String(gazy)+"</p><br />");
  
    client.println("<b>Tetno:</b>");
    client.println("<p id=\"gaz\">"+String(bps)+" Przy bramce szumów wynoszącej:"+String(granica)+"</p><br />");
    ////
    client.println("</body>");
    client.println("</html>");
    
  }
}

void loop() 
{
  float curOdczyt = analogRead(pinAnalogowy);
  
  if(curOdczyt>ostatniOdczyt)
  {
    czyRosnie=true;
  }
  else
  {
    if(czyRosnie==true)
    {
      sumaCzasow+=(millis()-ostatnieUderzenie);
      uderzeniaNaPomiar++;
      ostatnieUderzenie = millis();
      BINARKAELO=700;
    }
    czyRosnie=false;
  }
  Serial.print(BINARKAELO);
  Serial.print(",");
  Serial.println(curOdczyt);
  BINARKAELO=0;
  ostatniOdczyt=curOdczyt;
  
  if(millis()>aktualizacja)
  {
    bps=int(60000/(sumaCzasow/uderzeniaNaPomiar));
    aktualizacja = millis()+10000;
    sumaCzasow=0;
    uderzeniaNaPomiar=0;
  }
  

  ////
  sensors.requestTemperatures(); //Pobranie temperatury czujnika
  temperaturaUno = sensors.getTempCByIndex(0);
  temperaturaDos = sensors.getTempCByIndex(1);
  gazy = digitalRead(cyfrowe_wejscie_gazu);
  ////
  
  serwerStuff();
  delay(25);
}
