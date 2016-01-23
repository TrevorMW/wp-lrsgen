;(function( $, window, undefined )
{
  $(document).ready(function()
  {
    $(document).on( 'keyup focusout', 'td[contenteditable]', function()
    {
      var form   = $(this).closest('tr').find('form[data-editable-row]'),
          val    = $(this).html(),
          target = $(this).data('edit-id');

      $('input[name="' + target + '"]').val( val );

      ajax_form.set_data( form )

      setTimeout(function()
      {
        ajax_form.make_request( '', function( response )
        {
          var resp = $.parseJSON( response );

          if( resp.status )
          {

          }
          else
          {

          }

        })
      }, 1000, form)
    })

  })

})(jQuery, window)