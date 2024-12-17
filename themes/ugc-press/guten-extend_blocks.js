wp.hooks.addFilter(
  'blocks.registerBlockType',
  'g365-press/g365_press',
  function( settings, name ) {
//     if ( name === 'core/media-text' ) {
//       return lodash.assign( {}, settings, {
//         supports: lodash.assign( {}, settings.supports, {
//           align: ['center', 'full']
//         } ),
//       } );
//     }
    return settings;
  }
);