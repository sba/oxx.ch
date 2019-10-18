 

<SCRIPT LANGUAGE="JavaScript">



number_of_sponsors=3;


var sctr=0;
var halt=0;
var isn=new Array();
for (i=0;i<number_of_sponsors;i++){
 isn[i]=new Image();

}





isn[0].src="images/stories/logos/logo_1.png";
isn[1].src="images/stories/logos/logo_2.png";
isn[2].src="images/stories/logos/logo_3.png";





var durl=new Array();

durl[0]="http://www.zofingen.ch";
durl[1]="http://www.petzi.ch";
durl[2]="http://www.ag.ch/kuratorium/de/pub/";





function rotateIt(){

 if (halt!=1){
  sctr++;
  if (sctr>number_of_sponsors-1){
   sctr=0;

   }

  document.Sponsoren.src=isn[sctr].src;
  setTimeout("rotateIt()",5000);

  }

 }



function doIt(){

 halt=1;
 parent.location.href=durl[sctr];

 }



function dispIt(){
 parent.window.status=durl[sctr];

 }



</SCRIPT>







<A HREF="#" onClick="doIt();return false" onMouseover="dispIt();return true;"><img src="images/stories/logos/logo_1.png" WIDTH="100" height="60" NAME="Sponsoren" BORDER=0></a><SCRIPT LANGUAGE="JavaScript"> sctr=0; rotateIt();</SCRIPT>



