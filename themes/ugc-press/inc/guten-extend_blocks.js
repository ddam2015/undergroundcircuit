/**
 * Dependencies
 */
const { __ } = wp.i18n;
// const { G, SVG, Path } = wp.components;
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.editor;
// import { getColumnsTemplate } from './utils';


wp.hooks.addFilter(
  'blocks.registerBlockType',
  'g365-press/g365_press',
  function( settings, name ) {
    if ( name === 'core/media-text' ) {
      return lodash.assign( {}, settings, {
        supports: lodash.assign( {}, settings.supports, {
          align: ['center', 'full', 'overlay']
        } ),
      } );
    }
    return settings;
  }
);


//plain container
wp.blocks.registerBlockType('g365/general-container', {
  title: 'Single Div Container',
  icon: 'id-alt',
  category: 'layout',
  supports: { align: true },
  attributes: {},
  edit: function(props) {
    var InspectorControls = wp.editor.InspectorControls;
    var PanelBody = wp.components.PanelBody;

    // in edit() method
    return [
      wp.element.createElement(
        InspectorControls,
        null,
        wp.element.createElement('p', {
          title: __('Panel Title'),
          initialOpen: true
        })
      ),
      wp.element.createElement('div', { className: props.className },
        wp.element.createElement(InnerBlocks)
      )
    ]
  },
  save: function(props) {
    return (
      wp.element.createElement( 'div', { className: props.className },
        wp.element.createElement( InnerBlocks.Content, {} )
      )
    )
  }
});


//       wp.element.createElement('div', { className: props.className },
//         wp.element.createElement(InnerBlocks, {
//           template: [
//             ['core/columns', {columns: 1, min: 1}, [
//               ['core/column']
//             ]]
//           ]
//         })
//       )


//   edit: function( props ) {
//     return(
//       <div className={props.className}>
//         <InnerBlocks allowedBlocks={ [ 'core/column' ] }/>
//       </div>
//       );
//     },
//   save: function( props ) {
//       return null;
//   }

// /* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
  
// wp.blocks.registerBlockType('pulse/general-container', {
//   title: 'Single Div Container',
//   icon: 'id-alt',
//   category: 'layout',
//   attributes: {
//     content: {type: 'string'},
//     color: {type: 'string'}
//   },
  
// /* This configures how the content and color fields will work, and sets up the necessary elements */
  
//   edit: function(props) {
//     function updateContent(event) {
//       props.setAttributes({content: event.target.value})
//     }
//     function updateColor(value) {
//       props.setAttributes({color: value.hex})
//     }
//     return React.createElement(
//       "div",
//       null,
//       React.createElement(
//         "h3",
//         null,
//         "Simple Box"
//       ),
//       React.createElement("input", { type: "text", value: props.attributes.content, onChange: updateContent }),
//       React.createElement(wp.components.ColorPicker, { color: props.attributes.color, onChangeComplete: updateColor })
//     );
//   },
//   save: function(props) {
//     return wp.element.createElement(
//       "h3",
//       { style: { border: "3px solid " + props.attributes.color } },
//       props.attributes.content
//     );
//   }
// })