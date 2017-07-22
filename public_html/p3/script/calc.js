//standard SI values
const GRAVITY=9.81;
const DENSITY=1.01325;

//finds the start of a string of digits from a specified starting point
function findBegNum(equation,loc=equation.length-1){
	while(loc>=0){
		if(!isNaN(parseInt(equation.charAt(loc-1)))||equation.charAt(loc-1)==".")
			loc--;
		else
			return loc;
	}
	return 0;
}

//finds the end of a string of digits from a specified starting point
function findEndNum(equation,loc=0){
	while(loc<equation.length){
		if(!isNaN(parseInt(equation.charAt(loc+1)))||equation.charAt(loc+1)==".")
			loc++;
		else
			return loc;
	}
	return equation.length-1;
}

//get the result
function operate(equation,opLoc){
	//set all the values
	var begLoc=findBegNum(equation,opLoc-1);	//first digit of the left number
	var endLoc=findEndNum(equation,opLoc+1);	//last digit of the right number
	var leftVal=parseFloat(equation.substr(begLoc,opLoc-begLoc));	//value of the number on the left
	var rightVal=parseFloat(equation.substr(opLoc+1,endLoc-opLoc));	//value of the number on the right
	
	//each of the operators
	switch(equation.charAt(opLoc)){
		case '^':
			return equation.substr(0,begLoc)+Math.pow(leftVal,rightVal)+equation.substr(endLoc+1,equation.length-1-endLoc);
		case '*':
			return equation.substr(0,begLoc)+leftVal*rightVal+equation.substr(endLoc+1,equation.length-1-endLoc);
		case '/':
			return equation.substr(0,begLoc)+leftVal/rightVal+equation.substr(endLoc+1,equation.length-1-endLoc);
		case '+':
			return equation.substr(0,begLoc)+(leftVal+rightVal)+equation.substr(endLoc+1,equation.length-1-endLoc);
		case '-':
			return equation.substr(0,begLoc)+leftVal-rightVal+equation.substr(endLoc+1,equation.length-1-endLoc);
		default:
			return "";
	}
}

//solve a given equation with a variable, given a value
function solve(equation,variable,val){
	for(var a=0;a<equation.length;a++)
		if(equation.charAt(a)==variable)	//if a variable is found
			equation=equation.substr(0,a)+val+equation.substr(a+1,equation.length-1-a);	//replace it with its value
	
	for(var a=0;a<equation.length;a++){
		if(equation.charAt(a)=='('){	//if brackets are found
			var layer=0;	//initialize layer count
			var isClosed=false;	//verify it closes at some point
			for(var b=a;b<equation.length;b++){	//find the closing bracket
				switch(equation.charAt(b)){
					case '(':
						layer++;	//one layer deeper
						break;
					case ')':
						layer--;	//up one level
						break;
				}
				if(layer==0){
					isClosed=true;	//the brackets have been closed
					equation=equation.substr(0,a)+solve(equation.substr(a+1,b-a-1),variable,val)+equation.substr(b+1,equation.length-b-1);	//solve the inside of the brackets recursively
					break;
				}
			}
			if(!isClosed){
				return "";	//error
			}
		}
	}
	
	//in the case of each of the operations, in order of pemdas is found
	for(var a=equation.length-1;a>=0;a--)
		if(equation.charAt(a)=='^')
			equation=operate(equation,a);
	
	for(var a=0;a<equation.length;a++)
		if(equation.charAt(a)=='*'||equation.charAt(a)=='/')
			equation=operate(equation,a);
	
	for(var a=0;a<equation.length;a++)
		if(equation.charAt(a)=='+'||equation.charAt(a)=='-')
			equation=operate(equation,a);
	
	return equation;
}

function propellerCalc(area,angle,lRadius,uRadius){	//Basically uses the Riemann sum to approximate the values of the force for a given shape of propeller
	var sections=10000;	//the sections is arbitrarily high, but too high will cause overflow - or crash
	var secLength=(uRadius-lRadius)/sections;	//length of each section area is l*w
	var sum=0;	//initialize the sum
	
	for(var a=lRadius;a<uRadius;a+=secLength){
		angleInRads=solve(angle,'r',a)*Math.PI/180;	//convert the angle to radians
		sum+=secLength/(solve(area,'r',a)*Math.sin(angleInRads));	//A=l*w, or l*1/w, in this case
	}
	//result
	return sum;
}

document.getElementById("calc").onclick=function(){
	//get each of the user input values
	var mass=parseFloat(document.getElementById("mass").value);
	var lRadius=parseFloat(document.getElementById("lRadius").value);
	var uRadius=parseFloat(document.getElementById("uRadius").value);
	var time=parseFloat(document.getElementById("time").value);
	var resistance=parseInt(document.getElementById("resistance").value);
	var propCount=parseInt(document.getElementById("numOfProps").value);
	var angle=document.getElementById("angle").value;
	var area=document.getElementById("area").value;
	
	//multiply and divide the constants
	var constantVals=(GRAVITY*mass*resistance)/(DENSITY*time*propCount);
	
	//output
	document.getElementById("calculation").innerHTML=Math.sqrt(constantVals*propellerCalc(area,angle,lRadius,uRadius));
};