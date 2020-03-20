/* global shippingZoneMethodsLocalizeScript, ajaxurl */
( function( $, data, wp, ajaxurl ) {
	$( function() {
		var $table          = $( '.wc-ecfe-table' ),
			$tbody          = $( '.wc-ecfe-rows' ),
			$save_button    = $( '.wc-ecfe-save' ),
			$set_default    = $('.wc-ecfe-set-default'),
			$row_template   = _.template( 'wc-ecfe-row-template' ),
			$blank_template = wp.template( 'wc-ecfe-row-template-blank' ),
		
			$interpolate    = /\{\{(.+?)\}\}/g,


			// Backbone model
			ShippingMethod       = Backbone.Model.extend({
				initialize: function() { 
				 this.set(data);

				// console.log( this.get('wc_admin_mcfe_nonce_generated') );
				},
				save: function() {
					$.post( ajaxurl + ( ajaxurl.indexOf( '?' ) > 0 ? '&' : '?' ) + 'action=woocommerce_mcfe_settings_data', {
						wc_admin_mcfe_nonce : data.wc_admin_mcfe_nonce_generated,
						mcfe_data : data.mcfe_data
					}, this.onSaveResponse, 'json' );
				},
				onSaveResponse: function( response, textStatus ) {
					console.log(response);
					shippingMethod.trigger( 'data:responsed' );
					//console.log(response);
					//shippingMethod.set( 'methods', response.data.methods );
				}
			} ),

			// Backbone view
			ShippingMethodView = Backbone.View.extend({
				//rowTemplate: wp.template( 'wc-ecfe-row-template' ),
				rowTemplate: _.template( $("#wc-ecfe-row-template").html()  ),

				get_values: function( $nameKey ){
					var $return;
					_.mapObject( data.mcfe_data , function( $value, $key ){
						if( _.has( $value, $nameKey ) ){
							//console.log(_.result($value, $nameKey));
							$return = _.result($value, $nameKey);
						}						
					});
					return $return;
				},

				initialize: function() {

					view        = this;

					console.log(data);

					$.each( data.mcfe_data.billing, function( $key, $value ){
						view.$el.append( view.rowTemplate( { key: $key, value: $value } ));
					} );

					$.each( data.mcfe_data.shipping, function( $key, $value ){
						view.$el.append( view.rowTemplate( { key: $key, value: $value } ));
					} );

					$set_default.on( 'click', { view: this }, this.onSetDefault );
					$save_button.on( 'click', { view: this }, this.onSubmit );
					this.listenTo(this.model, 'data:responsed', this.render );

					$( document.body ).on( 'click', '.wc-shipping-zone-method-settings', { view: this }, this.onConfigureShippingMethod );

					$( document.body ).on( 'wc_backbone_modal_response', this.onAddFieldMethodSubmitted );


				},
				onUpdateZone: function( event ) {
				},
				block: function() {
					$( this.el ).block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				},
				unblock: function() {
					$( this.el ).unblock();
				},
				render: function() {
							// Blank out the contents.
							//this.$el.empty();
							this.unblock();

				},
				initTooltips: function() {
				},
				onSubmit: function( event ) {
					event.data.view.block();
					event.data.view.model.save();
					event.preventDefault();
				},
				onSetDefault: function(event){

					event.data.view.block();
					event.data.view.model.set('mcfe_data', '');
					event.data.view.model.save();

					event.preventDefault();
					//console.log();
					
					// var billing = event.data.view.model.get('mcfe_data').billing;
					// $.each( billing, function( $key, $value ){
					// 	console.log($key, $value);
					// 	event.data.view.$el.append(event.data.view.rowTemplate( { key: $key, value: $value } ));
					// } );

					//console.log( billing );
					//console.log( event.data.view.rowTemplate( billing ) );
					
					
				},
				onDeleteRow: function( event ) {
				},
				onToggleEnabled: function( event ) {
				},
				setUnloadConfirmation: function() {
				},
				clearUnloadConfirmation: function() {
				},
				unloadConfirmation: function( event ) {
				},
				updateModelOnChange: function( event ) {
				},
				updateModelOnSort: function( event ) {
				},
				onConfigureShippingMethod: function( event ) {
					event.preventDefault();
					
					//console.log( $(event.target).data('name') );
					var formFields = event.data.view.get_values( $(event.target).data('name') );
					//console.log( formFields );
					$( this ).WCBackboneModal({
						template : 'wc-modal-shipping-method-settings',
						variable : formFields,
					});
				},
				onConfigureShippingMethodSubmitted: function( event, target, posted_data ) {

				},
				showErrors: function( errors ) {
				},
				onAddShippingMethod: function( event ) {
				},
				onAddFieldMethodSubmitted: function( event, target, posted_data ) {
					console.log(posted_data);
				},
				onChangeShippingMethodSelector: function() {
				},
				onTogglePostcodes: function( event ) {
				}
			} ),
			shippingMethod = new ShippingMethod({
				methods: data.methods,
				zone_name: data.zone_name
			} ),
			shippingMethodView = new ShippingMethodView({
				model:    shippingMethod,
				el:       $tbody
			} );

		shippingMethodView.render();

		$tbody.sortable({
			items: 'tr',
			cursor: 'move',
			axis: 'y',
			handle: 'td.wc-shipping-zone-method-sort',
			scrollSensitivity: 40
		});
	});
})( jQuery, checkoutEditData, wp, ajaxurl );
