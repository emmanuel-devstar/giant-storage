/**
 * Schema Admin - Entry Point
 * This file loads the JSX component and transforms it using Babel Standalone
 */

(function() {
    'use strict';

    // Wait for DOM and dependencies
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSchemaAdmin);
    } else {
        initSchemaAdmin();
    }

    function initSchemaAdmin() {
        // Check if we're on the schema admin page
        const adminContainer = document.getElementById('modular-schema-admin');
        if (!adminContainer) {
            return;
        }

        // Wait for React and Babel to be available
        if (typeof React === 'undefined' || typeof ReactDOM === 'undefined' || typeof Babel === 'undefined') {
            console.error('Schema Admin: Required dependencies not loaded');
            return;
        }

        // Load and transform the JSX component
        fetch(modularSchema.themeUri + '/js/modules/schema-admin.jsx')
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
                    console.error('Schema Admin: Error transforming JSX', error);
                    adminContainer.innerHTML = '<div class="error"><p>Error loading schema admin. Please check the console.</p></div>';
                }
            })
            .catch(error => {
                console.error('Schema Admin: Error loading JSX file', error);
                adminContainer.innerHTML = '<div class="error"><p>Error loading schema admin. Please check the console.</p></div>';
            });
    }
})();

