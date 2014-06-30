<!DOCTYPE HTML>
<html lang="sk">
	<head>
		<style>
			canvas {
				border:1px pink solid;
				position:absolute;
				top:0px;
				left:0px;
			}
		</style>
		<script src="plants.js"></script>
	</head>
	<body>
		<canvas id="Canvas" width="1400" height="800"></canvas>		
	</body>
</html>
<script>
	var canvas = document.getElementById('Canvas');
	var context = canvas.getContext('2d');
	plocha={
		background:"green",
		numX:15,
		numY:10,
		kockaWidth:80,
		kockaHeight:80,
		plocha:[],
		fill:function(pole){
			for(var i=0;i<this.numY;i++){
				var helper=[];
				for(var j=0;j<this.numX;j++){
					helper.push(0);
				}
				pole.push(helper);
			};
		},
		draw:function(){
			context.beginPath();
			context.rect(0, 0, this.numX*this.kockaWidth, canvas.height);
			context.fillStyle = this.background;
			context.fill();
		}
	}
	sidebar={
		background:"grey",
		draw:function(){
			context.beginPath();
			context.rect(plocha.numX*plocha.kockaWidth, 0, 2.5*plocha.kockaWidth, canvas.height);
			context.fillStyle = this.background;
			context.fill();

			context.font = 'italic 35pt Calibri';
			context.fillStyle = 'blue';
			context.fillText("slnk: "+slnk, (plocha.numX*plocha.kockaWidth+10), 45);

			for(i in this.plants){
				var toto =this.plants[i];
				context.beginPath();
				context.arc(toto["x"],toto["y"], plocha.kockaWidth/2, 0, 2 * Math.PI, false);
				context.fillStyle = toto["color"];
				//console.log(player.selected+" == "+toto.name);
				if(player.select[0]==toto.name){
					context.lineWidth = 5;
					context.strokeStyle = 'white';
					context.stroke();
				}
				context.fill();
			}
		},

		plants:{
			0:{
				id:"a",
				color:"brown",
				name:"slnecnica",
				x:1290,
				y:105,
			},
			1:{
				id:"b",
				color:"black",
				name:"kvetina",
				x:1290,
				y:105+90,
			}
		}

	}
	player={
		select:false
	}
	function init(){
		plocha.fill(plocha.plocha);
		isPlaying=false;
		ticks=0;
		plants=[];
		zombies=[];
		suns=[];
		shots=[];
		slnk=100;
		lastZombieAdd=Date.now()
	}

	function mainLoop(){
		check();
		seeder();
		draw();
		move();
		ticks++;
		if(isPlaying){
			requestAnimationFrame(mainLoop);
		}
	}

	function draw(){
		plocha.draw();
		
		for(i in plants){
			plants[i].draw();
		}
		for(i in zombies){
			zombies[i].draw();
		}
		for(i in shots){
			shots[i].draw();
		}
		for(i in suns){
			suns[i].draw();
		}
		sidebar.draw();
	}

	function move(){
		for(i in zombies){
			zombies[i].move();
		}
		for(i in suns){
			suns[i].move();
		}
		for(i in shots){
			shots[i].move();
		}
	}			

	function check(){
		for(i in plants){
			if(typeof(plants[i].checkGetingSun) === "function"){
				plants[i].checkGetingSun();
			}
			if(typeof(plants[i].checkShot) === "function"){
				plants[i].checkShot();
			}
		}
		i=0;
		for(i in suns){
			if(suns[i].checkLife()){
				suns.splice(i,1);
			}
		}
		i=0;
		for(i in shots){
			var toto=shots[i];
			
			if(toto.checkColision()){
				shots.splice(i,1);
				//i--;			
			}
			if(toto.x-toto.polomer>=plocha.numX*plocha.kockaWidth){
				shots.splice(i,1);
				//i--;
			}
		}
	}
	function seeder(){		
		if(Date.now()-lastZombieAdd>=10000){
			var riadok=Math.floor(Math.random()*10);
			zombies.push(new walker((plocha.numX*plocha.kockaWidth+40),riadok*plocha.kockaHeight));
			lastZombieAdd=Date.now();
		}		
	}
	function click(data){		
		for(i in sidebar.plants){
			var toto=sidebar.plants[i];
			var X=data.clientX;
			var Y=data.clientY;
			var dlzka=Math.sqrt((X-toto["x"])*(X-toto["x"])+((Y-toto["y"])*(Y-toto["y"])));
			if(dlzka<40){
				if(player.select[0]==toto["name"]){
					player.select=false
					break;
				}
				else{
					if(slnk-window[toto["name"]].prototype.cena>=0){
						player.select=[toto["name"],toto["id"]];
						break;
					}
				}	
			}	
		}
		for(i in suns){
			var toto=suns[i]
			if(Math.sqrt((data.clientX-toto.x)*(data.clientX-toto.x)+((data.clientY-toto.y)*(data.clientY-toto.y)))<toto.polomer){
				slnk+=toto.suns;
				suns.splice(i,1);
				return true;
			}
		}		
		if((data.x>=0)&&(data.x<=plocha.numX*plocha.kockaWidth)&&(data.y>=0)&&(data.y<=plocha.numY*plocha.kockaHeight)&&(player.select)){
			var Xko=Math.floor(X/plocha.kockaWidth);
			var Yko=Math.floor(Y/plocha.kockaHeight);
			if(plocha.plocha[Yko][Xko]!=0){
				return false;
			}
			plants.push(new window[player.select[0]](data.clientX,data.clientY));
			plocha.plocha[Yko][Xko]=player.select[1];
			player.select=false;			
		}
	}

	window.onclick=function(d){click(d);}
	init();
	isPlaying=true;
	mainLoop();
</script>
