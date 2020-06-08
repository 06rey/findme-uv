(function(){

	var app = new Vue({

		el: '#app',
		/*
	  |--------------------------------------------------------------------------
	  | MOUNTED
	  |--------------------------------------------------------------------------
	  */
	 	mounted: function(){

	 		_allRouteTripCallback = function(data){
	 			$.each(data, function(){
	 				$(`#${this.route_id}`).children('.tripCount').text(this.count.pending);
	 			})
	 		};

	 		let app = this;

	 		// Search bar
	 		$('#searchRoute').on('input', function(){
	 			let hasResult = false;
	 			$('#routeList').children().each(function(){
	 				let found = false;
	 				$(this).find('.place').each(function(){
	 					if ($(this).text().includes($('#searchRoute').val())){
	 						found = true;
	 						hasResult = true;
	 					}
	 				});
	 				found ? $(this).show() : $(this).hide();
	 			});
	 			hasResult ? $('#noResult').addClass('d-none') : $('#noResult').removeClass('d-none');
	 		});
	 	
	 	}
	});

})();