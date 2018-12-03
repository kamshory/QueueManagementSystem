<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Queue Management System</title>
<script type="text/javascript" src="js/queue.js"></script>
<script type="text/javascript">

var queue;
window.onload = function()
{
	queue = new PlanetQueue('#atomicaudio', ['#audio1', '#audio2', '#audio3', '#audio4']);
	// preload files
	queue.preload('nomor-antrian.mp3', 'ke-counter.mp3', [1, 40]);
	// define customer source
	queue.setSrcCustomer('nomor-antrian.mp3');
	// define counter source
	queue.setSrcCounter('ke-counter.mp3');
	
	queue.onExecuteQueue = function(customer, counter)
	{
		document.querySelector('.status').innerHTML = 'NOMOR ANTRIAN '+customer+' KE COUNTER '+counter;
		var cls = 'q-'+customer+'-'+counter;
		var curcls = document.querySelector('.'+cls).className;
		if(curcls.indexOf('q-executed') == -1)
		{
			document.querySelector('.'+cls).className += ' q-executed';
		}
	};
	queue.onAddQueue = function(customer, counter)
	{
		var child = document.createElement('tr');
		var cls = 'q-'+customer+'-'+counter;
		child.setAttribute('class', cls);
		child.innerHTML = '<td>'+customer+'</td><td>'+counter+'</td>';
		document.querySelector('.queue-list').querySelector('tbody').prependChild(child);
	};
	
	// Add queue
	// customer queue number
	// counter number
	// repeat (default = 2)
	queue.addQueue(2, 5, 2)
		.addQueue(3, 4, 2)
		.addQueue(4, 1, 2)
		.addQueue(5, 3, 2)
		.playQueue();
	
	// You can add more queue while previouse file is playing
	// Multiple queue
	queue.addQueue(12, 6, 2)
		.addQueue(14, 5, 2)
		.addQueue(13, 7, 2)
		.playQueue();
	
	// Single queue
	queue.addQueue(17, 1, 2)
		.playQueue();
}
</script>
<style type="text/css">
.queue-list{
	border-collapse:collapse;
}
.queue-list td{
	padding:10px 10px;
	font-size:2.5vw;
	text-align:center;
}
.queue-list thead{
	background-color:#930;
	color:#FFFFFF;
}
.queue-list tbody tr:nth-child(odd) td{
	background-color:#9C0;
}
.queue-list tbody tr:nth-child(even) td{
	background-color:#CF3;
}

.queue-list tbody tr.q-executed td{
	-webkit-filter: grayscale(100%);
    filter: grayscale(100%);
}
.list-container{
	overflow:hidden;
}
</style>
</head>

<body>
<div class="list-container">
<table class="queue-list">
	<thead>
    	<tr>
        	<td>ANTRIAN</td>
        	<td>COUNTER</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
<div class="status">&nbsp;</div>
<div id="atomicaudio" style="position:absolute; left:-10000px; top:-10000px;">
    <audio id="audio1"></audio>
    <audio id="audio2"></audio>
    <audio id="audio3"></audio>
    <audio id="audio4"></audio>
</div>
</body>
</html>