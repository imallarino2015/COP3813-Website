$(function(){
	$('#selection').on('change',function(){	//preview the image
		var image=new FileReader();
		image.onload=function(e){
			$('#preview').attr('src',e.target.result);
		}
		image.readAsDataURL(this.files[0]);
	});
	
	$('#caption').on('keyup',function(){	//update character count
		$('#count').text(140-$('#caption').val().length);
	});
});