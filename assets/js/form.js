jQuery( function ( $ ) {
   "use strict"

   $( '.simple-crm-main-form' ).on( 'submit', function () {
      var self = $(this);

      var data = {};

      $.each( self.serializeArray(), function ( i, field ) {
         data[ field.name ] = field.value;
      } );

      data['action'] = 'handle_form_submitting';

      $( '.simple-crm-main-form-wrapper' ).css( { 'opacity': '0.3' } );
      $( '.simple-crm-main-form-result' ).html( '' );

      wp.ajax.post( data ).done( () => {
         showResultMessage( 'The form has been sent successfully' );
      } ).fail( ( error ) => {
         showResultMessage( 'Something went wrong' );
      } );
      return false;
   } );

   function showResultMessage( message ) {
      $( '.simple-crm-main-form-wrapper' ).css( { 'opacity': '1' } );
      $( '.simple-crm-main-form-result' ).html( message );
      $( '.simple-crm-main-form-result' ).css( { 'display' : 'inline-block' } );

      setTimeout( function () {
         $( '.simple-crm-main-form-result' ).html( '' );
         $( '.simple-crm-main-form-result' ).css( { 'display' : 'none' } );
      }, 4000 );

      $( '.simple-crm-main-form-wrapper' ).find( 'input' ).val( '' );
      $( '.simple-crm-main-form-wrapper' ).find( 'textarea' ).val( '' );
   }
} );