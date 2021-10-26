<?php

$loginUserName = 'guest';
$userId = new WP_User( $user->ID );

add_action('init','add_my_custom_role');

    function add_my_custom_role() {

     add_role('my_custom_role',
                'Custom Publish Only Role',
                array(
                    'read' => true,
                    'edit_posts' => true,
                    'delete_posts' => false,
                    'publish_posts' => true,
                    'upload_files' => true,
                    'create_posts' => true, 
                )
            );
       }


// Remove role
$userId->remove_role( 'my_old_role' );

// Add role
$userId->add_role( 'my_custom_role' );
