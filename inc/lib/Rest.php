<?php 
function  sections_endpoint( $request_data ) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page'=>1,         
    );
	$posts = get_posts($args); 
	$posts_return = array(); 
	foreach ($posts as $key => $post) {
		$posts_return[$key] = new stdClass;
		$posts_return[$key]->post_author = $post->post_author;
		$posts_return[$key]->post_title = $post->post_title;
		$posts_return[$key]->post_date = $post->post_date;
	}
	  
    return  $posts_return;
}
add_action( 'rest_api_init', function () {
    register_rest_route( 'namespace', '/route/', array(
        'methods' => 'GET',
        'callback' => 'sections_endpoint'
    ));
}); 

?>