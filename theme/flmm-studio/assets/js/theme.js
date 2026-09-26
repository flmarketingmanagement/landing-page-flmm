/* FLMM Studio: header, menú móvil, animaciones, carrusel del equipo, índice del artículo y formulario. */
( function () {
	'use strict';
	var cfg = window.flmmTheme || {};
	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* Header: borde al hacer scroll. */
	var header = document.querySelector( '.flmm-site-header' );
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	/* Menú móvil. */
	var toggle = document.querySelector( '.flmm-menu-toggle' );
	var menu = document.getElementById( 'flmm-menu' );
	if ( toggle && menu ) {
		var setOpen = function ( open ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			menu.classList.toggle( 'is-open', open );
			var label = toggle.querySelector( '.screen-reader-text' );
			if ( label ) {
				label.textContent = open ? toggle.dataset.labelClose : toggle.dataset.labelOpen;
			}
		};
		toggle.addEventListener( 'click', function () {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );
		menu.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( 'a' ) ) {
				setOpen( false );
			}
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				setOpen( false );
				toggle.focus();
			}
		} );
	}

	/* Aparición suave de secciones. */
	var rv = document.querySelectorAll( '.flmm-rv' );
	// Lo que ya está en pantalla al cargar se muestra sin animación (no retrasa el LCP).
	rv.forEach( function ( el ) {
		if ( el.getBoundingClientRect().top < window.innerHeight ) {
			el.classList.add( 'is-in' );
		}
	} );
	document.documentElement.classList.add( 'flmm-rv-ready' );
	if ( ! reduce && 'IntersectionObserver' in window ) {
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-in' );
					io.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.12 } );
		rv.forEach( function ( el ) {
			io.observe( el );
		} );
	} else {
		rv.forEach( function ( el ) {
			el.classList.add( 'is-in' );
		} );
	}

	/* Equipo en orden aleatorio: se mezcla una vez por sesión del navegador y se mantiene durante la visita. */
	document.querySelectorAll( '.flmm-team' ).forEach( function ( track ) {
		var members = Array.prototype.slice.call( track.querySelectorAll( ':scope > .flmm-member' ) );
		if ( members.length < 2 ) {
			return;
		}
		var nameOf = function ( el ) {
			var h = el.querySelector( 'h3' );
			return h ? h.textContent.trim() : '';
		};
		var order = null;
		try {
			order = JSON.parse( window.sessionStorage.getItem( 'flmm-team-order' ) || 'null' );
		} catch ( e ) {}
		var names = members.map( nameOf );
		var valid = Array.isArray( order ) && order.length === names.length && names.every( function ( n ) {
			return order.indexOf( n ) !== -1;
		} );
		if ( ! valid ) {
			order = names.slice();
			for ( var i = order.length - 1; i > 0; i-- ) {
				var j = Math.floor( Math.random() * ( i + 1 ) );
				var tmp = order[ i ];
				order[ i ] = order[ j ];
				order[ j ] = tmp;
			}
			try {
				window.sessionStorage.setItem( 'flmm-team-order', JSON.stringify( order ) );
			} catch ( e ) {}
		}
		order.forEach( function ( name ) {
			var el = members[ names.indexOf( name ) ];
			if ( el ) {
				track.appendChild( el );
			}
		} );
	} );

	/* Carrusel del equipo: botones anterior y siguiente. */
	document.querySelectorAll( '.flmm-team' ).forEach( function ( track ) {
		var isEs = cfg.lang === 'es';
		var nav = document.createElement( 'div' );
		nav.className = 'flmm-team-nav';
		nav.innerHTML =
			'<button type="button" class="flmm-tnav" data-dir="-1" aria-label="' + ( isEs ? 'Anterior' : 'Previous' ) + '">←</button>' +
			'<button type="button" class="flmm-tnav" data-dir="1" aria-label="' + ( isEs ? 'Siguiente' : 'Next' ) + '">→</button>';
		track.parentNode.insertBefore( nav, track );
		track.setAttribute( 'tabindex', '0' );
		track.setAttribute( 'role', 'region' );
		track.setAttribute( 'aria-label', isEs ? 'Equipo' : 'Team' );
		var buttons = nav.querySelectorAll( 'button' );
		var update = function () {
			var max = track.scrollWidth - track.clientWidth - 4;
			buttons[ 0 ].disabled = track.scrollLeft <= 4;
			buttons[ 1 ].disabled = track.scrollLeft >= max;
		};
		buttons.forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				var card = track.querySelector( '.flmm-member' );
				var step = card ? card.offsetWidth + 16 : track.clientWidth;
				track.scrollBy( { left: step * Number( b.dataset.dir ), behavior: reduce ? 'auto' : 'smooth' } );
			} );
		} );
		track.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowRight' || e.key === 'ArrowLeft' ) {
				e.preventDefault();
				buttons[ e.key === 'ArrowRight' ? 1 : 0 ].click();
			}
		} );
		track.addEventListener( 'scroll', update, { passive: true } );
		window.addEventListener( 'resize', update );
		update();
	} );

	/* Índice del artículo con los H2 y resaltado de la sección visible. */
	var toc = document.querySelector( '[data-flmm-toc]' );
	var prose = document.querySelector( '.flmm-post-main .flmm-prose' );
	if ( toc && prose ) {
		var slug = function ( text ) {
			return text.normalize( 'NFD' ).replace( /[̀-ͯ]/g, '' ).toLowerCase().replace( /[^a-z0-9]+/g, '-' ).replace( /^-|-$/g, '' );
		};
		var list = toc.querySelector( 'ol' );
		var headings = Array.prototype.filter.call( prose.querySelectorAll( 'h2' ), function ( h ) {
			return ! h.closest( '.is-style-takeaways' );
		} );
		var used = {};
		headings.forEach( function ( h ) {
			if ( ! h.id ) {
				var base = slug( h.textContent ) || 'section';
				var id = base;
				var i = 2;
				while ( used[ id ] || document.getElementById( id ) ) {
					id = base + '-' + i++;
				}
				h.id = id;
			}
			used[ h.id ] = true;
			var li = document.createElement( 'li' );
			var a = document.createElement( 'a' );
			a.href = '#' + h.id;
			a.textContent = h.textContent;
			li.appendChild( a );
			list.appendChild( li );
		} );
		if ( headings.length > 1 ) {
			toc.hidden = false;
			var links = list.querySelectorAll( 'a' );
			if ( 'IntersectionObserver' in window ) {
				var spy = new IntersectionObserver( function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							links.forEach( function ( a ) {
								var on = a.getAttribute( 'href' ) === '#' + entry.target.id;
								a.classList.toggle( 'is-active', on );
								if ( on ) {
									a.setAttribute( 'aria-current', 'true' );
								} else {
									a.removeAttribute( 'aria-current' );
								}
							} );
						}
					} );
				}, { rootMargin: '-20% 0px -70% 0px' } );
				headings.forEach( function ( h ) {
					spy.observe( h );
				} );
			}
		}
	}

	/* Servicios del formulario: casillas de Jetpack mostradas como desplegable de selección múltiple. */
	document.querySelectorAll( '.flmm-services-field-wrap' ).forEach( function ( wrap, idx ) {
		var list = wrap.querySelector( '.grunion-checkbox-multiple-options' );
		var fieldset = wrap.querySelector( 'fieldset' );
		if ( ! list || ! fieldset ) {
			return;
		}
		var boxes = Array.prototype.slice.call( list.querySelectorAll( 'input[type=checkbox]' ) );
		var placeholder = cfg.servicesPlaceholder || 'Select one or more services';
		var btn = document.createElement( 'button' );
		btn.type = 'button';
		btn.className = 'flmm-select';
		btn.setAttribute( 'aria-haspopup', 'true' );
		btn.setAttribute( 'aria-expanded', 'false' );
		list.id = list.id || 'flmm-services-list-' + idx;
		btn.setAttribute( 'aria-controls', list.id );
		btn.innerHTML = '<span class="flmm-select__value"></span><span class="flmm-select__chev" aria-hidden="true"></span>';
		fieldset.insertBefore( btn, list );
		wrap.classList.add( 'is-enhanced' );
		var value = btn.querySelector( '.flmm-select__value' );
		var render = function () {
			var chosen = boxes.filter( function ( b ) {
				return b.checked;
			} ).map( function ( b ) {
				return b.value;
			} );
			value.textContent = chosen.length ? chosen.join( ', ' ) : placeholder;
			btn.classList.toggle( 'has-value', chosen.length > 0 );
		};
		var setOpen = function ( open ) {
			wrap.classList.toggle( 'is-open', open );
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		};
		btn.addEventListener( 'click', function () {
			setOpen( ! wrap.classList.contains( 'is-open' ) );
		} );
		boxes.forEach( function ( b ) {
			b.addEventListener( 'change', render );
		} );
		document.addEventListener( 'click', function ( e ) {
			if ( ! wrap.contains( e.target ) ) {
				setOpen( false );
			}
		} );
		wrap.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && wrap.classList.contains( 'is-open' ) ) {
				setOpen( false );
				btn.focus();
			}
		} );
		wrap.addEventListener( 'focusout', function ( e ) {
			if ( e.relatedTarget && ! wrap.contains( e.relatedTarget ) ) {
				setOpen( false );
			}
		} );
		render();
	} );

	/* Copiar enlace. */
	document.querySelectorAll( '[data-flmm-copy]' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var url = btn.getAttribute( 'data-flmm-copy' );
			var done = function () {
				btn.textContent = cfg.copied || 'Copied!';
			};
			if ( navigator.clipboard ) {
				navigator.clipboard.writeText( url ).then( done, done );
			} else {
				done();
			}
		} );
	} );

	/* Formulario sin Jetpack: abre el correo del visitante con el mensaje. */
	document.querySelectorAll( '[data-flmm-mailto]' ).forEach( function ( form ) {
		var msg = form.querySelector( '.flmm-form__msg' );
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			if ( ! form.checkValidity() ) {
				msg.textContent = cfg.fill || 'Please fill in all three fields.';
				return;
			}
			var d = new FormData( form );
			var body = d.get( 'message' ) + '\n\n' + d.get( 'name' ) + ' · ' + d.get( 'email' );
			window.location.href = 'mailto:' + ( cfg.email || '' ) + '?subject=' + encodeURIComponent( ( cfg.subject || '' ) + d.get( 'name' ) ) + '&body=' + encodeURIComponent( body );
			msg.textContent = cfg.opening || '';
		} );
	} );
} )();
