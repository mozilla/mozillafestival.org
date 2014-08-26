/* global $, nunjucks  */
'use strict';

$(function() {
	// the url to point at for thunderhug
	var apiURL = 'https://thunderhug.herokuapp.com';
	// array to store theme slugs converted into url hashes
	var themeHashes = [];

	/**
	 * Display sessions to user
	 *
	 * @param  {Array} sessions The session objects to render + display
	 */
	function displayProposals( sessions ) {
		// empty listing + mark as loading
		$( '#proposals' ).empty();
		$( '.proposal-listings' ).addClass( 'loading' );

		var sessionsToRender = [];
		sessions.forEach( function( session ) {
			session.excerpt = session.goals;
			session.goals = session.goals.replace(/\n/g, '<br />' );
			session.agenda = session.agenda.replace(/\n/g, '<br />' );
			session.scale = session.scale.replace(/\n/g, '<br />' );
			session.outcomes = session.outcomes.replace(/\n/g, '<br />' );

			sessionsToRender.push( session );
		});

		nunjucks.render( 'proposed-session.html', { sessions: sessionsToRender }, function( error, result ) {
			if( !error ) {
				return $( '#proposals' ).append( result );
			}

			console.log( error );
		});

		if( ! sessions.length ) {
			$( '#proposals' ).append( '<p>No proposals for this track yet. <a href="/propose">Propose one now</a>.</p>' );
		}

		$( '.proposal-listings' ).removeClass( 'loading' );
	}

	/**
	 * Display only sessions for a particular theme
	 *
	 * @param  {Array}  sessions   Full array of sessions proposed
	 * @param  {String} themeSlug  Slug for the theme to display
	 */
	function displayThemeProposals( sessions, themeSlug ) {
		var filteredSessions = sessions.filter( function( session ) {
			console.log( location.hash.substring( 1 ) );
			return session.themeSlug === themeSlug;
		});

		displayProposals( filteredSessions );
	}

	/*
		Get sessions
	 */
	$.ajax({
		url: apiURL + '/all',
		dataType: 'jsonp',
		success: function( data ) {
			var themes = {};
			data.themes.forEach( function( theme ) {
				themeHashes.push( '#' + theme.slug );
				themes[ theme.themeSlug ] = theme;

				$( '#proposal-filter' ).append( '<option value="' + theme.slug + '">' + theme.name + '</option>' );
			});
			var $select = $( '#proposal-filter' ).selectize()[0].selectize;

			// if the url hash is not one of our themes just process all tracks
			if( themeHashes.indexOf( location.hash ) === -1 ) {
				displayProposals( data.sessions );
			}
			else {
				displayThemeProposals( data.sessions, location.hash.substring( 1 ) );
			}


			function onChange( themeSlug ) {
				if( ! themeSlug || themeSlug === 'all' ) {
					return displayProposals( data.sessions );
				}

				if( themeHashes.indexOf( '#' + themeSlug ) === -1 ) {
					return;
				}

				displayThemeProposals( data.sessions, themeSlug );
			}

			$( window ).on( 'hashchange', function() {
				var themeSlug = location.hash.substring( 1 ) || 'all';

				$select.setValue( themeSlug );
				onChange( themeSlug );
			});
			$( '#proposal-filter' ).on( 'change', function() {
				location.hash = $( this ).val();
			});
		},
		error: function() {
			$( '.proposal-listings .constrained' ).empty().append( 'Ooops. Something went wrong.' );
			console.error( 'Failed to load proposals.', arguments );
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
