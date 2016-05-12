/*
		
Copyright 2016 Summerside Makerspace Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.		
		
*/

#include <avr/pgmspace.h>
#include <SPI.h>
#include <Ethernet.h>
#include "DHT.h"

#include "notes.h"
#include "songs.h"

#define DHTPIN 2
#define DHTTYPE DHT22

#define LIGHTPIN 0

#define MELODY1PIN 3

DHT dht(DHTPIN, DHTTYPE);

byte mac[] = {
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
IPAddress ip(192, 168, 0, 177);
EthernetServer server(80);

#define MELODY1 0

void setup() {
  Serial.begin(9600);
  while (!Serial) {
  }

  Ethernet.begin(mac, ip);
  server.begin();

  dht.begin();

  pinMode(MELODY1PIN, OUTPUT);
}

bool lightStatus = true;

void loop() {
  EthernetClient client = server.available();
  if (client) {
    boolean currentLineIsBlank = true;
    while (client.connected()) {
      if (client.available()) {
        char c = client.read();
        if (c == '\n' && currentLineIsBlank) {
          client.println("HTTP/1.1 200 OK\nContent-Type: text/html\nConnection: close\nRefresh: 5\n\n");
          client.print("<!DOCTYPE HTML><html>");
          float h = dht.readHumidity();
          float t = dht.readTemperature();
          float f = dht.readTemperature(true);

          if(!isnan(h) && !isnan(t) && !isnan(f)){
            float hif = dht.computeHeatIndex(f, h);
            float hic = dht.computeHeatIndex(t, h, false); 
            client.print("<dl><dt>Humidity</dt><dd>");
            client.print(h);
            client.print("</dd>\n<dt>Temperature</dt><dd>");
            client.print(t);
            client.print("</dd>\n<dt>Heat Index</dt><dd>");
            client.print(hic);
            client.print("</dd></dl>");
          }

          float light = analogRead(LIGHTPIN);
          client.print("<dl><dt>Light Level</dt><dd>");
          client.print(light);
          client.print("</dd></dl>");

          client.print("</html>");
          break;
        }
        if (c == '\n') {
          currentLineIsBlank = true;
        } else if (c != '\r') {
          currentLineIsBlank = false;
        }
      }
    }
    delay(1);
    client.stop();
  }
  float light = analogRead(LIGHTPIN);
  if(light < 250){
    if(lightStatus){
      lightStatus = false;
      sing(2);  
    }
  } else {
    if(!lightStatus){
      lightStatus = true;
      sing(3);  
    }  
  }
}

void sing(int song) {
  int size;
  switch(song){
    case 2:
      size = 56;
      for (int thisNote = 0; thisNote < size; thisNote++) {
        int noteDuration = 1000 / pgm_read_word_near(underworld_tempo + thisNote);   
        buzz(MELODY1PIN, pgm_read_word_near(underworld_melody + thisNote), noteDuration);
        int pauseBetweenNotes = noteDuration * 1.30;
        delay(pauseBetweenNotes);
      }
    break;
    
    case 3:
      size = 42;
      for (int thisNote = 0; thisNote < size; thisNote++) { 
        int noteDuration = pgm_read_word_near(fanfare_melody_tempo + thisNote);
        buzz(MELODY1PIN, pgm_read_word_near(fanfare_melody + thisNote), noteDuration);
        delay(10);     
      }   
    break;
  } 
}
 
void buzz(int targetPin, long frequency, long length) {
  if(frequency == NOTE_REST){
    delay(length);
    return;
  }
  long delayValue = 1000000 / frequency / 2;
  long numCycles = frequency * length / 1000;
  for(long i = 0; i < numCycles; i++){
    digitalWrite(targetPin, HIGH);
    delayMicroseconds(delayValue);
    digitalWrite(targetPin, LOW);
    delayMicroseconds(delayValue);
  } 
}