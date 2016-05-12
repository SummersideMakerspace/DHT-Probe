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

const PROGMEM uint16_t underworld_melody[] = {
  NOTE_C4, NOTE_C5, NOTE_A3, NOTE_A4,
  NOTE_AS3, NOTE_AS4, 0,
  0,
  NOTE_C4, NOTE_C5, NOTE_A3, NOTE_A4,
  NOTE_AS3, NOTE_AS4, 0,
  0,
  NOTE_F3, NOTE_F4, NOTE_D3, NOTE_D4,
  NOTE_DS3, NOTE_DS4, 0,
  0,
  NOTE_F3, NOTE_F4, NOTE_D3, NOTE_D4,
  NOTE_DS3, NOTE_DS4, 0,
  0, NOTE_DS4, NOTE_CS4, NOTE_D4,
  NOTE_CS4, NOTE_DS4,
  NOTE_D4, NOTE_GS3,
  NOTE_G3, NOTE_CS4,
  NOTE_C4, NOTE_FS4, NOTE_F4, NOTE_E3, NOTE_AS4, NOTE_A4,
  NOTE_GS4, NOTE_DS4, NOTE_B3,
  NOTE_AS3, NOTE_A3, NOTE_GS3,
  0, 0, 0
};

const PROGMEM uint16_t underworld_tempo[] = {
  12, 12, 12, 12,
  12, 12, 6,
  3,
  12, 12, 12, 12,
  12, 12, 6,
  3,
  12, 12, 12, 12,
  12, 12, 6,
  3,
  12, 12, 12, 12,
  12, 12, 6,
  6, 18, 18, 18,
  6, 6,
  6, 6,
  6, 6,
  18, 18, 18, 18, 18, 18,
  10, 10, 10,
  10, 10, 10,
  3, 3, 3
};

const PROGMEM uint16_t fanfare_melody[] = {
  NOTE_F5, NOTE_F5, NOTE_F5, 
  NOTE_F5,
  NOTE_CS5, NOTE_DS5,
  NOTE_F5, NOTE_REST, NOTE_DS5,
  NOTE_F5,
  NOTE_C5, NOTE_AS4, NOTE_C5, NOTE_AS4, NOTE_DS5,
  NOTE_DS5, NOTE_D5, NOTE_DS5, NOTE_D5, NOTE_D5,
  NOTE_C5, NOTE_AS4, NOTE_A4, NOTE_AS4, NOTE_G4,
  NOTE_REST,
  NOTE_C5, NOTE_AS4, NOTE_C5, NOTE_AS4, NOTE_DS5,
  NOTE_DS5, NOTE_D5, NOTE_DS5, NOTE_D5, NOTE_D5,
  NOTE_C5, NOTE_AS4, NOTE_C5, NOTE_DS5, NOTE_F5,
  NOTE_REST
};

const PROGMEM uint16_t fanfare_melody_tempo[] = {
  138, 138, 138,
  434,
  434, 434,
  138, 138, 138,
  1322,
  434, 434, 434, 212, 434,
  212, 434, 212, 434, 212,
  434, 434, 434, 212, 1544,
  434,
  434, 434, 434, 212, 434,
  212, 434, 212, 434, 212,
  434, 434, 434, 212, 1544,
  434
};