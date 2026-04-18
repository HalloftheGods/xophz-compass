<?php

class Xophz_Compass_Polls_API {

    public function register_routes() {
        register_rest_route( 'xophz-compass/v1', '/polls', array(
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_polls' ),
                'permission_callback' => '__return_true',
            ),
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'create_poll' ),
                'permission_callback' => function() {
                    return current_user_can( 'manage_options' );
                },
            )
        ) );

        register_rest_route( 'xophz-compass/v1', '/polls/(?P<id>\d+)/vote', array(
            array(
                'methods'  => 'POST',
                'callback' => array( $this, 'vote_poll' ),
                'permission_callback' => '__return_true',
            )
        ) );
    }

    public function get_polls( WP_REST_Request $request ) {
        if ( ! class_exists( 'Forminator_API' ) ) {
            return new WP_Error( 'forminator_missing', 'Forminator plugin is not active', array( 'status' => 500 ) );
        }

        $polls = Forminator_API::get_polls( null, 1, 999, 'publish' );
        
        $format_polls = function($modules) {
            $res = array();
            if (is_array($modules) || is_object($modules)) {
                foreach($modules as $m) {
                    $name = isset($m->settings['pollName']) ? $m->settings['pollName'] : $m->name;
                    $answers = isset($m->settings['answers']) ? $m->settings['answers'] : array();
                    
                    // Fetch results
                    $results = array();
                    if ( class_exists('Forminator_Form_Entry_Model') && method_exists('Forminator_Form_Entry_Model', 'count_entries') ) {
                        // Let's get entries for poll
                    }
                    
                    $res[] = array(
                        'id' => isset($m->id) ? $m->id : '',
                        'name' => $name,
                        'answers' => $answers,
                        'settings' => $m->settings,
                    );
                }
            }
            return $res;
        };

        return rest_ensure_response( $format_polls($polls) );
    }

    public function create_poll( WP_REST_Request $request ) {
        if ( ! class_exists( 'Forminator_API' ) ) {
            return new WP_Error( 'forminator_missing', 'Forminator plugin is not active', array( 'status' => 500 ) );
        }

        $name = $request->get_param('name');
        $emoji_options = $request->get_param('emojis'); // array of emojis
        if ( ! is_array($emoji_options) ) {
            $emoji_options = array();
        }

        if ( empty($name) ) {
            return new WP_Error( 'invalid_params', 'Name is required', array( 'status' => 400 ) );
        }

        $answers = array();
        foreach($emoji_options as $emoji) {
            $answers[] = array(
                'title' => $emoji,
                'use_image' => false,
                'image' => '',
                'hash' => md5($emoji . time() . rand(0, 1000))
            );
        }
        
        if ( empty($answers) ) {
             $answers[] = array(
                'title' => '🔥',
                'use_image' => false,
                'image' => '',
                'hash' => md5('fire' . time())
             );
        }

        $settings = array(
            'pollName' => $name,
            'answers' => $answers,
            // standard forminator poll settings
            'show-votes-count' => true,
            'results-behav' => 'show_after',
        );

        $poll_id = Forminator_API::add_poll( $name, array(), $settings );

        if ( is_wp_error( $poll_id ) ) {
            return $poll_id;
        }

        return rest_ensure_response( array(
            'success' => true,
            'poll_id' => $poll_id,
            'message' => 'Poll created'
        ) );
    }

    public function vote_poll( WP_REST_Request $request ) {
        if ( ! class_exists( 'Forminator_API' ) ) {
            return new WP_Error( 'forminator_missing', 'Forminator plugin is not active', array( 'status' => 500 ) );
        }

        $poll_id = $request->get_param('id');
        $emoji = $request->get_param('emoji');

        if ( empty($emoji) ) {
            return new WP_Error( 'invalid_params', 'Emoji vote is required', array( 'status' => 400 ) );
        }

        // Load the poll model
        $poll = Forminator_API::get_poll( $poll_id );
        if ( is_wp_error( $poll ) || ! is_object( $poll ) ) {
            return new WP_Error( 'not_found', 'Poll not found', array( 'status' => 404 ) );
        }

        $answers = isset($poll->settings['answers']) ? $poll->settings['answers'] : array();
        $found = false;
        $hash = '';

        foreach ( $answers as $ans ) {
            if ( $ans['title'] === $emoji ) {
                $found = true;
                $hash = isset($ans['hash']) ? $ans['hash'] : '';
                break;
            }
        }

        // If the emoji is new to this poll, add it dynamically
        if ( ! $found ) {
            $hash = md5( $emoji . time() . wp_rand(0, 1000) );
            $answers[] = array(
                'title'     => $emoji,
                'use_image' => false,
                'image'     => '',
                'hash'      => $hash
            );
            $poll->settings['answers'] = $answers;
            $poll->save();
        }

        // Record the entry using Forminator's API
        $entry_meta = array(
            array(
                'name'  => $poll_id, // For polls, the name is typically the poll ID
                'value' => $hash
            )
        );
        $entry_id = Forminator_API::add_poll_entry( $poll_id, $entry_meta );

        if ( is_wp_error( $entry_id ) ) {
            return $entry_id;
        }

        return rest_ensure_response( array( 
            'success' => true,
            'message' => 'Vote cast successfully',
            'hash' => $hash,
            'entry_id' => $entry_id
        ) );
    }
}
