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

  $.fn.callback_bank = {
    filters:{},
    callbacks:
    {
      generate_register_codes:function( resp, instance )
      {

      }
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

  $(document).ready(function()
  {
    if( $('[data-overlay]')[0] != undefined )
      $.fn.overlay.init( $('[data-overlay]'), $('[data-overlay]').parent() );

    if( $('[data-ajax-form]')[0] != undefined )
    {
      $('[data-ajax-form]').submit( function( e )
      {
        e.preventDefault();
        $.fn.wp_ajax.init( $(this) );
        $.fn.wp_ajax.make_request( $.fn.wp_ajax );
      })
    }
  })


})( jQuery, window );