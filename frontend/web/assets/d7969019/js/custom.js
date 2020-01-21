$(document).ready(function(){
    $('.single-item').slick({
        autoplay:true,
     fade: true,
     dots: false,
      slidesToShow: 1,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: true,
            dots: true,
            slidesToShow: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
              dots: false,
            slidesToShow: 1
          }
        }
      ]
    });
    
    
    $('.Premium_ad_slider').slick({
        autoplay:true,
        arrows: true,
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: true,
            dots: true
          }
        },
          {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
    
    
    $('.detail_ad_slider').slick({
        autoplay:true,
        arrows: true,
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            dots: false
          }
        },
    
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });
    
    
    $('.membership_slider').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      arrows: false,
    });
    
    // product slider //
    
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      arrows: false,
      dots: false,
      centerMode: true,
      focusOnSelect: true
    });

    $("a").on('click', function(event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top
            }, 800, function(){

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        } // End if
    });
	
});


function signupValidate(formId,baseUrl){ 
	error=true;
 	form=$('#'+formId).serializeArray();
 	var blankGroup=[];
 	var i=0;
	$.each($('#'+formId).serializeArray(), function() {
	var name=this.name
	var value=this.value;
	var name=name.split('[');
	if(typeof name[1] !== 'undefined'){
		name=(name[1].slice(0,-1));
		if(value==''){
			blankGroup[i]=name;
			$('.field-'+formId+'-'+name).addClass('has-error required');
			$('#'+formId+'-'+name).attr('aria-invalid',true);
			$('#'+formId+'-'+name).next('.help-block').addClass('error').text('This field cannot be blank.');
			i++;		
		}else{
			$('.field-'+formId+'-'+name).removeClass('has-error required');
			$('#'+formId+'-'+name).removeAttr('aria-invalid');
			$('#'+formId+'-'+name).next('.help-block').removeClass('error').text('');	
			
			if(name=='mobile'){
				if(!phonenumber(value)){
					blankGroup[i]=name;
				$('.field-'+formId+'-'+name).addClass('has-error required');
				$('#'+formId+'-'+name).attr('aria-invalid',true);
				$('#'+formId+'-'+name).next('.help-block').addClass('error').text('Please enter 10 digits number!');					
				i++;
				}
			}
		}
	}

});


	return false;



 }
 
 function phonenumber(inputtxt)
{
  var phoneno = /^\d{10}$/;
  if((inputtxt.match(phoneno))) {
      return true;
  }
  else {
	  return false;
  }
}

function addCustomFieldValidation(){
    $( ".requiredVal" ).each(function( index ) {
        formid='post-ad';
        var getid= $( this ).attr('id');
        var str=getid.split('-');
        var getId=str[2];

        console.log(getid);
      //  var label=$('label[for='+getid+']').text();

        $('#'+formid).yiiActiveForm('add', {
            id: 'postad-custom_field-'+getId,
            name: 'postad-custom_field-'+getId,
            container: '.field-postad-custom_field-'+getId, //or your class container
            input: '#postad-custom_field-'+getId,
            error: '.help-block',  //or your class error
            validate:  function (attribute, value, messages, deferred, $form) {
                var label=$('#'+attribute.id).data('label');
                yii.validation.required(value, messages, {message: label+" is required."});
            }
        });
    });

    /*
     */
}






















