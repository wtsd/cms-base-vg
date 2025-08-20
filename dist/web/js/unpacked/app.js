/*jslint browser:true */
/*jslint plusplus: true */
/*global $, jQuery, VK, console, alert */

var locations = [], map, markers = [];
function addLocationsToMap(locations, zoomLevel) {
    var marker, i, latCenter = 0, lngCenter = 0;

    for (i = 0; i < locations.length; i++) {
      latCenter += locations[i][1];
      lngCenter += locations[i][2];
    }

    latCenter = (latCenter / locations.length);
    lngCenter = (lngCenter / locations.length);

    map = new google.maps.Map(document.getElementById('map-canvas'), {
          zoom: zoomLevel,
          center: new google.maps.LatLng(latCenter, lngCenter),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    var infowindow = new google.maps.InfoWindow();
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
}

function initializeMap() {
  var zoomLevel = 9;
  addLocationsToMap(locations, zoomLevel);

  
  $('.address-block').on('mouseover', function (e) {
    var id = $(this).attr('data-marker-id');
    var icon = new google.maps.MarkerImage("/web/images/map-icon.png", new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(9, 34));
    markers[id].setIcon(icon);
  });
  $('.address-block').on('mouseout', function (e) {
    var id = $(this).attr('data-marker-id');
    markers[id].setIcon();
  });
}

function doSubscribe(email) {
    $.ajax({
        type: 'post',
        url: '/api/signup/subscribe',
        data: {
            email: email
        },
        success: function (json) {
            if (json.status == 'ok') {
                $('.frmSubs').html(json.msg);
            } else {
                $('.subscriptionEmail').removeAttr('disabled');
                alert(json.msg);
            }
        },
        beforeSend: function () {
            $('.subscriptionEmail').attr('disabled', 'disabled');
        },
        dataType: 'json',
        cache: false,
        error: function () {}
    });
}

function cartGeneralRoutine() {
    // Cart
    $('.cartCount').on('mouseover', function (e) {
      // @todo: Get cart info
      //$('.cart-contents-float').show();
    });
    $('.cart-contents-float').on('mouseout', function (e) {
      $('.cart-contents-float').hide();
    });
    $('input.quantity').on('change', function (e) {
      var $this = $(this),
          offer_id = $this.attr('data-offer'),
          quantity = $this.val(),
          $tr = $this.parents('tr');
      
      $.ajax({
                type : 'post',
                url : '/api/v1/cart/quantity',
                data : {
                    offer_id : offer_id,
                    quantity : quantity
                },
                success : function (json) {
                  if (json.status == 'ok') {
                      //$btn.hide();
                      $tr.children('.price-subtot').text(json.subtot)
                      $('.cartSum').html(json.sum);
                      $('.error').html(json.msg).show();
                      $('.cartCount').html('Корзина ' + json.cartCount + ' (' + json.sum + ' руб.)');
                      //$btn.html('Товар добавлен!');
                  } else {
                      $('.error').hide();
                      $('.notification').html(json.msg).show();
                      $('#fbck_frm').hide();
                  }
                },
                dataType: 'json',
                cache: false,
                error: function () {}
            });
    });
    $('.addToCart').on('click', function (e) {
        var $btn = $(this),
            offer_id = 0,
            $block = $btn.parents('.offer-item, .single-offer'),
            $cart = $('.menu-right');
        
        if ($btn.attr('data-offer-id') > 0) {
            offer_id = $btn.attr('data-offer-id');
        } else {
            offer_id = $('#offer_id').val();
        }
        if ($btn.hasClass('active')) {
            $btn.removeClass('active');
            console.log('REMOVE FROM CART');
        } else {
            //$btn.addClass('active');
            e.preventDefault();

            $.ajax({
                type : 'post',
                url : '/api/v1/cart/add',
                data : {
                    offer_id : offer_id,
                    quantity : 1
                },
                success : function (json) {
                  if (json.status == 'ok') {
                      //$btn.hide();
                      $('.cart-modal').modal();
                      setTimeout(function(){
                          $(".cart-modal").modal('hide');
                      }, 5000);

                      $('.error').html(json.msg).show();
                      $('.cartCount').html('Корзина ' + json.cartCount + ' (' + json.sum + ' руб.)');
                      $('.msg', $block).html('Товар добавлен! <a href="/cart/">Оформить заказ</a>.').show();
                      //$btn.html('Товар добавлен!');
                      setTimeout(function () { $('.msg', $block).hide('slide'); }, 5000);
                      // @todo: Fast order question
                  } else {
                      $('.error').hide();
                      $('.notification').html(json.msg).show();
                      $('#fbck_frm').hide();
                  }
                },
                dataType: 'json',
                cache: false,
                error: function () {}
            });

            //console.log('ADD TO CART');
        }
    });
    $('.clearCart').on('click', function (e) {
        var $btn = $(this);

        $btn.addClass('active');
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '/api/v1/cart/clear',
            success : function (json) {
              $('.offersCount').html('0');
              $('.offersSum').html('0');
              $('.cart-offers').html('');
              if (json.status == 'ok') {
                  $('.error').html(json.msg).show();
                  $('.cartCount').html('Корзина пуста');
              } else {
                  $('.error').hide();
                  $('.notification').html(json.msg).show();
                  $('#fbck_frm').hide();
              }
            },
            dataType: 'json',
            cache: false,
            error: function () {}
        });
    });
    
    $('.removeFromCart').on('click', function (e) {
        var id = $(this).attr('data-offer');
        e.preventDefault();
        $.ajax({
          type: 'post',
          url: '/api/v1/cart/remove',
          data: {
            offer_id: id
          },
          success: function (json) {
            $('.offer_' + id).remove();
              if (json.status == 'ok') {
                  $('.error').html(json.msg).show();
                  $('.cartCount').html(json.cartCount);
              } else {
                  $('.error').hide();
                  $('.notification').html(json.msg).show();
                  $('#fbck_frm').hide();
              }
          },
          dataType: 'json',
          cache: false,
          error: function () {}
        });
    });

    $('.quantity-change').on('click', function (e) {
      e.preventDefault();
      var $btn = $(this),
          offer_id = $btn.attr('data-offer'),
          $input = $('input.quantity[data-offer="'+offer_id+'"]'),
          q = $input.val();

        if ($btn.attr('data-action') == 'sub') {
          if (q > 1) {
            $input.val(parseInt(q) - 1);
          }
        }
        if ($btn.attr('data-action') == 'add') {
          $input.val(parseInt(q) + 1);
        }

        
        var $tr = $btn.parents('tr'),
            quantity = $input.val();
        
        $.ajax({
                  type : 'post',
                  url : '/api/v1/cart/quantity',
                  data : {
                      offer_id : offer_id,
                      quantity : quantity
                  },
                  success : function (json) {
                    if (json.status == 'ok') {
                        //$btn.hide();
                        $tr.children('.price-subtot').text(json.subtot)
                        $('.cartSum').html(json.sum);
                        $('.error').html(json.msg).show();
                        $('.cartCount').html('Корзина ' + json.cartCount + ' (' + json.sum + ' руб.)');
                        //$btn.html('Товар добавлен!');
                    } else {
                        $('.error').hide();
                        $('.notification').html(json.msg).show();
                        $('#fbck_frm').hide();
                    }
                  },
                  dataType: 'json',
                  cache: false,
                  error: function () {}
              });
    });

}

