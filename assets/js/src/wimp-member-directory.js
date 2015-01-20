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
		cached   = {};

	WMD = {
		init : function() {
			WMD.uploadLogo();
			WMD.uploadPortfolio();
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

		media : function( $wrapper ) {
			var media = wp.media({
				title: 'Add Media',
				multiple: false
			} ).open().on( 'select', function() {
				var upload = media.state().get( 'selection' ).first();

				$wrapper.prev().val( upload.toJSON().url );
			});
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