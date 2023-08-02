// STICKY JS STARTS
     $(window).on('scroll', function() {
    if ($(window).scrollTop() > 200) {
      $('#navbar').addClass('sticky');
    } else {
      $('#navbar').removeClass('sticky');
    }
  });

// STICKY JS ENDS


// HEADER FOOTER CALL FUNCTION STARTS
  $(function(){
    $("#header").load("header.html");
    $("#footer").load("footer.html");
  });
  $(function(){
    $("#header2").load("header2.html");
  });
// HEADER FOOTER CALL FUNCTION ENDS


// SCROLL TO TOP JS STARTS
  $(document).ready(function(){ 
    $(window).scroll(function(){ 
      if ($(this).scrollTop() > 100) { 
        $('#scroll').fadeIn(); 
      } 
      else{ 
        $('#scroll').fadeOut(); 
      } 
    }); 
    $('#scroll').click(function(){ 
    $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
    });   
  });
// SCROLL TO TOP JS ENDS


// SEARCH JS STARTS
    
    $('#searchIcon').on('click', function () {
        $('.search-form').toggleClass('active');
    });
    $('.closeIcon').on('click', function () {
        $('.search-form').removeClass('active');
    });

// SEARCH JS ENDS

$('#new_products').owlCarousel({
    // autoplay: true,
    smartSpeed: 900,
    // loop: true,
    margin: 20,
    nav: true,
    center:false,
    autoplay:true,
    autoplayHoverPause:true,
    navText: ['<img src="assets/frontend/images/left.png">','<img src="assets/frontend/images/right.png">'],
    dots: false,
    responsive:{
        0:{
            items:2,
            nav: false
        },
        575:{
            items:2,
            nav: false
        },
        768:{
            items:3,
            nav: false
        },
        992:{
            items:4
        },
        1200:{
            items:6
        }
    }
});

$('#new_products_related').owlCarousel({
    // autoplay: true,
    smartSpeed: 900,
    // loop: true,
    margin: 20,
    nav: true,
    center:false,
    autoplay:true,
    autoplayHoverPause:true,
    navText: ['<img src="../assets/frontend/images/left.png">','<img src="../assets/frontend/images/right.png">'],
    dots: false,
    responsive:{
        0:{
            items:2,
            nav: false
        },
        575:{
            items:2,
            nav: false
        },
        768:{
            items:3,
            nav: false
        },
        992:{
            items:4
        },
        1200:{
            items:5
        }
    }
});


$('#ecatalogs').owlCarousel({
    smartSpeed: 900,
    loop: true,
    margin: 20,
    nav: true,
    center:false,
    autoplay:true,
    autoplayHoverPause:true,
    navText: ['<img src="assets/frontend/images/left.png">','<img src="assets/frontend/images/right.png">'],
    dots: true,
    responsive:{
        0:{
            items:2,
            nav: false
        },
        575:{
            items:2,
            nav: false
        },
        768:{
            items:3,
            nav: false
        },
        992:{
            items:3
        },
        1200:{
            items:3
        }
    }
});


$('#trending_products').owlCarousel({
    // autoplay: true,
    smartSpeed: 900,
    // loop: true,
    margin: 20,
    nav: true,
    center:false,
    autoplay:true,
    autoplayHoverPause:true,
    navText: ['<img src="assets/frontend/images/left.png">','<img src="assets/frontend/images/right.png">'],
    dots: false,
    responsive:{
        0:{
            items:2,
            nav: false
        },
        575:{
            items:2,
            nav: false
        },
        768:{
            items:3,
            nav: false
        },
        992:{
            items:4
        },
        1200:{
            items:6
        }
    }
});


// SLIDER 1 JS STARTS
    $('#slider_app').owlCarousel({
        autoplay: true,
        smartSpeed: 900,
        loop: true,
        margin: 0,
        nav: true,
        dots: false,
        center: true,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1,
                nav: false
            },
            575:{
                items:1,
                nav: false
            },
            768:{
                items:1,
                nav: false
            },
            992:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });
// SLIDER 1 JS ENDS


     $(function(){
 
    $('#message').keyup(function()
    {
        var yourInput = $(this).val();
        re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if(isSplChar)
        {
            var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl_char);
        }
    });
 
});



// SLIDER 1 JS STARTS
    $('#slider1').owlCarousel({
        autoplay: true,
        smartSpeed: 900,
        loop: true,
        margin: 0,
        nav: false,
        dots: true,
        center: true,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1,
                nav: false
            },
            575:{
                items:1,
                nav: false
            },
            768:{
                items:1,
                nav: false
            },
            992:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });
// SLIDER 1 JS ENDS

function showDiv5(){   
jQuery(".hide_content").show("slow");
jQuery(".show_con").hide("hide");
}


function ShowPincodepopup() {
  document.getElementById("delivery_detail").style.display = "block";
  document.getElementById("overlay").style.display = "block";
  $('body').css('overflow', 'hidden');
  
}

function HidePincodepopup() {
  document.getElementById("delivery_detail").style.display = "none";
  document.getElementById("overlay").style.display = "none";
  $('body').css('overflow', 'auto');
  
}

function SelectMale() {
     $('.male-gender').addClass( 'active_gender' );
     $('.female-gender').removeClass( 'active_gender' );
     document.getElementById("selectgender").value = "Male";
  
}

function SelectFemale() {
     $('.male-gender').removeClass( 'active_gender' );
     $('.female-gender').addClass( 'active_gender' );
     document.getElementById("selectgender").value = "Female";
}

$('input#q').keyup( function() {
   if( this.value.length == 6 ) {

   $('#output').val(this.value); 

   }

});



















































































































