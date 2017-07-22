$(function(){
	function load(id){
		if(localStorage.getItem(id)!==null)
			$('#'+id).val(localStorage.getItem(id));
	}
		
	function save(){
		localStorage.setItem('from',$("#from").val());
		localStorage.setItem('to',$("#to").val());
	}
	
	load('from');
	load('to');
	
	$("#converter").on('submit',function(){
		save();
	});
});