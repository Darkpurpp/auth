<script type="text/javascript">
$( document ).ready(function() {

	function adaptCoordinates( c )
	{
		arr = [];
		for ( var key in c)
		{
			arr[key] = c[key].split(';');

			arr[key][0] = arr[key][0].split(',');

			arr[key][1] = arr[key][1].split(',');
		}

		return arr;

	}

	var config = {"area_email": "535,374;906,405", "area_password": "535,447;906,477", "email": "522,357;915,414", "password":"522,429;917,486","enter": "523,570;916,623","check":"523,507;565,548","forget":"773,518;916,542","apm_enter":"723,255;904,289", "default_enter":"523,255;716,290", "cross0" : "448,805;460,817", "cross1": "448,878;460,890"};

	var config_apm = {"area_email": "536,447;906,477", "area_password": "536,519;906,550", "area_apm":"536,375;907,406", "email": "523,429;915,485", "password":"523,501;915,561", "apm":"523,357;915,417","enter": "524,642;916,695","check":"524,579;565,620","forget":"773,591;914,613","login_enter":"523,256;718,289","default_enter":"723,256;905,290", "cross0" : "884,376;896,394", "cross1": "884,376;896,394", "cross2":"884,376;896,394" };

	config = adaptCoordinates(config);

	config_apm = adaptCoordinates(config_apm);

	function adaptTemplate( apm = '', cnfg = [])
	{
		var version = apm;

		if( $('img').length > 0 )
		{
			$('img').remove();
		}

		if( version == '')
		{
			source = "/o_templates/default.png"
		}
		else
		{
			source = "/o_templates/default_apm.png"
		}

		$('body').append('<img class="default" draggable="false" style="position: absolute; z-index: 1;" src="' + source + '">');

	    var keys = Object.keys(cnfg); 

	    for (var i = 0; i < keys.length; i++) {

	    	if( keys[i].indexOf('cross') >= 0 )
	    	{
	    		continue;
	    	}

	    	x0 = Number(cnfg[keys[i]][0][0]);
	   		y0 = Number(cnfg[keys[i]][0][1]);
	   		x1 = Number(cnfg[keys[i]][1][0]);
	    	y1 = Number(cnfg[keys[i]][1][1]);

	    	if(keys[i].indexOf('area') < 0 )
	    	{
	    		widthOfTemplate = x1 - x0;
	    		heightOfTemplate = y1 - y0;

	    		onMouseOver =  `/c_templates/${keys[i]}-hover${version}.png`;

	    		onMouseOut = `/c_templates/${keys[i]}-default${version}.png`;

	    		if(keys[i].indexOf('_enter') >= 0 && keys[i].indexOf('default') < 0 )
	    		{
	    			param = 'other_enter="1"';
	    		}
	    		else
	    		{
	    			param = '';
	    		}


	    		notBg = 'notbg="1"';

	    		$('body').append(`<img ${param} ${notBg} onmouseout="this.src='${onMouseOut}';" pre_template="${onMouseOut}" onmouseover="this.src='${onMouseOver}'" class="${keys[i]}" style="position: absolute; z-index: 2; width: ${widthOfTemplate}px; height: ${heightOfTemplate}px; top: ${y0}px; left: ${x0}px;" draggable="false" src="/c_templates/${keys[i]}-default${version}.png" >`);

	    		$("." + keys[i]).on("click", function(){
	    			p = $(this).attr('class');
	    			this.src = `/c_templates/${p}-mousedown${version}.png`;

	    			if( p == 'default_enter' )
	    			{
	    				s = this;

	    				setTimeout( function(){
				    		s.src = `/c_templates/${p}-default${version}.png`;;
				    	}, 100);
	    			}

	    			if(p.indexOf('check') >= 0)
	    			{
	    				let sleepAndChange = setTimeout( function(){
	    					if( $('.' + p ).attr('pre_template') != `/c_templates/${p}-active_hover${version}.png` )
	    					{
	    						$('.' + p).attr('src', `/c_templates/${p}-active_hover${version}.png` );
	    						$('.' + p).attr('pre_template', `/c_templates/${p}-active_hover${version}.png` );
	    						$('.' + p).attr('onmouseout', `this.src='/c_templates/${p}-active_hover${version}.png'`);
	    						$('.' + p).attr('onmouseover', `this.src='/c_templates/${p}-active_hover${version}.png'`);
	    					}
	    					else
	    					{
	    						$('.' + p).attr('src', `/c_templates/${p}-default${version}.png` );
	    						$('.' + p).attr('pre_template', `/c_templates/${p}-default${version}.png` );
	    						$('.' + p).attr('onmouseout', `this.src='/c_templates/${p}-default${version}.png'`);
	    						$('.' + p).attr('onmouseover', `this.src='/c_templates/${p}-hover${version}.png'`);
	    					}
	    					
	    				}, 100);


	    			}
	    			else if(['email','password','apm'].indexOf(p) >= 0)
	    			{
	    				var e = window.event;

		    			if( e.offsetX >= 358 && e.offsetX <= 375 && e.offsetY >= 22 && e.offsetY <= 39 )
		    			{
		    				$('#area_' + p ).val('');
		    			}

	    				document.querySelector('#area_' + p ).focus();
	    			}
	    			else if( p == 'enter' )
	    			{
	    				code_number = '';

	    				if( version == '_apm' )
	    				{
	    					code_number = $('#area_apm').val();
	    				}

	    				$.ajax({
						  method: "POST",
						  url: "index.php?r=auth/auth",
						  data: { email: $("#area_email").val(), password: $("#area_password").val(), 'apm': code_number, "version" : version, 'check' : $('.check').attr('src') },
						  success: function(res) {
						  	if( res.indexOf('email') >= 0 ){
						  		src = "/c_templates/email-error" + version + ".png";

						  		$('.email').attr( 'src', src ).attr( 'pre_template', src );
						  	}else{
						  		$('.email').attr( 'src', "/c_templates/email-default" + version + "1.png" ).attr( 'pre_template', "/c_templates/email-default" + version + ".png" );
						  	}	
						  	if( res.indexOf('apm') >= 0 && res != '_apm'){
						  		src = "/c_templates/apm-error" + version + ".png";

						  		$('.apm').attr( 'src', src ).attr('src', src);
						  	}else{
						  		$('.apm').attr( 'src', "/c_templates/apm-default" + version + "1.png" ).attr( 'pre_template', "/c_templates/apm-default" + version + "1.png" );
						  	}
						  	if( res.indexOf('password') >= 0){
						  		src = "/c_templates/password-error" + version + ".png";

						  		$('.password').attr( 'src', src ).attr( 'src', src );
						  	}else{
						  		$('.password').attr( 'src', "/c_templates/password-default" + version + "1.png" ).attr( 'pre_template', "/c_templates/password-default" + version + ".png" ); 
						  	}
						  	if( res.indexOf('_apm') >= 0 ){
						  		window.location = window.location.origin + window.location.pathname + '?r=auth/apm';
						  	}
						  	if( res == 1 ){
						  		window.location = window.location.origin + window.location.pathname + '?r=auth/list';
						  	}
						  }
						})
	    			}
	    		});
	    	}else{
	    		$('body').append(`<input id="${keys[i]}" style="position: absolute;border: none; outline: none; color: #031528;font-size: 16px; font-family: Roboto; top: ${y0}px; left: ${x0}px; z-index: 3">`);
	    	}
	    }

	    function hoverInputs( element )
	    {
	    	if($('.' + element ).length > 0)
	    	{
	    		$('.' + element ).removeAttr('onmouseout').removeAttr('onmouseover');
	    		$('.' + element ).hover(function(){
					if( !$("#area_" + element).is(":focus")){
						$(this).attr( 'src', "/c_templates/" + element + "-hover" + version + ".png" );
						if( $("#area_" + element).val().length > 0 )
				    	{
				    		$('.' + element ).attr( 'src',  "/c_templates/" + element + "-hover" + version + "1.png" );
				    	}	
					}
				},function(){
					if( !$("#area_" + element).is(":focus")){
						$(this).attr( 'src', "/c_templates/" + element + "-default" + version + ".png" );
						if( $("#area_" + element).val().length > 0  && $('.' + element ).attr('pre_template') != "/c_templates/" + element + "-error" + version + ".png")
				    	{
				    		$('.' + element ).attr( 'src',  "/c_templates/" + element + "-default" + version + "1.png" );
				    	}

				    	if( $('.' + element ).attr('pre_template') == "/c_templates/" + element + "-error" + version + ".png" )
				    	{
				    		$('.' + element ).attr('src' , "/c_templates/" + element + "-error" + version + ".png" );
				    	}
					}	
				});

				if( element == 'password' )
				{
					$('#area_password').attr('type', 'password');
				}

				if( element == 'apm' )
				{
					$('#area_apm').on( 'input', function() {
						symbols = $(this).val();
						new_str = symbols;
						if( symbols.length == 3 && symbols[2] != '-')
						{
							new_str = symbols[0] + symbols[1] + '-' + symbols[2];
						}
						else if( new_str.length > 9 )
						{
							new_str = new_str.slice(0,-1);
						}

						new_str = new_str.replace(/[^-\d]/g, '');

						$(this).val( new_str );
					});
				}
	    	}
	    }

	    hoverInputs( 'email' );
	    hoverInputs( 'password' );
	    hoverInputs( 'apm' );

	    $('input').hover(function(){
			if( !$(this).is(":focus")){
				cls = $(this).attr('id').split('_');
				$('.' + cls[1]).attr( 'src', "/c_templates/" + cls[1] + "-hover" + version + ".png" );
				if( $(this).val().length > 0 )
		    	{
		    		$('.' + cls[1] ).attr( 'src',  "/c_templates/" + cls[1] + "-hover" + version + "1.png" );
		    	}
			}
		},function(){
			if( !$(this).is(":focus")){
				cls = $(this).attr('id').split('_');
				$('.' + cls[1]).attr( 'src', "/c_templates/" + cls[1] + "-default" + version + ".png" );
				if( $(this).val().length > 0  && $('.' + cls[1] ).attr('pre_template') != "/c_templates/" + cls[1] + "-error" + version + ".png")
		    	{
		    		$('.' + cls[1] ).attr( 'src',  "/c_templates/" + cls[1] + "-default" + version + "1.png" );
		    	}

		    	if( $('.' + cls[1] ).attr('pre_template') == "/c_templates/" + cls[1] + "-error" + version + ".png" )
		    	{
		    		$('.' + cls[1] ).attr('src' , "/c_templates/" + cls[1] + "-error" + version + ".png" );
		    	}
			}	
		});

	    $('input')
	    .focusin(function(){ 
	    	$(this).css('color', '#031528');
	    	cls = $(this).attr('id').split('_');
	    	$('.' + cls[1] ).attr( 'src', "/c_templates/" + cls[1] + "-mousedown" + version + ".png" );
	    	if( $(this).val().length > 0 )
	    	{
	    		setTimeout( function(){
		    		$('.' + cls[1] ).attr( 'src', "/c_templates/" + cls[1] + "-active_hover" + version + ".png" );
		    	}, 10);
	    	}
	    })
	    .focusout(function(){ 
	    	$(this).css('color', '#748495'); 
	    	cls = $(this).attr('id').split('_');
	    	$('.' + cls[1] ).attr( 'src', $('.' + cls[1] ).attr('pre_template') );
	    	if( $(this).val().length > 0  && $('.' + cls[1] ).attr('pre_template') != "/c_templates/" + cls[1] + "-error" + version + ".png")
	    	{
	    		$('.' + cls[1] ).attr( 'src',  "/c_templates/" + cls[1] + "-default" + version + "1.png" );
	    	}
	    })

	    $('input').on( 'input', function() {
	    	cls = $(this).attr('id').split('_');
	    	if( $(this).val().length > 0 )
	    	{
	    		$('.' + cls[1] ).attr( 'src', "/c_templates/" + cls[1] + "-active_hover" + version + ".png" );
	    	}
	    	else
	    	{
	    		$('.' + cls[1] ).attr( 'src', "/c_templates/" + cls[1] + "-mousedown" + version + ".png" );	
	    	}
	    });

	    $('img[other_enter="1"]').on('click', function(){

	    	email = $('#area_email').val();

	    	password = $('#area_password').val();

	    	check_src = $('.check').attr('src');

	    	$('input').remove();

	    	$('img[notbg="1"]').remove();

	    	if( version == '' )
	    	{
	    		adaptTemplate( '_apm', config_apm );

	    		vrsn = '_apm';
	    	}
	    	else
	    	{
	    		adaptTemplate( '', config );

	    		vrsn = '';
	    	}

	    	if( check_src.indexOf('active_hover') >= 0 )
	    	{
	    		setTimeout( function(){
	    			$('.check').trigger('click');
	    		}, 100);
	    	}

	    	$('#area_password').val(password).css('color', '#748495');

	    	if( password.length > 0 )
	    	{
	    		$('.password').attr( "src", "/c_templates/password-default" + vrsn + "1.png" );
	    	}

	    	$('#area_email').val(email).css('color', '#748495');

	    	if( email.length > 0 )
	    	{
	    		$('.email').attr( "src", "/c_templates/email-default" + vrsn + "1.png" );
	    	}
	    });

	    if( version == '_apm')
	    {
	    	$('.default_enter').hover( function() {
	    		$(this).attr('src', $(this).attr('pre_template'));
	    	})
	    }

	}

	adaptTemplate( '', config );
});
</script>