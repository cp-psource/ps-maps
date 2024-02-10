<?php
/*
Plugin Name: Google Places Support
Description: Ermöglicht das Anzeigen von Orten in der Nähe. Im Dialogfeld "Kartenoptionen" stehen neue Optionen zur Verfügung.
Plugin URI:  https://n3rds.work/piestingtal-source-project/ps-gmaps/
Version:     1.0
Author:      DerN3rd (PSOURCE)
*/

class Agm_PlacesAdminPages {

	private function __construct() {}

	public static function serve() {
		$me = new Agm_PlacesAdminPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		// UI
		add_action(
			'agm-admin-scripts',
			array( $this, 'load_scripts' )
		);
		add_filter(
			'agm-save-options',
			array( $this, 'prepare_for_save' ),
			10, 2
		);
		add_filter(
			'agm-load-options',
			array( $this, 'prepare_for_load' ),
			10, 2
		);

		// Adding in map defaults
		add_action(
			'agm_google_maps-options',
			array( $this, 'inject_default_location_types' )
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/admin/places.min.js' );
	}

	public function prepare_for_save( $options, $raw ) {
		$options['show_places'] = isset( $raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset( $raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset( $raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}

	public function prepare_for_load( $options, $raw ) {
		$options['show_places'] = isset( $raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset( $raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset( $raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}

	public function inject_default_location_types( $options ) {
		$options['place_types'] = array(
			'accounting' => __( 'Accounting', 'psmaps' ),
			'airport' => __( 'Airport', 'psmaps' ),
			'amusement_park' => __( 'Amusement park', 'psmaps' ),
			'aquarium' => __( 'Aquarium', 'psmaps' ),
			'art_gallery' => __( 'Art gallery', 'psmaps' ),
			'atm' => __( 'ATM', 'psmaps' ),
			'bakery' => __( 'Bakery', 'psmaps' ),
			'bank' => __( 'Bank', 'psmaps' ),
			'bar' => __( 'Bar', 'psmaps' ),
			'beauty_salon' => __( 'Beauty salon', 'psmaps' ),
			'bicycle_store' => __( 'Bicycle store', 'psmaps' ),
			'book_store' => __( 'Book store', 'psmaps' ),
			'bowling_alley' => __( 'Bowling alley', 'psmaps' ),
			'bus_station' => __( 'Bus station', 'psmaps' ),
			'cafe' => __( 'Cafe', 'psmaps' ),
			'campground' => __( 'Campground', 'psmaps' ),
			'car_dealer' => __( 'Car dealer', 'psmaps' ),
			'car_rental' => __( 'Car rental', 'psmaps' ),
			'car_repair' => __( 'Car repair', 'psmaps' ),
			'car_wash' => __( 'Car wash', 'psmaps' ),
			'casino' => __( 'Casino', 'psmaps' ),
			'cemetery' => __( 'Cemetery', 'psmaps' ),
			'church' => __( 'Church', 'psmaps' ),
			'city_hall' => __( 'City hall', 'psmaps' ),
			'clothing_store' => __( 'Clothing store', 'psmaps' ),
			'convenience_store' => __( 'Convenience store', 'psmaps' ),
			'courthouse' => __( 'Courthouse', 'psmaps' ),
			'dentist' => __( 'Dentist', 'psmaps' ),
			'department_store' => __( 'Department store', 'psmaps' ),
			'doctor' => __( 'Doctor', 'psmaps' ),
			'electrician' => __( 'Electrician', 'psmaps' ),
			'electronics_store' => __( 'Electronics store', 'psmaps' ),
			'embassy' => __( 'Embassy', 'psmaps' ),
			'establishment' => __( 'Establishment', 'psmaps' ),
			'finance' => __( 'Finance', 'psmaps' ),
			'fire_station' => __( 'Fire station', 'psmaps' ),
			'florist' => __( 'Florist', 'psmaps' ),
			'food' => __( 'Food', 'psmaps' ),
			'funeral_home' => __( 'Funeral home', 'psmaps' ),
			'furniture_store' => __( 'Furniture store', 'psmaps' ),
			'gas_station' => __( 'Gas station', 'psmaps' ),
			'general_contractor' => __( 'General contractor', 'psmaps' ),
			'grocery_or_supermarket' => __( 'Grocery or supermarket', 'psmaps' ),
			'gym' => __( 'Gym', 'psmaps' ),
			'hair_care' => __( 'Hair care', 'psmaps' ),
			'hardware_store' => __( 'Hardware store', 'psmaps' ),
			'health' => __( 'Health', 'psmaps' ),
			'hindu_temple' => __( 'Hindu temple', 'psmaps' ),
			'home_goods_store' => __( 'Home goods store', 'psmaps' ),
			'hospital' => __( 'Hospital', 'psmaps' ),
			'insurance_agency' => __( 'Insurance agency', 'psmaps' ),
			'jewelry_store' => __( 'Jewelry store', 'psmaps' ),
			'laundry' => __( 'Laundry', 'psmaps' ),
			'lawyer' => __( 'Lawyer', 'psmaps' ),
			'library' => __( 'Library', 'psmaps' ),
			'liquor_store' => __( 'Liquor store', 'psmaps' ),
			'local_government_office' => __( 'Local government office', 'psmaps' ),
			'locksmith' => __( 'Locksmith', 'psmaps' ),
			'lodging' => __( 'Lodging', 'psmaps' ),
			'meal_delivery' => __( 'Meal delivery', 'psmaps' ),
			'meal_takeaway' => __( 'Meal takeaway', 'psmaps' ),
			'mosque' => __( 'Mosque', 'psmaps' ),
			'movie_rental' => __( 'Movie rental', 'psmaps' ),
			'movie_theater' => __( 'Movie theater', 'psmaps' ),
			'moving_company' => __( 'Moving company', 'psmaps' ),
			'museum' => __( 'Museum', 'psmaps' ),
			'night_club' => __( 'Night club', 'psmaps' ),
			'painter' => __( 'Painter', 'psmaps' ),
			'park' => __( 'Park', 'psmaps' ),
			'parking' => __( 'Parking', 'psmaps' ),
			'pet_store' => __( 'Pet store', 'psmaps' ),
			'pharmacy' => __( 'Pharmacy', 'psmaps' ),
			'physiotherapist' => __( 'Physiotherapist', 'psmaps' ),
			'place_of_worship' => __( 'Place of worship', 'psmaps' ),
			'plumber' => __( 'Plumber', 'psmaps' ),
			'police' => __( 'Police', 'psmaps' ),
			'post_office' => __( 'Post office', 'psmaps' ),
			'real_estate_agency' => __( 'Real estate agency', 'psmaps' ),
			'restaurant' => __( 'Restaurant', 'psmaps' ),
			'roofing_contractor' => __( 'Roofing contractor', 'psmaps' ),
			'rv_park' => __( 'RV park', 'psmaps' ),
			'school' => __( 'School', 'psmaps' ),
			'shoe_store' => __( 'Shoe store', 'psmaps' ),
			'shopping_mall' => __( 'Shopping mall', 'psmaps' ),
			'spa' => __( 'Spa', 'psmaps' ),
			'stadium' => __( 'Stadium', 'psmaps' ),
			'storage' => __( 'Storage', 'psmaps' ),
			'store' => __( 'Store', 'psmaps' ),
			'subway_station' => __( 'Subway station', 'psmaps' ),
			'synagogue' => __( 'Synagogue', 'psmaps' ),
			'taxi_stand' => __( 'Taxi stand', 'psmaps' ),
			'train_station' => __( 'Train station', 'psmaps' ),
			'travel_agency' => __( 'Travel agency', 'psmaps' ),
			'university' => __( 'University', 'psmaps' ),
			'veterinary_care' => __( 'Veterinary care', 'psmaps' ),
			'zoo' => __( 'Zoo', 'psmaps' ),
		);
		return $options;
	}
}


class Agm_PlacesUserPages {
	private function __construct() {}

	public static function serve() {
		$me = new Agm_PlacesUserPages();
		$me->_add_hooks();
	}

	private function _add_hooks() {
		// UI
		add_action(
			'agm-user-scripts',
			array( $this, 'load_scripts' )
		);
		add_filter(
			'agm-load-options',
			array( $this, 'prepare_for_load' ),
			10, 2
		);
	}

	public function load_scripts() {
		lib3()->ui->add( AGM_PLUGIN_URL . 'js/user/places.min.js', 'front' );
	}

	public function prepare_for_load( $options, $raw ) {
		$options['show_places'] = isset( $raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset( $raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset( $raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}
}

function _agm_places_add_library_support( $data ) {
	$data['libraries'] = $data['libraries'] ? $data['libraries'] : array();
	$data['libraries'][] = 'places';
	return $data;
}
add_filter( 'agm_google_maps-javascript-data_object', '_agm_places_add_library_support' );

if ( is_admin() ) {
	Agm_PlacesAdminPages::serve();
} else {
	Agm_PlacesUserPages::serve();
}