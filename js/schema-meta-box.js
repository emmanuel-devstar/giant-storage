/**
 * Schema Meta Box - Entry Point
 * This file loads the JSX component for post edit screens
 */

(function() {
    'use strict';

    // Wait for DOM and dependencies
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSchemaMetaBox);
    } else {
        initSchemaMetaBox();
    }

    function initSchemaMetaBox() {
        // Check if we're on a post edit screen with the meta box
        const metaBoxContainer = document.getElementById('modular-schema-meta-box');
        if (!metaBoxContainer) {
            return;
        }

        // Wait for React and Babel to be available
        if (typeof React === 'undefined' || typeof ReactDOM === 'undefined' || typeof Babel === 'undefined') {
            console.error('Schema Meta Box: Required dependencies not loaded');
            return;
        }

        // Load and transform the JSX component
        fetch(modularSchema.themeUri + '/js/modules/schema-meta-box.jsx')
            .then(response => response.text())
            .then(jsxCode => {
                try {
                    // Transform JSX to JavaScript
                    const transformedCode = Babel.transform(jsxCode, {
                        presets: ['react'],
                        plugins: []
                    }).code;

                    // Execute the transformed code
                    eval(transformedCode);
                } catch (error) {
                    console.error('Schema Meta Box: Error transforming JSX', error);
                    metaBoxContainer.innerHTML = '<div class="error"><p>Error loading schema meta box. Please check the console.</p></div>';
                }
            })
            .catch(error => {
                console.error('Schema Meta Box: Error loading JSX file', error);
                metaBoxContainer.innerHTML = '<div class="error"><p>Error loading schema meta box. Please check the console.</p></div>';
            });
    }
})();

