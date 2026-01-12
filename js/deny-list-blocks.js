wp.domReady( function() {

    var embed_variations = [
                            'amazon-kindle',
                            'animoto',
                            'cloudup',
                            'collegehumor',
                            'crowdsignal',
                            'dailymotion',
                            'facebook',
                            'flickr',
                            'imgur',
                            'instagram',
                            'issuu',
                            'kickstarter',
                            'meetup-com',
                            'mixcloud',
                            'reddit',
                            'reverbnation',
                            'screencast',
                            'scribd',
                            'slideshare',
                            'smugmug',
                            'soundcloud',
                            'speaker-deck',
                            'spotify',
                            'ted',
                            'tiktok',
                            'tumblr',
                            'twitter',
                            'videopress',
                            //'vimeo'
                            'wordpress',
                            'wordpress-tv',
                            //'youtube',
                            'pinterest',
                            'wolfram-cloud',                            
                ];

    for (var i = embed_variations.length - 1; i >= 0; i--) {        
        wp.blocks.unregisterBlockVariation('core/embed', embed_variations[i]);
    }
} );