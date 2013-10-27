jQuery(document).ready(function($) {

	/***************************************
		ajax like shot feature
	***************************************/
	$(".like").stop().click(function(){

		var rel = $(this).attr("rel");

		var data = {
			data: rel,
			action: 'like_callback'
		}
		$.ajax({
			action: "like_callback",
			type: "GET",
			dataType: "json",
			url: ajaxurl,

			data: data,
			success: function(data){

				console.log(data.likes);
				console.log(data.status);

				if(data.status == true){
					$(".like[rel="+rel+"]").html(data.likes + " likes").addClass("liked");
				}else{
					$(".like[rel="+rel+"]").html(data.likes + " likes").removeClass("liked");
				}

			}
		});

	});

});