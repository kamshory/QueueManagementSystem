/*
Queue Management System
Create by Kamshory
=====================================================================================
This library will play audio queue. Each queue will play 4 audio files synchronously.
No jQuery required but if you will use jQuery for your own code, it is OK.
*/
function PlanetQueue(container, controlers)
{
	this.controlers = controlers;
	this.container = document.querySelector(container);
	this.numberLength = 3;
	this.status = 0;
	this.loaded = 0;
	this.audioFiles = [];
	this.queue = [];
	this.timeout;
	this.baseAudioSrc = 'audio/';
	
	this.init = function()
	{
		var entry;
		var index;
		for(index = 0; index < (atom.controlers.length-1); index++)
		{
			this.container.querySelector(atom.controlers[index]).setAttribute('data-index', index);
		}
		for(index = 0; index < (atom.controlers.length-1); index++)
		{
			this.container.querySelector(atom.controlers[index]).addEventListener('ended', 
				function(e){
					var index = parseInt(e.target.getAttribute('data-index'));
					var selector = atom.controlers[index+1];
					atom.playItem(atom.controlers[index+1]);
				}
			);
		}
		this.container.querySelector(atom.controlers[index]).addEventListener('play', 
			function(e){
				this.status = 1;
			}
		);
		this.container.querySelector(atom.controlers[index]).addEventListener('ended', 
			function(e){
				this.status = 0;
				atom.afterPlayAll();
			}
		);
	};
	this.afterPlayAll = function()
	{
	};
	this.onExecuteQueue = function(customer, counter)
	{
	};
	this.onAddQueue = function(customer, counter)
	{
	};
	this.setAudio = function(index, src)
	{
		if(typeof this.controlers[index] != 'undefined')
		{
			atom.container.querySelector(atom.controlers[index]).setAttribute('src', atom.baseAudioSrc+src);
		}
	};
	this.playAll = function(source)
	{
		source = source || [];
		if(source.length)
		{
			for(var i in source)
			{
				atom.setAudio(i, source[i]);
			}
		}
		this.container.querySelector(this.controlers[0]).play();
	};
	this.isPlaying = function(audio)
	{
		return audio
			&& audio.currentTime > 0
			&& !audio.paused
			&& !audio.ended
			&& audio.readyState > 2;
	};
	this.isPlayingAll = function()
	{
		var index;
		var playing = false;
		for(index = 0; index < (atom.controlers.length-1); index++)
		{
			playing = playing || this.isPlaying(this.container.querySelector(atom.controlers[index]));
		}
		return playing;
	}
	this.stop = function()
	{
		var index;
		for(index = 0; index < (atom.controlers.length-1); index++)
		{
			try
			{
				if(this.isPlaying(this.container.querySelector(atom.controlers[index])))
				{
					this.container.querySelector(atom.controlers[index]).pause();
					this.container.querySelector(atom.controlers[index]).currentTime = 0;
				}
			}
			catch(e)
			{
			}
		}
	}
	this.play = function(source)
	{
		this.playAll(source);
	};
	this.playItem = function(selector)
	{ 
		this.container.querySelector(selector).play();
	};
	this.setSrcCustomer = function(src)
	{
		this.setAudio(0, src);
	}
	this.setSrcCounter = function(src)
	{
		this.setAudio(2, src);
	}
	this.customer = function(number)
	{
		this.stop();
		var src = number.toString();
		if(src.indexOf('.mp3') == -1)
		{
			src = src.lpad('0', this.numberLength);
			src += '.mp3';
		}
		else
		{
			src = number;
		}
		this.setAudio(1, src);
		return this;
	};
	this.toCounter = function(number)
	{
		var src = number.toString();
		if(src.indexOf('.mp3') == -1)
		{
			src = src.lpad('0', this.numberLength);
			src += '.mp3';
		}
		else
		{
			src = number;
		}
		this.setAudio(3, src);
		return this;
	};
	this.preload = function(customerSrc, counterSrc, numberRange)
	{
		atom.audioFiles.push(customerSrc);
		atom.audioFiles.push(counterSrc);
		var i, url, src;
		for(i = numberRange[0]; i<=numberRange[1]; i++)
		{
			src = i.toString();
			if(src.indexOf('.mp3') == -1)
			{
				src = src.lpad('0', this.numberLength);
				src += '.mp3';
			}
			else
			{
				src = i;
			}
			atom.audioFiles.push(atom.baseAudioSrc+src);
		}
		
		var audios = [];
		for(i = 0; i<atom.audioFiles.length; i++)
		{
			url = atom.audioFiles[i];
			audios[i] = new Audio();
			audios[i].addEventListener('canplaythrough', atom.loadedAudio, false);
			audios[i].src = url;
		}
	};

	this.loadedAudio = function()
	{
		atom.loaded++;
		if (atom.loaded == atom.audioFiles.length)
		{
		}
	};
	HTMLElement = typeof(HTMLElement) != 'undefined' ? HTMLElement : Element;
	HTMLElement.prototype.prependChild = function(element) 
	{
		if(this.firstChild) 
		{
			return this.insertBefore(element, this.firstChild);
		} 
		else {
			return this.appendChild(element);
		}
	};
	String.prototype.lpad = function(padString, length) 
	{
		var str = this;
		while (str.length < length)
		{
				str = padString + str;
		}
		return str;
	}
	this.addQueue = function(customer, counter, repeat)
	{
		repeat = repeat || 2;
		var i;
		for(i = 0; i<repeat; i++)
		{
			atom.queue.push({customer:customer, counter:counter, repeat:repeat, executed:false});
		}
		atom.onAddQueue(customer, counter);
		return this;
	};
	this.playQueue = function()
	{
		if(!atom.isPlayingAll())
		{
			var i;
			for(i = 0; i<atom.queue.length; i++)
			{
				if(!atom.queue[i].executed)
				{
					atom.afterPlayAll = function()
					{
						atom.queue[i].executed = true;
						atom.playQueue();
					};
					atom.onExecuteQueue(atom.queue[i].customer, atom.queue[i].counter);
					atom.customer(atom.queue[i].customer).toCounter(atom.queue[i].counter).playAll();
					break;
				}
			}
		}
		else
		{
		}
	};
	var atom = this;
	this.init();
}
