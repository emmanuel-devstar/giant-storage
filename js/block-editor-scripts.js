wp.domReady( () => {
            
    wp.blocks.registerBlockStyle("core/button", {
            name: "mycustomstyle",
            label: "My Custom Style",   
            isDefault: true   
    });
    
    wp.blocks.unregisterBlockStyle( 'core/button', 'default' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
    wp.blocks.unregisterBlockStyle( 'core/buttosn', 'outline' );
 
} );