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
	queue = new PlanetQueue('#atomicaudio', ['#audio1', '#audio2', '#audio3', '#audio4'], '#bell');
	queue.baseAudioSrc = 'audio/';
	// preload files
	queue.preload('nomor-antrian.mp3', 'ke-counter.mp3', [1, 40]);
	// define customer source
	queue.setSrcCustomer('nomor-antrian.mp3');
	// define counter source
	queue.setSrcCounter('ke-counter.mp3');
	// define bell source
	queue.setSrcBell('bell.mp3');
	
	// override onExecuteQueue
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
	// override onAddQueue
	queue.onAddQueue = function(customer, counter)
	{
		var child = document.createElement('tr');
		var cls = 'q-'+customer+'-'+counter;
		child.setAttribute('class', cls);
		child.innerHTML = '<td>'+customer+'</td><td>'+counter+'</td>';
		document.querySelector('.queue-list').querySelector('tbody').prependChild(child);
	};
	queue.afterPlayBell = function()
	{
		queue.playQueue();
	};
	
	// Add queue
	// customer queue number
	// counter number
	// repeat (default = 2)
	queue.addQueue(138, 12, 2)
		.addQueue(5, 3, 2)
		.playBellAndQueue();
	
	// You can add more queue while previouse file is playing
	// Multiple queue
	queue.addQueue(12, 6, 2)
		.addQueue(13, 7, 2)
		.playBellAndQueue();
	
	// Single queue
	queue.addQueue(51, 1, 2)
		.addQueue(54, 4, 2)
		.playBellAndQueue();
		
	initVideo('video.video');	
}
var videoList = [
	'video/video1.mp4',
	'video/video2.mp4',
	'video/video3.mp4',
	'video/video4.mp4'
];
function initVideo(selector)
{
	document.querySelector(selector).addEventListener('ended', function(e){
		playVideo(selector);
	});
	playVideo(selector);
}
function playVideo(selector)
{
	var index = parseInt(document.querySelector(selector).getAttribute('data-index'));
	var num = videoList.length;
	var cur = index;
	document.querySelector(selector).setAttribute('src', videoList[cur]);
	var next = (cur+1) % num;
	document.querySelector(selector).setAttribute('data-index', next);
	document.querySelector(selector)
	document.querySelector(selector).play();
}
</script>
<style type="text/css">
body{
	margin:0;
	padding:0;
	background-color:#09F;
	background:url(image/image.jpg) no-repeat center center;
	background-size:cover;
	font-family:Verdana, Geneva, sans-serif;
}
.queue-list{
	border-collapse:collapse;
}
.queue-list td{
	padding:10px 10px;
	text-align:center;
	font-size:36px;
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
}
.all {
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
    height: calc(100vh - 40px);
    overflow: hidden;
}

.list-container{
	float:right;
	width:400px;
	height:calc((100vw - 460px) * 9 / 16);
	background-color:#EEEEEE;
	overflow:hidden;
}
.list-container table{
	width:100%;
	box-sizing:border-box;
	overflow:hidden;
}
.video-container{
	background-color:#FFFFFF;
}
.video{
	width:calc(100vw - 460px);
	height:calc((100vw - 460px) * 9 / 16);
}
.status-bar{
	padding:20px 0px;
	background-color:#000;
	color:#FFFFFF;
	display:table-column;
	vertical-align:middle;
	font-size:36px;
	position:absolute;
	bottom:20px;
	width:100%;
	left:0;
	text-align:center;
}
</style>
</head>

<body>

<div class="all">
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

<div class="video-container video">
<video data-index="0" class="video" muted="muted" poster="image/poster.png" controls></video>
</div>

<div class="status-bar">
<div class="status">&nbsp;</div>
</div>

<div id="atomicaudio" style="position:absolute; left:-10000px; top:-10000px;">
    <audio id="audio1"></audio>
    <audio id="audio2"></audio>
    <audio id="audio3"></audio>
    <audio id="audio4"></audio>
    <audio id="bell"></audio>
</div>
</div>
</body>
</html>