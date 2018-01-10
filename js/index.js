/* 
 ** Funcion Cronometro
 ** 
 ** Creado por Favio Brntn
 */
function Cronometro(){
	this.tiempo = localStorage.getItem('tiempo') || 60;
	this.intervalo = undefined;
	this.timer = document.getElementById("timer");

	$(".comenzar").click(function(e){
		e.preventDefault();
		this.comenzar();
	}.bind(this));

	$(".detener").click(function(e){
		e.preventDefault();
		this.detener();
	}.bind(this));

	$("#form_tiempo").submit(function(e){
		e.preventDefault();
		if($("#tiempo").val() > 0){
			localStorage.setItem('tiempo', $("#tiempo").val());
			window.location.href="index.php";
		}
	});
	
}

Cronometro.prototype.control = function() {
	if(this.tiempo <= 10 && this.tiempo > 0 ){
		var x = document.getElementById("tic");
		this.timer.style.color = 'red';
		x.play();
	}
	if(this.tiempo == 0){
		var x = document.getElementById("alarma");
		this.detener();
		x.play();
		this.tiempo = localStorage.getItem('tiempo') || 60;
	}
	this.timer.innerHTML = (this.tiempo--);
}

Cronometro.prototype.detener = function(){
	$(".detener").css("display", 'none');
	$(".comenzar").css("display", '');
	clearTimeout(this.intervalo);
}

Cronometro.prototype.comenzar = function(){
	$(".comenzar").css("display", 'none');
	$(".detener").css("display", '');
	this.intervalo = setInterval(() => {
		this.control();
	}, 1000);
}