function startOfferRoutine() {
  $('.single-offer .previews .thumb').on('click', function (e) {
    e.preventDefault();
    var src = $(this).attr('src');
    $('.img img.main').attr('src', src);
  });

  var url = document.location.toString();
  if (url.match('#')) {
      $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
  } 

  // Change hash for page-reload
  $('.nav-tabs a').on('shown', function (e) {
      window.location.hash = e.target.hash;
  });
}

$(document).ready(function(){
  'use strict';

    $('.subscriptionEmail').on('keypress', function (e) {
        if (e.keyCode == 13) {
            var email = $(this).val();
            doSubscribe(email)
        }
    });
    $('.doSubscribe').on('click', function (e) {
        e.preventDefault();
        var email = $('.subscriptionEmail').val();
        doSubscribe(email);
    });

  $("a[href='#top']").hide();
  $(window).bind('scroll', function(){
    if($(this).scrollTop() > 200) {
      $("a[href='#top']").show();
    } else {
      $("a[href='#top']").hide();
    }
  });

  $("a[href='#top']").click(function() {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });

  //$('.dropdown-toggle').dropdown()
  

    /*var $masonryContainer = $('.images, .galleries');
      var $grid = $masonryContainer.masonry({
        itemSelector: '.image',
        //columnWidth: 140
        columnWidth: function( containerWidth ) {
            var w = $masonryContainer.width();
            return (w / 4);
            //return containerWidth / 4;
          }()
    })

    $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
    });*/


  $('.images').photobox('a',{ time:0 });

  //$('.lightbox').photobox('a',{ time:0 });

  cartGeneralRoutine();

  /*
    locations = [
      ['м. Садовая/Сенная, ул. Садовая, 35', 59.9279595, 30.3201029, 1],
      ['м. Петроградская, ул. Профессора Попова, 23', 59.9279595, 30.3201029, 2]
     ];
  */

  if ($('#map-canvas').length > 0) {
    initializeMap();
  }

  $('#pull-menu').on('click', function(e) {  
    e.preventDefault();  
    var $menu = $(this).siblings('ul');
    console.log($menu);
    $menu.slideToggle();  
  });  

  $('.req-callback').on('click', function (e) {
    e.preventDefault();
    var $form = $('.callback');
    $form.modal('show');
  });
  
  $('pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });

  //$('.lightbox').photobox('a',{ time:0 });

  $(".frm_feedback").on("submit", function(e) {
      e.preventDefault();

      var $frm = $(this),
          $notifpanel = $('.notification'),
          formData = {
              token : $('#token').val(),
              name : $('#name').val(),
              email : $('#email').val(),
              msg : $('#msg').val(),
          };

      $.ajax({
          type: 'post',
          url: '/api/v1/feedback/send/', 
          data: formData, 
          success: function (json) {
              alert(json.msg);
              $notifpanel.html(json.msg).show();
              if (json.status == 'ok') {
                  $frm.hide();
              } else {
                  $('.error').hide();
              }
          },
          dataType: 'json',
          cache: false,
      });
      
  });


  $('.comment-frm').on('submit', function (e) {
    e.preventDefault();
    var $frm = $(this),
        $comments = $('.comments'),
        $newCmnt = $('<div></div>').addClass('comment'),
        comment = $('textarea[name="comment"]', $frm).val(),
        name = $('input[name="name"]', $frm).val(),
        fid = $(this).data('fid'),
        type = $(this).data('type'),
        data = $('input, textarea', $frm).serializeArray(),
        values = {};

        data.forEach(function (item) { 
          values[item.name] = item.value;
        });

    $.ajax({
      method: 'post',
      url: '/api/comment/add/',
      data: values,
      dataType: 'json',
      success: function (json) {
        if (json.status == 'ok') {
          $('textarea[name="comment"]', $frm).val('');
          $('input[name="name"]', $frm).val('');

          $('.comments-container').html(json.html);
          if (json.count == 1) {
            $('.comments-count').html('('+json.count+')');
          } else {
            $('.comments-count').html(json.count);
          }
          commentsRoutine();
          grecaptcha.reset();
        } else {
          alert(json.msg);
          grecaptcha.reset();
        }
      }
    });

  });

    $('.affix-el').affix()

  commentsRoutine();

});

function commentsRoutine()
{

  $('.comments-container .pagination a').on('click', function (e) {
    e.preventDefault();
    var $btn = $(this),
        $cmnts = $('.comments-container'),
        page = $btn.data('page'),
        type = $('.comments').data('type'),
        fid = $('.comments').data('fid'),
        values = {type: type, page: page, fid: fid};

    $.ajax({
      method: 'post',
      url: '/api/comment/get/',
      data: values,
      dataType: 'json',
      success: function (json) {
        if (json.status == 'ok') {
          $cmnts.html(json.html);
          commentsRoutine();
        } else {
          alert(json.msg);
        }
      },
    });


  });
}


$(window).scroll(function(event) {
  
  $('.scroll-vis').each(function(i, el) {
    var el = $(el);
    if (el.visible(true)) {
      el.addClass('anim'); 
    } 
  });
  
});

