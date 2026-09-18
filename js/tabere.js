window.addEvent("domready",function(){
	$('trimite').addEvent("click",function(e){
		e = new Event(e);
		//stop propagation
		if(!$('raspuns').hasClass("loading")) $('raspuns').addClass("loading");
		$('contact').setStyle("display","none");
		$('raspuns').setStyle("display","block");				
		var myAjax = new Ajax('mailer.php', {  
		   data:$('consulta'),
		   update:$('raspuns'),
		   onComplete:function(){
			 var desaparecer = new Fx.Style('raspuns', 'opacity', {duration:6000});
			  if($('raspuns').hasClass("loading")) $('raspuns').removeClass("loading");
				 desaparecer.start(1,0).addEvent("onComplete",function(){
					 $('raspuns').setStyle("display","none");
					 $('raspuns').setStyle("opacity","1");
					 $('raspuns').empty();
					 $('contact').setStyle("display","block");
			 	 }); //margin-top is set to 10px immediately
		  	 }
		}).request();
	})
})
//Tooltips
var Tips2 = new Tips($$('.tooltip'), {
	initialize:function(){
		this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 300, wait: false}).set(0);
	},
	onShow: function(toolTip) {
		this.fx.start(1);
	},
	onHide: function(toolTip) {
		this.fx.start(0);
	}
});

// SiFR
//<![CDATA[
if(typeof sIFR == "function"){
	sIFR.replaceElement(named({sSelector:"h2", sFlashSrc:"sifr/sifr.swf", sColor:"#000000", sLinkColor:"#FFFFFF", sBgColor:"#000000", sHoverColor:"#CCCCCC", sWmode:"transparent",sFlashVars:"textalign=right"}));
};
//]]>

var scroll = new Fx.Scroll('continut', {
	wait: false,
	duration: 1500,
	offset: {'x': 0, 'y': 0},
	transition: Fx.Transitions.Sine.easeInOut
});

$('slideout').addEvent('click', function(e){
	e = new Event(e);
	mySlide.slideOut();
	scroll.toElement('content1');
	e.stop();
});
 
$('link2').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content2');
});
 
$('link3').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content3');
});

$('link4').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content4');
});

$('link5').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content5');
});

$('link6').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content6');
});

$('link7').addEvent('click', function(event) {
	event = new Event(event).stop();
	scroll.toElement('content7');
});

 
// Slide Noi despre noi
var mySlide = new Fx.Slide('despre-noi');
mySlide.hide();
 
$('slidein').addEvent('click', function(e){
	e = new Event(e);
	mySlide.slideIn();
	scroll.toElement('content1');
	e.stop();
});


// Popup: When the user clicks on div, open the popup
function myPopupFunction() {
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}

function myPopupFunction1() {
  var popup = document.getElementById("myPopup1");
  popup.classList.toggle("show");
}

function myPopupFunction2() {
  var popup = document.getElementById("myPopup2");
  popup.classList.toggle("show");
}




function myFunction4() {
  var popup = document.getElementById("myPopup1");
  popup.classList.toggle("show");
}


function myFunction5() {
  var popup = document.getElementById("myPopup2");
  popup.classList.toggle("show");
}





function myFunction7() {
  var popup = document.getElementById("myPopup1");
  popup.classList.toggle("show");
}


function myFunction8() {
  var popup = document.getElementById("myPopup2");
  popup.classList.toggle("show");
}

function myPopupFunctionFA() {
  var popup = document.getElementById("myPopupFA");
  popup.classList.toggle("show");
}


// Accordion collapse
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
