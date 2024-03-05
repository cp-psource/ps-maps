<?php
/**
 * The new contextual help API helper.
 *
 * Example usage:
 * <code>
 * $help = new PSOURCE_ContextualHelper();
 * $help->add_page(
 *		'dashboard',
 *		array(
 *			array(
 *				'id' => 'myid',
 *				'title' => __('My title', 'textdomain'),
 *				'content' => '<p>' . __('My content lalala', 'textdomain') . '</p>',
 *			),
 *		),
 *		'<p>My awesome sidebar!</p>',
 *		false // Don't clear existing items.
 *	);
 * $help->initialize();
 * </code>
 */

 class PSOURCE_ContextualHelp {

    private $_pages = array();

    private $_auto_clear_wp_help = false;

    public function __construct() {}

    public function add_page($screen_id, $tabs = array(), $sidebar = '', $clear = false) {
        if (!is_array($tabs)) return false;
        $this->_pages[$screen_id] = array(
            'tabs' => $tabs,
            'sidebar' => $sidebar,
            'clear' => $clear,
        );
    }

    public function remove_page($screen_id) {
        unset($this->_pages[$screen_id]);
    }

    public function add_tab($screen_id, $tab = array()) {
        if (!is_array($tab)) return false;
        $this->_pages[$screen_id]['tabs'][] = $tab;
    }

    public function remove_tab($screen_id, $tab_id) {
        if (!$tab_id) return false;

        $tabs = @$this->_pages[$screen_id]['tabs'];
        if (!$tabs) return false;

        $tmp = array();
        foreach ($tabs as $tab) {
            if (@$tab['id'] == $tab_id) continue;
            $tmp[] = $tab;
        }
        $this->_pages[$screen_id]['tabs'] = $tmp;
    }

    public function clear_wp_help() {
        $this->_auto_clear_wp_help = true;
    }

    public function initialize() {
        $this->_add_hooks();
    }

    private function _add_hooks() {
        if (version_compare($GLOBALS['wp_version'], '3.3', '>=')) {
            add_action('admin_head', array($this, 'add_contextual_help'), 999);
        } else {
            add_filter('contextual_help', array($this, 'add_compatibility_contextual_help'), 1, 1);
        }
    }

    public function add_contextual_help() {
        $screen = get_current_screen();
        if (!is_object($screen)) return false;

        $screen_id = $screen->id;
        if (!isset($this->_pages[$screen_id]) || !@$this->_pages[$screen_id] || !@$this->_pages[$screen_id]['tabs']) return false;
        $info = $this->_pages[$screen_id];

        $clear = (@$info['clear'] || $this->_auto_clear_wp_help);
        if ($clear) $screen->remove_help_tabs();

        $screen->set_help_sidebar(@$info['sidebar']);

        foreach ($info['tabs'] as $tab) {
            $screen->add_help_tab($tab);
        }
    }

    public function add_compatibility_contextual_help($help) {
        $screen = get_current_screen();
        if (!is_object($screen)) return $help;

        $screen_id = $screen->id;
        if (!@$this->_pages[$screen_id] || !@$this->_pages[$screen_id]['tabs']) return $help;
        $info = $this->_pages[$screen_id];

        $clear = (@$info['clear'] || $this->_auto_clear_wp_help);
        if ($clear) $help = '';

        foreach ($info['tabs'] as $tab) {
            $help .= '<h3>' . $tab['title'] . '</h3>' . $tab['content'];
        }

        return $help;
    }
}