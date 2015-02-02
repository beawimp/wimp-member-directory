/* global: jQuery, console */

/**
 * WIMP Member Directory
 * http://beawimp.org/member-directory
 *
 * Copyright (c) 2014 Cole Geissinger
 * Licensed under the GPLv2+ license.
 */

var WMD;

( function( window, $, undefined ) {
	'use strict';

	var document = window.document,
		wp       = window.wp,
		cached   = {},
		wmdFrame,
		$wmdField;

	WMD = {
		init : function() {
			WMD.uploadLogo();
			WMD.uploadPortfolio();
			WMD.editListing();
			WMD.saveListing();
		},

		uploadLogo : function() {
			$( document.getElementById( 'upload-image' ) ).click( function( e ) {
				e.preventDefault();

				WMD.media( $( e.target ) );
			});
		},

		uploadPortfolio : function() {
			$( document.getElementsByClassName( 'upload-portfolio' ) ).click( function( e ) {
				e.preventDefault();

				WMD.media( $( e.target ) );
			});
		},

		media : function( $el ) {
			// If the frame already exists, reopen it.
			if ( typeof( wmdFrame ) !== 'undefined' ) {
				wmdFrame.close();
			}

			wmdFrame = wp.media.frames.customHeader = wp.media({
				title: 'Upload image to your Directory Listing',
				library: {
					type: 'image'
				},
				button: {
					text: 'Insert Image'
				},
				multiple: false
			});


			// Callback for the selected image
			wmdFrame.on( 'select', function() {
				var attachment = wmdFrame.state().get( 'selection' ).first().toJSON(),
					$val1 = $el.prev(),
					id = $val1.attr( 'id' );

				if ( 'logo-id' === id ) {
					var $val2 = $val1.prev();

					$val1.val( attachment.id );
					$val2.val( attachment.url );
				} else {
					$val1.val( attachment.url );
				}
			});

			// Open the frame.
			wmdFrame.open();
		},

		editListing : function() {
			$( document.getElementsByClassName( 'add-new-tax' ) ).click( function( e ) {
				e.preventDefault();

				var $SELF = $( this ),
					nonce = $( document.getElementById( 'wmd-listing-nonce' ) ).val();

				$wmdField = $SELF.prev();

				var data  = {
					term : $wmdField.val(),
					tax : $wmdField.attr( 'data-type' )
				};

				if ( 'add-city' === $SELF.attr( 'id' ) ) {
					data.isCity = true;
				}

				WMD.ajax( 'wmd_save_listing_tax', nonce, data );
			});
		},

		saveListing : function() {
			$( document.getElementById( 'wmd-listings' ) ).submit( function( e ) {
				e.preventDefault();

				var $SELF  = $( this ),
					nonce  = $( document.getElementById( 'wmd-listing-nonce' ) ).val(),
					inputs = this.elements,
					data   = {
						industry: {},
						tech: {},
						type: {},
						portfolio: {}
					},
					count = 1,
					lastType;

				data['content'] = $SELF.find( '#content' ).val();
				data['id'] = $SELF.find( '#id' ).val();

				for ( var i = 0; i < inputs.length; i++ ) {
					var el = inputs[ i ];

					if ( el.hasAttribute( 'data-save' ) ) {
						if ( WMD.isInt( el.id ) ) {
							var type = el.name.match( /wmd\[(.*)\]\[[0-9]{1,10}\]/ );

							data[ type[1] ][ count ] = el.value;

							// Reset the count for each new type handled
							if ( lastType && type[1] !== lastType ) {
								count = 1;
							} else {
								count ++;
							}
							lastType = type[1];
						} else {
							var portfolio = el.name.match( /wmd\[(.*)\]\[[0-9]{1,10}\]/ );

							if ( null !== portfolio ) {
								var attachmentID = el.getAttribute( 'data-id' );
								data['portfolio'][ attachmentID ] = el.value;
							} else {
								data[ el.id ] = el.value;
							}
						}
					}
				}

				WMD.ajax( 'wmd_save_listing_post', nonce, data );
			});
		},

		ajax : function( action, nonce, data ) {

			wp.ajax.send( action, {
				success: WMD.ajaxSuccess,
				error: WMD.ajaxError,
				data: {
					nonce: nonce,
					data: data
				}
			});
		},

		ajaxSuccess : function( data ) {
			var id = $wmdField.next().attr( 'id' );

			if ( 'add-city' === id ) {
				WMD.ajaxCitySuccess( data );
			} else {
				WMD.ajaxTaxSuccess( data );
			}
		},

		ajaxTaxSuccess : function( data ) {
			var html = '<label for="' + data.term_id + '">' +
					'<input type="checkbox" ' +
						'name="wmd[' + data.taxonomy + '][' + data.term_id + ']" ' +
						'value="' + data.name + '" ' +
						'id="' + data.term_id + '" ' +
						'checked="checked" /> ' +
					data.name +
				'</label>';

			$wmdField.val( '' ).prev().after( html );
		},

		ajaxCitySuccess : function( data ) {
			var html = '<option value="' + data.term_id + '" selected="selected">' +
					data.name +
				'</option>';

			$wmdField.val( '' ).prev().append( html );
		},

		ajaxError : function( data ) {
			WMD.listingTaxNotification( 'error', data );
		},

		listingTaxNotification : function( type, message ) {
			var html = '<div class="wmd-notification wmd-' + type + '">' + message + '</div>';

			$( document.getElementsByClassName( 'wmd-notification' ) ).fadeOut().remove();

			$wmdField.next().after( html ).next().fadeIn();

			if ( 'success' === type ) {
				$( document.getElementsByClassName( 'wmd-success' ) ).fadeOut().remove();
			}
		},

		isInt : function( value ) {
			return ! isNaN( value ) && ( function(x) {
					return (x | 0) === x;
				})(parseFloat( value ) );
		},

		load : function() {
			$( '.flexslider' ).flexslider({
				'controlNav' : false
			});
		}
	};

	// Fire events on window load
	$( window ).load( WMD.load );

	// Fire events on document ready
	$( document ).ready( WMD.init );
} )( this, jQuery );