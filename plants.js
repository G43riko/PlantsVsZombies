slnecnica=function(x,y){
	this.x=Math.floor(x/plocha.kockaWidth)*plocha.kockaWidth;
	this.y=Math.floor(y/plocha.kockaHeight)*plocha.kockaHeight;
	this.start=Date.now();
	this.healt=6;
	slnk-=this.cena;
	this.suns=50;
	this.lastSun=Date.now();
	this.image = new Image();
	this.image.src="images/SunflowerHD.png";
};
slnecnica.prototype={
	cena:25,
	draw:function(){
		context.drawImage(this.image,this.x,this.y,plocha.kockaWidth,plocha.kockaHeight);
	},
	checkGetingSun:function(){
		if(Date.now()-this.lastSun>=10000){
			suns.push(new Sun(this.x,this.y,this.suns));
			this.lastSun=Date.now();
		};
	}
};
kvetina=function(x,y){
	this.x=Math.floor(x/plocha.kockaWidth)*plocha.kockaWidth;
	this.y=Math.floor(y/plocha.kockaHeight)*plocha.kockaHeight;
	this.start=Date.now();
	this.healt=10;
	this.attack=1;
	this.lastShot=Date.now();
	slnk-=this.cena;
	this.image = new Image();
	this.image.src="images/PeashooterHD.png";
};
kvetina.prototype={
	cena:50,
	draw:function(){
		context.drawImage(this.image,this.x,this.y,plocha.kockaWidth,plocha.kockaHeight);
	},
	checkShot:function(){
		
		if(Date.now()-this.lastShot>=500){		
			shots.push(new Shot(this.x+2*plocha.kockaWidth/2,this.y+plocha.kockaHeight/2,5,0,this.attack));
			this.lastShot=Date.now();
			chyba++;
			console.log(chyba);
			console.log(zombies);	
		}
	}
};
chyba=0;
walker=function(x,y){
	this.healt=3;
	this.x=x;
	this.y=y;
	this.dx=-0.5;
	this.dy=0;
	this.image = new Image();
	this.image.src="images/ZombieHD.png";
};
walker.prototype={
	draw:function(){
		context.drawImage(this.image,this.x,this.y,plocha.kockaWidth,plocha.kockaHeight);
	},
	move:function(){
		this.x+=this.dx;
		this.y+=this.dy;
	}
};
Shot=function(x,y,dx,dy,attack){
	this.polomer=5;
	this.x=x;
	this.y=y;
	this.dx=dx;
	this.dy=dy;
	this.attack=attack;	
	
};

Shot.prototype={
	draw:function(){
		context.beginPath();
		context.arc(this.x, this.y, this.polomer, 0, 2 * Math.PI, false);
		context.fillStyle = 'yellow';
		context.fill();
	},
	move:function(){
		this.x+=this.dx;
		this.y+=this.dy;
	},
	checkColision:function(){
		for(j in zombies){
			var toto=zombies[j];
			if((this.y-plocha.kockaHeight/2)==toto.y){			
				if(this.x+this.polomer>=toto.x){		
					console.log("náraz!!");							
					toto.healt-=this.attack;
					this.attack=0;	
					if(toto.healt<=0){
						zombies.splice(j,1);
					}
					return true;
				}
			}
		}
		return false;
	}
};
Sun=function(x,y,sunS){
	this.suns=sunS;
	this.polomer=10;
	this.x=(x+plocha.kockaWidth/2);
	this.y=(y+this.polomer);
	this.dx=Math.random()*1-0.5;
	this.dy=Math.random()*1-0.5;
	this.startTime=Date.now();
	this.image = new Image();
	this.image.src="images/SunHD.png";
};

Sun.prototype={
	draw:function(){
		context.drawImage(this.image,this.x,this.y,this.polomer*2,this.polomer*2);
	},
	move:function(){
		var xSos=this.x;
		var ySos=this.y;
		this.x+=this.dx;
		this.y+=this.dy;
		if(this.x-this.polomer<0){
			this.dx*=-1;
			this.x=this.polomer;
		}
		if((this.x+this.polomer>canvas.width)){
			this.dx*=-1;
			this.x=canvas.width-this.polomer;
		}
		if(this.y-this.polomer<0){
			this.dy*=-1;
			this.y=this.polomer;
		}
		if(this.y+this.polomer>canvas.height){
			this.dy*=-1;
			this.y=canvas.height-this.polomer;
		}
		this.dy-=this.dy*Math.random()/100;
		this.dx-=this.dx*Math.random()/100;
	},
	checkLife:function(){
		if(Date.now()-this.startTime>=8000){
			return true;
		}
		return false;
	}
};
