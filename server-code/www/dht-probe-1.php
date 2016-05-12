<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>DHT Probe 1 | Marberg @ SummersideMakerspace</title>
		<!--
		
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
		
		-->
		<style>
h3{
	border-top: 1px solid #dcdcdc;
	padding-top: 30px;
	margin-top: 30px;
}

h3:first-child{
	border-top: none;
	padding-top: 0;
}
		</style>
	</head>
	<body>
		<h1>DHT (Humidity &amp; Temperature) Probe #1</h1>
		<h2>Located in the comfy seating area</h2>
		<p>Last data reset: 2016-04-20 23:18:00</p>
		<hr>
		<div class="graphs">
			<h3>Last Five Minutes</h3>
			<p>Data resolution: 5 seconds</p>
			<?php graph_five_minute() ?>
			<h3>Last Hour</h3>
			<p>Data resolution: 5 seconds</p>
			<?php graph_last_hour() ?>
			<h3>Last Day </h3>
			<p>Data resolution: 60 seconds</p>
			<?php graph_last_day() ?>
			<h3>Last 10 Days</h3>
			<p>Data resolution: 1 hour</p>
			<?php graph_10_days() ?>
			<h3>Last Month</h3>
			<p>Data resolution: 1 hour</p>
			<?php graph_last_month() ?>
			<h3>Last 3 Months</h3>
			<p>Data resolution: 1 day</p>
			<?php graph_3_months() ?>
			<h3>Last 18 Months</h3>
			<p>Data resolution: 1 day</p>
			<?php graph_18_months() ?>
			<?php include 'footer.php'; ?>
		</div>
	</body>
</html>
<?php

function graph_five_minute(){
	graphDHTProbe('-s -300s', 'last-five-minutes', true);
}

function graph_last_hour(){
	graphDHTProbe('-s -1h', 'last-hour', true);
}

function graph_last_day(){
	graphDHTProbe('-s -24h', 'last-day', true);
}

function graph_10_days(){
	graphDHTProbe(array('-s -10d', '-S 3600'), '10-days');
}

function graph_last_month(){
	graphDHTProbe(array('-s -1M', '-S 3600'), 'last-month');
}

function graph_3_months(){
	graphDHTProbe(array('-s -3M', '-S 86400'), '3-months');
}

function graph_18_months(){
	graphDHTProbe(array('-s -550d', '-S 86400'), '18-months');
}

function graph_10_years(){
	graphDHTProbe(array('-s -10y', '-S 86400'), '10-years');
}

function graphDHTProbe($options, $image_file, $suppress_min_max = false){
	$rrd_file = '/usr/local/dht-probe/dht-probe.rrd';

	if(!is_array($options)){
		$options = array($options);
	}

	$options = array_merge($options, array(
		'-z',
		'-w 1220',
		'-h 320',
		'DEF:ds0=' . $rrd_file . ':humidity:AVERAGE',
		'DEF:ds1=' . $rrd_file . ':temperature:AVERAGE',
		'DEF:ds2=' . $rrd_file . ':heat_index:AVERAGE',
		'LINE1.5:ds0#0160c7:Humidity Average (%)',
		'LINE1.5:ds1#fd7e00:Temperature Average (C)',
		'LINE1.5:ds2#3b9519:Heat Index Average (C)'
	));

	if(!$suppress_min_max){
		$options = array_merge(array(
			'DEF:ds3=' . $rrd_file . ':humidity:MAX',
			'DEF:ds4=' . $rrd_file . ':humidity:MIN',
			'CDEF:ds5=ds3,ds4,-',
			'LINE1:ds4:',
			'AREA:ds5#c3d3eb66:Humidity Range:STACK',
			'DEF:ds6=' . $rrd_file . ':temperature:MAX',
			'DEF:ds7=' . $rrd_file . ':temperature:MIN',
			'CDEF:ds8=ds6,ds7,-',
			'LINE1:ds7:',
			'AREA:ds8#fdcea066:Temperature Range:STACK',
			'DEF:ds9=' . $rrd_file . ':heat_index:MAX',
			'DEF:ds10=' . $rrd_file . ':heat_index:MIN',
			'CDEF:ds11=ds9,ds10,-',
			'LINE1:ds10:',
			'AREA:ds11#c3e49466:Heat Index Range:STACK',
			'LINE1:ds4#6f94da:',
			'LINE1:ds7#fd9c42:',
			'LINE1:ds10#83b95a:',
			'LINE1:ds3#6f94da:',
			'LINE1:ds6#fd9c42:',
			'LINE1:ds9#83b95a:',
		), $options);
	}

	rrd_graph(dirname(__FILE__) . '/graphs/dht-probe-' . $image_file . '.png', $options);

	?>
	<img src="/graphs/dht-probe-<?php echo $image_file ?>.png" alt="Generated Image">
	<?php
}

