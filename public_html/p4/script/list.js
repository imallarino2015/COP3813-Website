$(function() {
	//Initialization
	var startTime=0;
	var $this;

	$('#showForm').show();
	$('#modifyList').hide();
	
	//load the list
	function load(taskType){
		if(localStorage.getItem(taskType)!==null){
			var list=JSON.parse(localStorage.getItem(taskType));
			
			$('ul[class='+taskType+']').empty();
		
			for(var a=0;a<list.length;a++)
				$('ul[class='+taskType+']').append('<li>'+list[a]+'</li>');
		}
	}

	//Commonly Used Actions
	function recount(){	//recount all incomplete tasks
		var count=$('ul[class!=complete]').children().length;
		$('#count').text(count);
	}
	
	function save(){	//save to the local machine
		var task=[];
		$('ul[class=important] li').each(function(){task.push($(this).text())});
		localStorage.setItem('important',JSON.stringify(task));
		task.length=0;
		$('ul[class=normal] li').each(function(){task.push($(this).text())});
		localStorage.setItem('normal',JSON.stringify(task));
		task.length=0;
		$('ul[class=complete] li').each(function(){task.push($(this).text())});
		localStorage.setItem('complete',JSON.stringify(task));
	}
	
	load('important');
	load('normal');
	load('complete');
	recount();
	
	//Event Handlers
	$('#showForm').on('click', function(){
		$('#showForm').hide();
		$('#adit').val('add');
		$('#modifyList').show();
	});

	$('#modifyList').on('submit', function(e) {
		var text=$('input:text').val();           // Get value of text input
		
		if($('input:checkbox').prop('checked'))
			$('ul[class="important"]').append('<li>'+text+'</li>');	// Add important task
		else
			$('ul[class="normal"]').append('<li>'+text+'</li>');	// Add normal task
		$('input:text').val('');                    // Empty the text input
		if($('#adit').val()=='edit')
			$this.remove();
		
		recount();
		save();
		$('#showForm').show();
		$('#modifyList').hide();
	});
	
	$('ul').on('mousedown', 'li', function(e) {
		startTime=e.timeStamp;
	});
	
	$('ul').on('mouseup','li',function(e){
		$this=$(this);
		var task=$this.text();
		
		if(e.timeStamp-startTime>500){	//on long press
			if(!$this.parents().hasClass('complete')){	//mark as complete
				$('ul[class="complete"]').append('<li>'+task+'</li>');   // Add back to end of list as complete
			}
			$this.remove();
		}else{	//on short press
			if($this.parents().hasClass('complete')){	//mark as incomplete
				$this.remove();
				$('ul[class="normal"]').append('<li>'+task+'</li>');
			}else{	//edit
				$('#showForm').hide();
				$('#adit').val('edit');
				$('#modifyList').show();
				$('input:text').val(task);
				$('input:checkbox').prop('checked',$this.parents().hasClass('important'));
			}
		}
		
      recount();
      save();
	});
});
