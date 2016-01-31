;(function( $, window, undefined )
{
  $.fn.wp_ajax = {
    el:'',
    action:'',
    pre_callback:'',
    target:'',
    form_msg:'',
    init:function( el )
    {
      if( el[0] != undefined )
      {
        this.el           = el;
        this.action       = this.el.data('action');
        this.pre_callback = this.el.data('pre-callback');
        this.target       = this.el.data('target');
        this.form_msg     = this.el.find('[data-form-msg]');

        $.fn.form_btn.init( this.el.find('button[type="submit"]') )
      }
    },
    make_request: function( instance )
    {
      $.fn.form_btn.disable();
      $.fn.overlay.show_overlay();

      var formData = this.el.serializeArray(),
          data = {}

      $.each(formData, function()
      {
        if( data[this.name] !== undefined )
        {
          if( !data[this.name].push )
          {
            data[this.name] = [ data[this.name] ];
          }
          data[this.name].push( this.value || '' );
        }
        else
        {
          data[this.name] = this.value || '';
        }
      });

      data.action  = this.action;
      data.referer = data._wp_http_referer

      if( this.pre_callback != '' && $.fn.callback_bank.filters.hasOwnProperty( this.pre_callback ) )
        data = $.fn.callback_bank.filters[this.pre_callback]( data );

      $.post( ajax_url, data, function( response )
      {
        setTimeout( $.fn.form_btn.enable(), 1000 )
        setTimeout( $.fn.overlay.hide_overlay(), 1000 );

        var new_data = $.parseJSON( response );

        if( instance != undefined )
          new_data.form_msg = instance.el.find('[data-form-msg]')

        if( $.fn.callback_bank.callbacks.hasOwnProperty( new_data.callback ) )
          $.fn.callback_bank.callbacks[new_data.callback]( new_data, instance );

        typeof after_request == 'function' ? after_request( data, new_data, instance ) : '' ;
      });
    }
  }

  $.fn.wp_get = {
    action:'',
    init:function( action )
    {
      if( action != null )
        this.action = action;
    },
    make_request: function( before, after_request, event_data  )
    {
      data          = {};
      data.action   = this.action;

      if( typeof before == 'function' )
        data = before( data, event_data )

      $.post( ajax_url, data, function( response )
      {
        typeof after_request == 'function' ? after_request( event_data, $.parseJSON( response ) ) : '' ;
      });
    }
  }

  $.fn.form_btn = {
    el:'',
    init:function( el )
    {
      this.el = el;
    },
    disable:function()
    {
      this.el.attr('disabled', true );
    },
    enable:function()
    {
      this.el.removeAttr('disabled');
    }
  }

  $.fn.form_msg = {
    el:'',
    init:function( el )
    {
      this.el = el;
      this.el.html('');
    },
    add_msg:function( msg, status )
    {
      status ? klass = 'success' : klass = 'error' ;
      this.el.html( msg ).addClass( 'active ' + klass );
    },
    remove_msg:function()
    {
      this.el.html('').removeClass();
    }
  }

  $.fn.maps = {
    el:'',
    map:'',
    lat:null,
    lng:null,
    zoom:null,
    update_lat:'',
    update_lng:'',
    defaults:{
      center:{ lat: 32.7833, lng: -79.9333 },
      zoom:14
    },
    options:{},
    directions_service:'',
    directions_display:'',
    init:function( el, options )
    {
      this.el   = el;
      this.lat  = this.el.data('lat')  != '' ? this.el.data('lat')  : null ;
      this.lng  = this.el.data('lng')  != '' ? this.el.data('lng')  : null ;
      this.zoom = this.el.data('zoom') != '' ? this.el.data('zoom') : 14   ;

      if( this.lat != null )
        this.defaults.center.lat = this.lat;

      if( this.lng != null )
        this.defaults.center.lng = this.lng;

      if( this.zoom != null )
        this.defaults.zoom = this.zoom;

      this.options = $.extend( {}, this.defaults, options )
      this.map     = new google.maps.Map( el[0], this.options );

      this.set_marker( this.el.data('drag') ? true : false );
      this.update_lat = this.el.closest('form').find('[data-update-lat]')
      this.update_lng = this.el.closest('form').find('[data-update-lng]')

      this.directions_service = new google.maps.DirectionsService;
      this.directions_display = new google.maps.DirectionsRenderer;

      $(document).on( 'set_directions', this, function( e, data )
      {
        e.data.set_route( e, data )
      })

    },
    set_marker:function( draggable )
    {
      var marker = new google.maps.Marker({
        position: this.options.center,
        map: this.map,
        draggable:draggable
      });

      google.maps.event.addListener( marker, 'dragend', function( event )
      {
        $.fn.maps.update_lat.val( this.getPosition().lat().toFixed(8) );
        $.fn.maps.update_lng.val( this.getPosition().lng().toFixed(8) );
      });
    },
    refresh_map:function( data )
    {
      this.options.center.lat = data.lat
      this.options.center.lng = data.lng
      this.init( this.el, this.options )
    },
    set_route:function( e, directions  )
    {
      var target    = directions.target,
          form_data = directions.el.serializeArray(),
          data = {}

      $.each( form_data, function()
      {
        if( data[this.name] !== undefined )
        {
          if( !data[this.name].push )
            data[this.name] = [ data[this.name] ];

          data[this.name].push( this.value || '' );
        }
        else
        {
          data[this.name] = this.value || '';
        }
      });

      e.data.directions_service.route({
        origin: data.start,
        destination: data.end,
        travelMode: google.maps.TravelMode.DRIVING
      },
      function( response, status )
      {
        if (status === google.maps.DirectionsStatus.OK)
        {
          e.data.directions_display.setDirections(response);
        }
        else
        {
          window.alert('Directions request failed due to ' + status);
        }
      });

      e.data.directions_display.setPanel( directions.target[0] );
      e.data.directions_display.setMap( e.data.map );
    }
  }

  $.fn.directions = {
    el:'',
    target:'',
    init:function( el, target )
    {
      this.el = el;

      if( this.el.data('updateable-content') )
        this.target = $('['+ this.el.data('updateable-content') +']')

      this.el.submit( this, function( e )
      {
        e.preventDefault();

        $(document).trigger( 'set_directions', e.data )
      })
    }
  }

  $.fn.overlay = {
    el:'',
    parent:'',
    init:function( el, parent )
    {
      this.el     = el
      this.parent = parent
    },
    show_overlay:function()
    {
      this.parent.addClass('active')
    },
    hide_overlay:function()
    {
      this.parent.removeClass('active')
    }
  }

  $.fn.cc = {
    el:'',
    parent:'',
    type:'',
    init:function( el, parent, type )
    {
      this.el     = el;
      this.parent = parent;
      this.type   = type;

      this.el.validateCreditCard( function( result )
      {
        $.fn.cc.set_card_type( result.card_type )
      });
    },
    set_card_type:function( data )
    {
      if( data != null )
        this.type.html('<i class="fa fa-fw fa-cc-' + data.name + '"></i>')
      else if( data != null && this.el.val() !== null )
        this.type.html('<i class="fa fa-fw fa-spinner fa-spin" style="font-size:28px; position:relative; top:5px;"></i>')
      else
        this.type.html('')
    }
  }

  $.fn.tabs = {
    tabs:'',
    triggers:'',
    init:function( tabs, triggers )
    {
      this.tabs     = tabs;
      this.triggers = triggers;

      this.triggers.on( 'click', 'a', this, function( e )
      {
        e.data.show_tab( $(this) )
      })
    },
    show_tab:function( el )
    {
      this.hide_all();

      el.addClass('active');

      var id = el.data('tab-trigger');

      this.tabs.find('[data-tab="' + id + '"]').addClass('active');
    },
    hide_all:function()
    {
      this.triggers.find('[data-tab-trigger]').each(function()
      {
        $(this).removeClass('active');
      })

      this.tabs.find('[data-tab]').each(function()
      {
        $(this).removeClass('active');
      })

    }
  }

  $.fn.accordion = {
    el:'',
    trigger:'',
    target:'',
    callback:'',
    is_open:false,
    init:function( trigger )
    {
      this.el       = trigger.closest('[data-accordion]')
      this.trigger  = trigger
      this.target   = trigger.closest('[data-accordion]').find('[data-accordion-content]')
      this.callback = trigger.data('callback')

      this.toggle_accordion( function( data )
      {
        if( data.is_open )
        {
          $(document).trigger( 'find_deferred_data', data )
        }
      });
    },
    toggle_accordion:function( func )
    {
      setTimeout(function( data )
      {
        data.target.toggleClass('visible')
      }, 400, this )

      this.trigger.toggleClass('active')
      this.el.find('.accordion-close').toggleClass('active');
      this.target.toggleClass('active')

      this.trigger.hasClass('active') ? this.is_open = true : this.is_open = false ;

      typeof func == 'function' ? func( this ) : '' ;
    }
  }

  $.fn.loadable_content = {
    el:'',
    action: '',
    extra_data:'',
    init:function( el, extra_data )
    {
      this.el         = el;
      this.action     = this.el.data('loadable-content')
      this.extra_data = extra_data;
    },
    load_content:function()
    {
      $.fn.overlay.show_overlay();

      $.fn.wp_get.init( this.action )
      $.fn.wp_get.make_request( function( data, instance )
      {
        data.variable_data = instance.extra
        return data
      },
      function( instance, resp )
      {
        resp.status ? instance.el.html( resp.data.loadable_content ) : '' ;

        $.fn.overlay.hide_overlay();

      }, this )
    }
  }

  $.fn.loadable_data = {
    init:function()
    {
      $(document).on( 'find_immediate_data', this, function( e )
      {
        e.data.find_immediate_data()
      })

      $(document).on( 'find_deferred_data', this, function( e, data )
      {
        e.data.find_deferred_data( data  )
      })
    },
    find_immediate_data:function()
    {
      $(document).find('[data-loadable-content]').each(function()
      {
        if( $(this).data('load-when') == 'now')
        {
          $.fn.loadable_content.init( $(this), $(this).data('load-extra') )
          $.fn.loadable_content.load_content( $.fn.loadable_content );
        }
      })
    },
    find_deferred_data:function( data )
    {
      data.target.find('[data-loadable-content]').each(function()
      {
        $.fn.loadable_content.init( $(this), $(this).data('load-extra') )
        $.fn.loadable_content.load_content( $.fn.loadable_content );
      })
    }
  }

  $(document).ready(function()
  {
    if( $('[data-overlay]')[0] != undefined )
      $.fn.overlay.init( $('[data-overlay]'), $('[data-overlay]').parent() );

    if( $('[data-validate-cc-card ]')[0] != undefined )
      $.fn.cc.init( $('[data-validate-cc-card ]'), $('[data-cc-input]'), $('[data-card-type]') )

    if( $('[data-tabs]')[0] != undefined && $('[data-tab-triggers]')[0] != undefined )
      $.fn.tabs.init( $('[data-tabs]'), $('[data-tab-triggers]') );

    if( $('[data-map-directions]')[0] != undefined )
      $.fn.directions.init( $('[data-map-directions]') );

      $.fn.loadable_data.init();
    $(this).trigger( 'find_immediate_data' )

    if( $('[data-fireable-input]')[0] != undefined )
    {
      $('[data-fireable-input]').on( 'change', function()
      {
        $(this).closest('form').trigger( 'submit' )
      })
    }

    if( $('[data-accordion]')[0] != undefined )
    {
      $(document).on( 'click', '[data-accordion-trigger]', function()
      {
        $.fn.accordion.init( $(this) )
      })
    }

    if( $('[data-ajax-form]')[0] != undefined )
    {
      $('[data-ajax-form]').submit( function( e )
      {
        e.preventDefault();
        $.fn.wp_ajax.init( $(this) );
        $.fn.wp_ajax.make_request( $.fn.wp_ajax );
      })
    }

    if( $('img[data-retina]')[0] != undefined && retina.is_retina() )
    {
      $('body').imagesLoaded(function()
      {
        $('img[data-retina]').each( function()
        {
          $.fn.retina.init( $(this) );
          $.fn.retina.replace_images();
        })
      })
    }

    load_map = function()
    {
      if( $('[data-map-canvas]')[0] != undefined )
      {
        $.fn.maps.init( $('[data-map-canvas]') );
      }
    }

    $(document).on('ready', function()
    {
      $(this).trigger( 'load_data', $(document) );
    })

    $(document).on( 'click', '[data-refesh-map]', function()
    {
      $(this).closest('form').find('[data-ajax-field]').trigger('focusout')
    })

    $(document).on( 'keydown', '[data-ajax-field]', function( e )
    {
      if( $(this).is(':focus') && e.keyCode == 13 )
      {
        $(this).closest('form').find('[data-ajax-field]').trigger('focusout')
      }
    })

    $(document).on( 'focusout', '[data-ajax-field]', function( e )
    {
      $.fn.wp_get.init( $(this).data('action') )

      $.fn.wp_get.make_request(
        function( data, e )
        {
          data.address = e.val();

          return data;
        },
        function( resp )
        {
          $.fn.maps.refresh_map( resp.data )
        },
        $(this)
      )

    })

    $(document).on( 'click', '[data-boolean-fee]', function( e )
    {
      var target = $(this).closest('[data-boolean-fee-parent]').find('[data-boolean-fee-input]');

      if( $(this).is(':checked') && !target.hasClass('active') )
      {
        target.addClass('active')
      }
      else
      {
        target.removeClass('active');
      }
    })

    $('#menu-item-27 a').html( '<i class="fa fa-fw fa-dashboard"></i> <br />Dashboard')
    $('#menu-item-25 a').html( '<i class="fa fa-fw fa-list"></i> <br />Rates')
    $('#menu-item-26 a').html( '<i class="fa fa-fw fa-home"></i> <br />Hotels')
    $('#menu-item-24 a').html( '<i class="fa fa-fw fa-file"></i> <br />Reservations')
  })


  $.fn.callback_bank = {
    filters:
    {
      // PRE_CALLBACKS **ALWAYS** NEED TO RETURN FORM DATA, REGARDLESS OF DOING ANYTHING WITH IT.
      before_form_submit:function( form_data )
      {
        return data;
      }
    },
    callbacks:
    {
      new_reservation:function( resp, instance )
      {

      },
      filter_hotel_list:function( resp, instance )
      {
        var target = $('[data-updateable-content="' + instance.target + '"]'),
            html;

        resp.status ?  html = resp.data.hotels : html = resp.message;

        target.html( html )
      },
      user_login:function( resp, instance )
      {
        $.fn.form_msg.init( instance.form_msg );
        $.fn.form_msg.add_msg( resp.message, resp.status );

        if( resp.status )
        {
          setTimeout( function( data )
          {
            window.location = resp.data.redirect_url;
          }, 1000, resp );
        }
      },
      user_register:function( resp, instance )
      {
        $.fn.form_msg.init( instance.form_msg );
        $.fn.form_msg.add_msg( resp.message, resp.status );
      },
      filter_dashboard_dates:function( resp, instance )
      {
        var target = $('[data-updateable-content="' + instance.target + '"]'),
            html;

        resp.status ?  html = resp.data.dashboard_dates : html = resp.message;

        target.html( html )
      }
    }
  }


})( jQuery, window );