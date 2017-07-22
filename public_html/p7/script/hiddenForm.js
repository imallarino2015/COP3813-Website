$(function(){
	var $formBtn=$('#showForm');
	var $popUp=$('#popUp');
	var $form=$('#hiddenForm');
	
	$formBtn.on("click",function(){
		$popUp.css("display","block");
	});
	
	$popUp.on("click",function(e){
		if(!$form.is(e.target)&&$form.has(e.target).length==0)
			$popUp.css("display","none");
	});
});