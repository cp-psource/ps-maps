<?php

/**
 * Sidebar widget for Google Maps Plugin.
 */
class AgmMapsWidget extends WP_Widget {
    public function __construct() {
        parent::__construct( false, 'Google Maps Widget' );
        $this->model = new AgmMapModel();
    }

    public function form( $instance ) {
        $fields = array(
            'title',
            'height',
            'width',
            'query',
            'query_custom',
            'network',
            'map_id',
            'show_as_one',
            'show_map',
            'show_markers',
            'show_images',
            'show_posts',
            'zoom',
        );
        $instance = array_intersect_key( $instance, array_flip( $fields ) );
        $instance = wp_parse_args( (array) $instance, array(
            'height' => 200,
            'width' => 200,
            'show_as_one' => 1,
            'show_map' => 1,
            'show_markers' => 1,
            'show_images' => 0,
            'show_posts' => 1,
            'zoom' => 7, // Add default value for zoom
            'query_custom' => '',
        ) );

        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $height = isset( $instance['height'] ) ? esc_attr( $instance['height'] ) : 200;
        $width = isset( $instance['width'] ) ? esc_attr( $instance['width'] ) : 200;
        $query = isset( $instance['query'] ) ? esc_attr( $instance['query'] ) : '';
        $query_custom = isset( $instance['query_custom'] ) ? esc_attr( $instance['query_custom'] ) : '';
        $network = isset( $instance['network'] ) ? esc_attr( $instance['network'] ) : '';
        $map_id = isset( $instance['map_id'] ) ? esc_attr( $instance['map_id'] ) : '';
        $show_as_one = isset( $instance['show_as_one'] ) ? esc_attr( $instance['show_as_one'] ) : 1;
        $show_map = isset( $instance['show_map'] ) ? esc_attr( $instance['show_map'] ) : 1;
        $show_markers = isset( $instance['show_markers'] ) ? esc_attr( $instance['show_markers'] ) : 1;
        $show_images = isset( $instance['show_images'] ) ? esc_attr( $instance['show_images'] ) : 0;
        $show_posts = isset( $instance['show_posts'] ) ? esc_attr( $instance['show_posts'] ) : 1;
        $zoom = isset( $instance['zoom'] ) ? esc_attr( $instance['zoom'] ) : 7;

        $zoom_items = array(
            '1' => __( 'Erde', 'psmaps' ),
            '3' => __( 'Kontinent', 'psmaps' ),
            '5' => __( 'Region', 'psmaps' ),
            '7' => __( 'Nahe Städte', 'psmaps' ),
            '12' => __( 'Stadtplan', 'psmaps' ),
            '15' => __( 'Details', 'psmaps' ),
        );

        $maps = $this->model->get_maps( null, -1 );

        include AGM_VIEWS_DIR . 'widget_settings.php';
    }

