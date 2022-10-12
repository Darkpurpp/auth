<?php echo $table;?>
<script type="text/javascript">
$( document ).ready( function() {
	
	function toDel() {
		$('.btn-danger').on( 'click', function() {

			let btn = $(this); 

			var tr = btn.parent().parent()

			var id = tr.attr('id');

			$.ajax({
			  method: "POST",
			  url: "index.php?r=auth/delete",
			  data: { 'id': id },
			  success: function(res) {

			  	if( res == 1 )
			  	{
			  		tr.remove();
			  	}

			  }
			});

		});
	}

	$('.logout').on( 'click', function() {
		window.location = window.location.origin + window.location.pathname + '?r=auth/logout'; 
	});

	toDel();

	$('.btn-primary').on( 'click', function() {

		let btn = $(this); 

		var tr = btn.parent().parent()

		$.ajax({
		  method: "POST",
		  url: "index.php?r=auth/add",
		  data: { 'name': tr.children().eq(0).children().eq(0).val(), 'email': tr.children().eq(1).children().eq(0).val(), 'password': tr.children().eq(2).children().eq(0).val() },
		  success: function(res) {

		  	$('input').val('');

		  	if( res != '' )
		  	{
		  		tr.before( res );

		  		toDel();
		  	}

		  }
		});

	});
})
</script>