$(function(){
	var isTaken=true;
	
	function updateAvail(){	//if the username is taken
		isTaken=false;
		$input=$("#newName").val().toLowerCase();	//switch to lowercase to minimize ambiguity
		for(var a=0;a<names.length;a++)
			if($input==names[a].toLowerCase()){
				isTaken=true;
				break;
			}
	}
	
	$("#newName").on("keyup",function(){
		updateAvail();
		if(isTaken||$("#newName").val().length<3||$("#newName").val().length>15)
			$("#nameCheck").html("&times;").css("color","red");
		else
			$("#nameCheck").html("&check;").css("color","green");
	});
	
	$("#regForm").on("submit",function(e){
		updateAvail();
		if(isTaken){
			alert("This username is taken.")
			e.preventDefault();
		}else if($("#newName").val().length<3||$("#newName").val().length>15||$("#newPass").val().length<8){
			alert("Please make sure your password is at least eight characters in length and your username is three to 15.");
			e.preventDefault();
		}
	});
});