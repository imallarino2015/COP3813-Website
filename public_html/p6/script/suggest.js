$("#name").on("keyup",function(){
	var $input=$("input:text").val().toLowerCase();	//convert all the text to lowercase to make it uniform
	var suggestion="";	//names that are being suggested
	if($input.length!=0)
		for(var a=0;a<names.length;a++)	//for every name
			if($input==names[a].substr(0,$input.length).toLowerCase())	//make the names on the array uniformly lowercase and compare
				if(suggestion=="")	//if no names exist
					suggestion=names[a];
				else
					suggestion+=', '+names[a];
	$("#suggestions").text(suggestion);	//populate the tag
});