/* Registro en el editor de los bloques dinámicos del theme (vista previa del servidor). */
( function ( wp ) {
	var el = wp.element.createElement;
	var SSR = wp.serverSideRender;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var blocks = window.flmmBlocks || {};

	Object.keys( blocks ).forEach( function ( slug ) {
		var name = 'flmm/' + slug;
		if ( wp.blocks.getBlockType( name ) && wp.blocks.getBlockType( name ).edit ) {
			return;
		}
		wp.blocks.registerBlockType( name, {
			apiVersion: 3,
			title: blocks[ slug ],
			category: 'theme',
			icon: 'admin-appearance',
			edit: function ( props ) {
				return el(
					'div',
					useBlockProps( { className: 'flmm-ssr flmm-ssr--' + slug } ),
					el( SSR, {
						block: name,
						attributes: props.attributes,
						urlQueryArgs: props.context && props.context.postId ? { post_id: props.context.postId } : {},
					} )
				);
			},
			save: function () {
				return null;
			},
		} );
	} );
} )( window.wp );
