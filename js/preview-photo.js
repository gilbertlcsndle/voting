$(function(){
	$('#upload-photo').change(function(){
		var preview = document.querySelector('#preview-photo');
		var file 	= document.querySelector('input[type=file]').files[0];
		var reader 	= new FileReader();
		reader.addEventListener('load', function(){
			preview.src = reader.result;
		}, false);
		if(file){
			reader.readAsDataURL(file);
		}
	});
});
