$(function() {

$(document).ready(function () {
       (function ($) {
         
       })(jQuery);
   });
   	
   	
   if(jQuery('#tg-dbcategoriesslider').length > 0){
   
       var _tg_postsslider = jQuery('#tg-dbcategoriesslider');
       _tg_postsslider.owlCarousel({
           items : 4,
           nav: true,
       
           loop: false,
           dots: false,
           autoplay: false,
           dotsClass: 'tg-sliderdots',
           navClass: ['tg-prev', 'tg-next'],
           navContainerClass: 'tg-slidernav',
           navText: ['<span class="icon-chevron-left"></span>', '<span class="icon-chevron-right"></span>'],
           responsive:{
               0:{ items:2, },
               640:{ items:3, },
               768:{ items:4, },
           }
       });
   }
   
   
   $(document).on('click', '.tg-categoryholder', function() {
   
   $('.ch_category').html('Edit Category');
   $('#sub-category-text').text('--');
   	var cat_id = $(this).attr('attr-id');
   	
   	var cat_name = $(this).attr('attr-name');
   	
   	$('#change-category-btn').css('display', 'block');
   	$('#main-category-text').text(cat_name);
   
   	 $('.tg-categoryholder').not(this).removeClass('intro');
     $(this).addClass('intro');
   	
   	$('#sub-category-loader').css('visibility', 'visible');
   	
   	 $.ajax({
              url: '../get-category',
              type: 'POST',
               data: {cat_id: cat_id}, 
               success: function(categories) {
   	 if(categories) {
   		setTimeout(function() {
   		
   		$('.cat_div').html(categories);
   		}, 1000);
   	 }
               }
           });
   });
   
   $(document).on('click', '.sub-cat-title', function() {
   var cat_id = $('.intro').attr('attr-id');
   
   var sub_cat_id = $(this).attr('sub_cat_id');
   var sub_cat_title = $(this).text();
   /* category Custom Field */
   $.ajax({
    url: '../get-category-custom-field',
    type: 'POST',
     data: {cat_id: sub_cat_id}, 
     success: function(customfield) {
   $('.custom_fields').html(customfield);
     }
    });
   
   
   $('.sub-cat-title').not(this).removeClass('sub_title_color');
   $(this).addClass('sub_title_color');
   
   $('#sub-category-text').text(sub_cat_title);
   $('#selectCategory').modal('hide');
   
   var elems = [];
   elems.push(cat_id);
   elems.push(sub_cat_id);
   	
     $('#cat_id').val(elems);
   
   });
   
   $('#submit_btn').click(function(e) { 
   
    var cats = $('#cat_id').val();
    var title = $('#postad-title').val();
    var description = $('#postad-description').val();
    var city = $('#postad-city').val();
    var state = $('#postad-state').val();
    var country = $('#postad-country').val();
    
   
   
    if(cats==''){
     $('.error_msg_cat').text('Please Select category');
     $('.error_msg_title').text('');
     $('.error_msg_description').text('');
     $('.error_msg_city').text('');
     $('.error_msg_state').text('');
     $('.error_msg_country').text('');
     
     
   $('html, body').animate({
   	scrollTop: $('#select_cat').offset().top
   }, 1000);
     
     
    return false;
    }else if(title==''){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('Please Enter title for advertise');
     $('.error_msg_description').text('');
     $('.error_msg_city').text('');
     $('.error_msg_state').text('');
     $('.error_msg_country').text('');
   $('html, body').animate({
   	scrollTop: $('#select_title').offset().top
   }, 1000);
     
     return false;
    }else if(description==''){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_description').text('Please Enter Description');
     $('.error_msg_city').text('');
     $('.error_msg_state').text('');
     $('.error_msg_country').text('');
   $('html, body').animate({
   	scrollTop: $('#select_description').offset().top
   }, 1000);
     
     return false;
    }else if(country==''){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_description').text('');
     $('.error_msg_city').text('');
     $('.error_msg_state').text('');
     $('.error_msg_country').text('Please Select Country');
   $('html, body').animate({
   	scrollTop: $('#select_country').offset().top
   }, 1000);
     
     return false;
    }else if(state=='0'){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_description').text('');
     $('.error_msg_city').text('');
     $('.error_msg_state').text('Please Select State');
     $('.error_msg_country').text('');
   $('html, body').animate({
   	scrollTop: $('#select_state').offset().top
   }, 1000);
     
     return false;
    } else if(city=='0'){
     $('.error_msg_cat').text('');
     $('.error_msg_title').text('');
     $('.error_msg_description').text('');
     $('.error_msg_city').text('Please Select City');
     $('.error_msg_state').text('');
     $('.error_msg_country').text('');
   $('html, body').animate({
   	scrollTop: $('#select_city').offset().top
   }, 1000);
     
     return false;
    }else{
    }
   });
   
   $('select[id="postad-country"]').on('change', function () {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url:  '../country_change/' + countryId,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    $('select[id="postad-state"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="PostAd[state]"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            //$('select[name="city"]').empty();
        }
    });
    
    
    
    
    $('select[id="postad-state"]').on('change', function () {
        var stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url:  '../state_change/' + stateId,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    $('select[id="postad-city"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="PostAd[city]"]').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            //$('select[name="city"]').empty();
        }
    });
   
});