    public function update( $new_instance, $old_instance ) {
        $fields = array(
            'title',
            'height',
            'width',
            'query',
            'query_custom',
            'network',
            'map_id',
            'show_as_one',
            'show_map',
            'show_markers',
            'show_images',
            'show_posts',
            'zoom',
        );
        $new_instance = array_intersect_key( $new_instance, array_flip( $fields ) );
        $new_instance = wp_parse_args( (array) $new_instance, array(
            'height' => 200,
            'width' => 200,
            'show_as_one' => 1,
            'show_map' => 1,
            'show_markers' => 1,
            'show_images' => 0,
            'show_posts' => 1,
        ) );
    
        $instance = $old_instance;
        $instance['title'] = isset( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['height'] = isset( $new_instance['height'] ) ? strip_tags( $new_instance['height'] ) : '';
        $instance['width'] = isset( $new_instance['width'] ) ? strip_tags( $new_instance['width'] ) : '';
        $instance['query'] = isset( $new_instance['query'] ) ? strip_tags( $new_instance['query'] ) : '';
        $instance['query_custom'] = isset( $new_instance['query_custom'] ) ? strip_tags( $new_instance['query_custom'] ) : '';
        $instance['network'] = isset( $new_instance['network'] ) ? strip_tags( $new_instance['network'] ) : '';
        $instance['map_id'] = isset( $new_instance['map_id'] ) ? strip_tags( $new_instance['map_id'] ) : '';
        $instance['show_as_one'] = isset( $new_instance['show_as_one'] ) ? (int) $new_instance['show_as_one'] : 1;
        $instance['show_map'] = isset( $new_instance['show_map'] ) ? (int) $new_instance['show_map'] : 1;
        $instance['show_markers'] = isset( $new_instance['show_markers'] ) ? (int) $new_instance['show_markers'] : 1;
        $instance['show_images'] = isset( $new_instance['show_images'] ) ? (int) $new_instance['show_images'] : 0;
        $instance['show_posts'] = isset( $new_instance['show_posts'] ) ? (int) $new_instance['show_posts'] : 1;
        $instance['zoom'] = isset( $new_instance['zoom'] ) ? (int) $new_instance['zoom'] : 7;
        return $instance;
    }
    

    public function widget( $args, $instance ) {
        $fields = array(
            'title',
            'height',
            'width',
            'query',
            'query_custom',
            'network',
            'map_id',
            'show_as_one',
            'show_map',
            'show_markers',
            'show_images',
            'show_posts',
            'zoom',
        );
        $instance = array_intersect_key( $instance, array_flip( $fields ) );
        $instance = wp_parse_args( (array) $instance, array(
            'height' => 200,
            'width' => 200,
            'show_as_one' => 1,
            'show_map' => 1,
            'show_markers' => 1,
            'show_images' => 0,
            'show_posts' => 1,
            'zoom' => 7, // Standardwert für Zoom
        ) );
    
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $height = (int) $instance['height'];
        $height = $height ? $height : 200;
        $width = (int) $instance['width'];
        $width = $width ? $width : 200;
        $query = $instance['query'];
        $query_custom = $instance['query_custom'];
        $network = $instance['network'];
        $map_id = $instance['map_id'];
        $show_as_one = (int) agm_positive_values( $instance['show_as_one'] );
        $show_map = (int) agm_positive_values( $instance['show_map'] );
        $show_markers = (int) agm_positive_values( $instance['show_markers'] );
        $show_images = (int) agm_positive_values( $instance['show_images'] );
        $show_posts = (int) agm_positive_values( $instance['show_posts'] );
        $zoom = (int) $instance['zoom'];
    
        // Überprüfen, ob 'zoom' im $instance-Array vorhanden ist
        if (isset($instance['zoom'])) {
            $zoom = (int) $instance['zoom'];
        } else {
            $zoom = 7; // Standardwert für Zoom
        }
    
        $maps = $this->get_maps(
            $query,
            $query_custom,
            $map_id,
            $show_as_one,
            $network
        );
    
        echo '' . $before_widget;
        if ( $title ) {
            echo '' . $before_title . $title . $after_title;
        }
    
        if ( is_array( $maps ) ) {
            foreach ( $maps as $map ) {
                $selector = 'agm_widget_map_' . md5( microtime() . rand() );
                $map['show_posts'] = (int) $show_posts;
                $map['height'] = $height;
                $map['width'] = $width;
                $map['show_map'] = $show_map;
                $map['show_markers'] = $show_markers;
                $map['show_images'] = $show_images;
    
                if ( $zoom ) {
                    $map['zoom'] = $zoom;
                }
    
                AgmDependencies::ensure_presence();
                ?>
                <div class="agm-google_map-widget" id="<?php echo esc_attr( $selector ); ?>"></div>
                <script type="text/javascript">
                _agmMaps[_agmMaps.length] = {
                    selector: "#<?php echo esc_attr( $selector ); ?>",
                    data: <?php echo json_encode( $map ); ?>
                };
                </script>
                <?php
            }
        }
    
        echo '' . $after_widget;
    }

    public function get_maps( $query, $custom, $map_id, $show_as_one, $network ) {
        $ret = false;
        switch ( $query ) {
            case 'current':
                $ret = $this->model->get_current_maps();
                break;

            case 'all_posts':
                $ret = $this->model->get_all_posts_maps();
                break;

            case 'all':
                $ret = $this->model->get_all_maps();
                break;

            case 'random':
                $ret = $this->model->get_random_map();
                break;

            case 'custom':
                if ( $network ) {
                    $ret = $this->model->get_custom_network_maps( $custom );
                } else {
                    $ret = $this->model->get_custom_maps( $custom );
                }
                break;

            case 'id':
                $ret = array( $this->model->get_map( $map_id ) );
                break;

            default:
                $ret = false;
                break;
        }

        if ( $ret && $show_as_one ) {
            return array( $this->model->merge_markers( $ret ) );
        }

        return $ret;
    }

}
