/* global $, nunjucks  */
'use strict';

$(function() {
	// the url to point at for thunderhug
	var apiURL = 'http://thunderhug.dev';
	// array to store theme slugs converted into url hashes
	var themeHashes = [];

	/**
	 * Display sessions to user
	 *
	 * @param  {Array} sessions The session objects to render + display
	 */
	function displayProposals( sessions ) {
		// empty listing + mark as loading
		$( '.proposal-listings .constrained' ).empty();
		$( '.proposal-listings' ).addClass( 'loading' );

		sessions.forEach( function( session ) {
			session.excerpt = session.goals;
			session.goals = session.goals.replace(/\n/g, '<br />' );
			session.agenda = session.agenda.replace(/\n/g, '<br />' );
			session.scale = session.scale.replace(/\n/g, '<br />' );
			session.outcomes = session.outcomes.replace(/\n/g, '<br />' );

			nunjucks.render( 'proposed-session.html', session, function( error, result ) {
				if( !error ) {
					return $( '.proposal-listings .constrained' ).append( result );
				}

				console.log( error );
			});
		});

		$( '.proposal-listings' ).removeClass( 'loading' );
		if( $( '.proposal-listings .constrained' ).html() == '' ) {
			$( '.proposal-listings .constrained' ).append( 'Ooops. Something went wrong.' );
		}
	}

	/*
		Get sessions
	 */
	$.ajax({
		url: apiURL + '/all',
		dataType: 'jsonp',
		success: function( data ) {
			console.log( 'cake' );
			data.themes.forEach( function( theme ) {
				themeHashes.push( '#' + theme.slug );
			});

			// if the url hash is not one of our themes just process all tracks
			if( themeHashes.indexOf( location.hash ) === -1 ) {
				displayProposals( data.sessions );
			}
		}
	});

	/*
		Toggle expand + strink
	 */
	$( '.proposal-listings' ).on( 'click', '.session header', function(){
		$( this ).parent().children( 'article' ).slideToggle();
		$( this ).parent().toggleClass( 'expanded' );
	});
});
