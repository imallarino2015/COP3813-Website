function findRoot(){
	var base1="public_html";	//local host
	var base2="~imallarino2015";	//server
	
	var current=document.location.pathname;	//where the document is located currently
	
	//keep removing directory names until the root of the website is found
	for(var a=current.length-1;a>=0;a--)
		if(current.charAt(a)=="/")
			if(current.substr(a+1,current.length-a-1)==base1||current.substr(a+1,current.length-a-1)==base2)
				return current;
			else
				current=current.substr(0,a);
		return "";
};

var path=findRoot();	//set once run many times

document.write(
'<nav>'
+'	<table class="navigation">'
+'		<tr>'
+'			<td><a href="'+path+'/index.html"><img src="'+path+'/resources/images/buttonHome.png" alt="Home" id="home"/></a></td>'
+'			<td><a href="'+path+'/p1/index.html"><img src="'+path+'/resources/images/button1.png" alt="Project 1" id="p1"/></a></td>'
+'			<td><a href="'+path+'/p2/index.html"><img src="'+path+'/resources/images/button2.png" alt="Project 2" id="p2"/></a></td>'
+'			<td><a href="'+path+'/p3/index.html"><img src="'+path+'/resources/images/button3.png" alt="Project 3" id="p3"/></a></td>'
+'		</tr>'
+'		<tr>'
+'			<td><a href="'+path+'/p4/index.html"><img src="'+path+'/resources/images/button4.png" alt="Project 4" id="p4"/></a></td>'
+'			<td><a href="'+path+'/p5/index.php"><img src="'+path+'/resources/images/button5.png" alt="Project 5" id="p5"/></a></td>'
+'			<td><a href="'+path+'/p6/index.php"><img src="'+path+'/resources/images/button6.png" alt="Project 6 (Upcoming)" id="p6"/></a></td>'
+'			<td><a href="'+path+'/p7/index.php"><img src="'+path+'/resources/images/button7.png" alt="Project 7 (Upcoming)" id="p7"/></a></td>'
+'		</tr>'
+'	</table>'
//+'<font color="red">Red</font> links are under construction.'
+'</nav>'
+'<hr>'
);

//alter the source of the image, depending on if the mouse is hovering over it or not
document.getElementById("home").onmouseover=function(){
	document.getElementById("home").src=path+"/resources/images/buttonHomeDown.png";
};
document.getElementById("home").onmouseout=function(){
	document.getElementById("home").src=path+"/resources/images/buttonHome.png";
};

document.getElementById("p1").onmouseover=function(){
	document.getElementById("p1").src=path+"/resources/images/button1Down.png";
};
document.getElementById("p1").onmouseout=function(){
	document.getElementById("p1").src=path+"/resources/images/button1.png";
};

document.getElementById("p2").onmouseover=function(){
	document.getElementById("p2").src=path+"/resources/images/button2Down.png";
};
document.getElementById("p2").onmouseout=function(){
	document.getElementById("p2").src=path+"/resources/images/button2.png";
};

document.getElementById("p3").onmouseover=function(){
	document.getElementById("p3").src=path+"/resources/images/button3Down.png";
};
document.getElementById("p3").onmouseout=function(){
	document.getElementById("p3").src=path+"/resources/images/button3.png";
};

document.getElementById("p4").onmouseover=function(){
	document.getElementById("p4").src=path+"/resources/images/button4Down.png";
};
document.getElementById("p4").onmouseout=function(){
	document.getElementById("p4").src=path+"/resources/images/button4.png";
};

document.getElementById("p5").onmouseover=function(){
	document.getElementById("p5").src=path+"/resources/images/button5Down.png";
};
document.getElementById("p5").onmouseout=function(){
	document.getElementById("p5").src=path+"/resources/images/button5.png";
};

document.getElementById("p6").onmouseover=function(){
	document.getElementById("p6").src=path+"/resources/images/button6Down.png";
};
document.getElementById("p6").onmouseout=function(){
	document.getElementById("p6").src=path+"/resources/images/button6.png";
};

document.getElementById("p7").onmouseover=function(){
	document.getElementById("p7").src=path+"/resources/images/button7Down.png";
};
document.getElementById("p7").onmouseout=function(){
	document.getElementById("p7").src=path+"/resources/images/button7.png";
};