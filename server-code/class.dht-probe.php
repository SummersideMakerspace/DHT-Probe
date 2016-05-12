<?php
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

define('ABSPATH', dirname(__FILE__) . '/');
define('PROBE_URI', 'http://192.168.0.177/');

class DHT_Probe{
	var $running;

	function DHT_Probe(){
		$this->init();
		$this->run();
		$this->shutdown();
	}

	function init(){
		if(!file_exists(ABSPATH . 'dht-probe.rrd')){
			if(!rrd_create(ABSPATH . 'dht-probe.rrd', array(
				'-s 5s',
				'DS:humidity:GAUGE:300:0:100',
				'DS:temperature:GAUGE:300:-40:80',
				'DS:heat_index:GAUGE:300:-50:150',
				'DS:light_level:GAUGE:300:0:1024',
				'RRA:MIN:0.5:5:172800',
				'RRA:MAX:0.5:5:172800',
				'RRA:AVERAGE:0.5:5:172800',
				'RRA:MIN:0.5:60:129600',
				'RRA:MAX:0.5:60:129600',
				'RRA:AVERAGE:0.5:60:129600',
				'RRA:MIN:0.5:3600:13392',
				'RRA:MAX:0.5:3600:13392',
				'RRA:AVERAGE:0.5:3600:13392',
				'RRA:MIN:0.5:86400:3660',
				'RRA:MAX:0.5:86400:3660',
				'RRA:AVERAGE:0.5:86400:3660'
			))){
				die("Error creating rrd file.\n");
			}
		}

	declare(ticks = 1);
	pcntl_signal(SIGINT, array($this, 'signalHandler'));
		$this->running = true;
	}

	function signalHandler($signal){
		switch($signal){
			case SIGINT:
				$this->running = false;
			break;
		}
	}

	function run(){
		while($this->running){
			$loop_start = microtime(true);

			$this->pollProbe();

			time_sleep_until($loop_start + 5);
		}
	}

	function pollProbe(){
		$data = file_get_contents('http://192.168.0.177/');
		preg_match_all('/<dd>([^<]+)<\/dd>/', $data, $matches);
		rrd_update(ABSPATH . 'dht-probe.rrd', array(
			'N:' . $matches[1][0] . ':' . $matches[1][1] . ':' . $matches[1][2] . ':' . $matches[1][3]
		));
	}

	function shutdown(){
	}
}


								