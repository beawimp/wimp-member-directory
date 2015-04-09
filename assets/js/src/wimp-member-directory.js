/* global: jQuery, console, tinyMCE */

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
		tinymce  = window.tinymce,
		postSave = false,
		wmdFrame,
		$wmdField,
		listingLive = false;

	WMD = {
		init : function() {
			WMD.uploadLogo();
			WMD.uploadPortfolio();
			WMD.editListing();
			WMD.saveListing();

			// Initialize select2
			if ( $.fn.select2 ) {
				$( '.state-selection' ).select2({
					placeholder: 'Select a state'
				});
				$( '.city-selection' ).select2({
					placeholder: 'Select a city'
				});
				$( '.industry-selection' ).select2({
					placeholder: 'What industries do you work with?'
				});
				$( '.tech-selection' ).select2({
					placeholder: 'What technologies do you use?'
				});
				$( '.services-selection' ).select2({
					placeholder: 'What services do you provide?'
				});
			}
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
				} else if ( 'portfolio' === $val1.attr( 'data-type' ) ) {
					var attachmentID = attachment.id,
						name = 'wmd[portfolio][' + attachmentID + ']',
						tmpl = $( document.getElementById( 'portfolio-tmpl' ) ).html();

					$val1.attr({
						'name' : name,
						'data-id' : attachmentID
					}).val( attachment.url );

					$el.parent().after( tmpl );

					WMD.uploadPortfolio();
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

				$SELF.parent().find( '.loading' ).slideDown();

				WMD.ajax( 'wmd_save_listing_tax', nonce, data );
			});
		},

		saveListing : function() {
			$( document.getElementById( 'wmd-listings' ) ).submit( function( e ) {
				e.preventDefault();

				var $SELF    = $( this ),
					nonce    = $( document.getElementById( 'wmd-listing-nonce' ) ).val(),
					inputs   = this.elements,
					data     = {
						industry: {},
						tech: {},
						type: {},
						portfolio: {}
					};

				// Store that we are currently saving the listing
				tinyMCE.triggerSave();
				postSave = true;

				data['content'] = tinyMCE.get( 'content' ).getContent();
				data['id']      = $SELF.find( '#id' ).val();

				for ( var i = 0; i < inputs.length; i++ ) {
					var el = inputs[ i ];

					if ( el.hasAttribute( 'data-save' ) ) {
						var portfolio = el.name.match( /wmd\[(.*)\]\[[0-9]{1,10}\]/ );

						if ( null !== portfolio ) {
							var attachmentID = el.getAttribute( 'data-id' );
							data['portfolio'][ attachmentID ] = el.value;
						} else if ( 'industry' === el.id ) {
							data['industry'] = $( '#industry' ).select2( 'val' );
						} else if ( 'techonologies' === el.id ) {
							data['tech'] = $( '#techonologies' ).select2( 'val' );
						} else if ( 'services' === el.id ) {
							data['type'] = $( '#services' ).select2( 'val' );
						} else if ( 'publish' === el.id ) {
							if ( el.checked ) {
								data['publish'] = 'publish';
								listingLive = true;
							}
						} else {
							data[ el.id ] = el.value;
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
			$( document.getElementById( 'wmd-listings' ) ).find( '.loading' ).fadeOut();
			// Check if we are processing a taxonomy ajax request
			if ( ! postSave ) {
				var id = $wmdField.next().attr( 'id' );

				if ( 'add-city' === id ) {
					WMD.ajaxCitySuccess( data );
				} else {
					WMD.ajaxTaxSuccess( data );
				}
			} else {
				// Default Ajax post save
				WMD.postNotifications( 'success', data );
			}
		},

		ajaxTaxSuccess : function( data ) {
			var html = '<option value="' + data.term_id + '" selected="selected">' +
						data.name +
					'*</option>',
				$select = $wmdField.prev().prev(),
				selectedItems = $select.select2( 'val' ),
				message = data.name + ' has been created and is being reviewed*. ' +
					'Your option will display on your listing soon.';

			// Add our HTML to the select element
			$wmdField.val( '' ).prev().prev().append( html );

			// Add our values to select2
			selectedItems.push( data.term_id );
			$select.select2( 'val', selectedItems );

			WMD.listingTaxNotification( 'success', message );
		},

		ajaxCitySuccess : function( data ) {
			var html = '<option value="' + data.term_id + '" selected="selected">' +
					data.name +
				'</option>';

			// Add our HTML to the select field
			$wmdField.val( '' ).prev().prev().append( html );

			// Add our values to select2
			$wmdField.prev().prev().select2( 'val', data.term_id );
		},


		postNotifications : function( type, message ) {
			var html = '<div class="wmd-notification wmd-' + type + '">' + message,
				$wrapper = $( document.getElementById( 'wmd-notifications' ) );

			if ( listingLive ) {
				html += ' <a href="/membership-account/view-my-listing/">View Your Listing.</a></div>';
			}

			$wrapper.empty().html( html );

			$( document.getElementsByClassName( 'wmd-notification' ) ).fadeIn();
		},

		ajaxError : function( data ) {
			// Check if we are processing a taxonomy ajax request
			if ( ! postSave ) {
				WMD.listingTaxNotification( 'error', data );
			} else {
				WMD.postNotifications( 'error', data );
			}
		},

		listingTaxNotification : function( type, message ) {
			var html = '<div class="wmd-notification wmd-' + type + '">' + message + '</div>';

			$( document.getElementsByClassName( 'wmd-notification' ) ).fadeOut().remove();

			$wmdField.next().after( html ).next().fadeIn();
		},

		isInt : function( value ) {
			return ! isNaN( value ) && ( function(x) {
					return (x | 0) === x;
				})(parseFloat( value ) );
		},

		load : function() {
			// Make sure flexslider is loaded
			if ( $.flexslider ) {
				$( '.flexslider' ).flexslider( {
					'controlNav' : false
				} );
			}
		}
	};

	// Fire events on window load
	$( window ).load( WMD.load );

	// Fire events on document ready
	$( document ).ready( WMD.init );
} )( this, jQuery );