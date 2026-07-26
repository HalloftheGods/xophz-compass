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
            global $wpdb;
            $entry_table = $wpdb->prefix . 'forminator_form_entry';
            $meta_table  = $wpdb->prefix . 'forminator_form_entry_meta';
            $res = array();

            if (is_array($modules) || is_object($modules)) {
                foreach($modules as $m) {
                    $poll_id = isset($m->id) ? (int)$m->id : 0;
                    $name    = isset($m->settings['pollName']) ? $m->settings['pollName'] : $m->name;
                    $answers = isset($m->settings['answers']) ? $m->settings['answers'] : array();
                    
                    $vote_counts = array();
                    if ( $poll_id > 0 ) {
                        $sql = $wpdb->prepare(
                            "SELECT meta_key, meta_value, COUNT(*) as cnt FROM {$meta_table} WHERE entry_id IN (SELECT entry_id FROM {$entry_table} WHERE form_id = %d) GROUP BY meta_key, meta_value",
                            $poll_id
                        );
                        $rows = $wpdb->get_results( $sql );
                        if ( is_array($rows) ) {
                            foreach( $rows as $row ) {
                                $k1 = (string)$row->meta_key;
                                $k2 = (string)$row->meta_value;
                                $cnt = (int)$row->cnt;
                                if ( ! isset( $vote_counts[$k1] ) ) $vote_counts[$k1] = 0;
                                $vote_counts[$k1] += $cnt;
                                if ( ! isset( $vote_counts[$k2] ) ) $vote_counts[$k2] = 0;
                                $vote_counts[$k2] += $cnt;
                            }
                        }
                    }

                    $formatted_answers = array();
                    if ( is_array($answers) ) {
                        foreach ( $answers as $ans ) {
                            $hash  = isset($ans['hash']) ? $ans['hash'] : '';
                            $title = isset($ans['title']) ? $ans['title'] : '';
                            $votes = 0;
                            if ( ! empty($hash) && isset($vote_counts[$hash]) ) {
                                $votes = (int)$vote_counts[$hash];
                            } else if ( ! empty($title) && isset($vote_counts[$title]) ) {
                                $votes = (int)$vote_counts[$title];
                            }

                            $formatted_answers[] = array(
                                'hash'  => $hash,
                                'title' => $title,
                                'votes' => $votes,
                            );
                        }
                    }
                    
                    $res[] = array(
                        'id'       => $poll_id,
                        'name'     => $name,
                        'answers'  => $formatted_answers,
                        'settings' => isset($m->settings) ? $m->settings : array(),
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
        $emoji_options = $request->get_param('emojis');
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
                'hash' => md5($emoji . time() . wp_rand(0, 1000))
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
            if ( isset($ans['title']) && $ans['title'] === $emoji ) {
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
                'name'  => $hash,
                'value' => '1'
            ),
            array(
                'name'  => $emoji,
                'value' => '1'
            ),
            array(
                'name'  => 'entry',
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
