<?php
/**
 * Plugin Name: Switch Post Status
 * Plugin URI: https://software.gieffeedizioni.it
 * Description: Switch post status from draft to publish and back.
 * Version: 1.0.2
 * Requires PHP: 5.6
 * Requires CP: 1.1
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Author: Gieffe edizioni srl
 * Author URI: https://www.gieffeedizioni.it
 * Text Domain: switch-post-status
 * Domain Path: /languages
 */

namespace XXSimoXX\SwitchPostStatus;

if (!defined('ABSPATH')) {
	die('-1');
}

// Add auto updater.
require_once('classes/UpdateClient.class.php');

class SwitchPostStatus{

	const ACTION = 'xsxswitch';

	private $lookup = [];

	public function __construct() {

		// Load text domain.
		add_action('plugins_loaded', [$this, 'text_domain']);

		// Hook the switch admin action.
		add_action('admin_action_'.$this::ACTION, [$this, 'switch_to']);

		// Inject action links.
		add_filter('post_row_actions', [$this, 'action_link'], 10, 2);
		add_filter('page_row_actions', [$this, 'action_link'], 10, 2);

	}

	public function text_domain() {
		load_plugin_textdomain('switch-post-status', false, basename(dirname(__FILE__)).'/languages');
		// Get defaults here so they can be translated.
		$this->get_defaults();
	}

	public function get_defaults() {
		// Fill the array containing default behaviour.
		// Can be changed using xsxswitch_lookup filter.
		$this->lookup = [
			'draft' => [
				'dst' => 'publish',
				'msg' => esc_html__('Switch to publish', 'switch-post-status'),
			],
			'publish' => [
				'dst' => 'draft',
				'msg' => esc_html__('Switch to draft', 'switch-post-status'),
			],
		];
	}

	public function action_link($actions, $post) {

		// If user can't edit items, bail.
		if (!current_user_can('publish_posts', $post->ID)) {
			return $actions;
		}

		// Filter values of messages and destinations.
		$lookup = apply_filters($this::ACTION.'_lookup', $this->lookup);

		// Post status not switchable, bail.
		if (!in_array($post->post_status, array_keys($lookup))) {
			return $actions;
		}

		// Add action link.
		$message = wp_kses($lookup[$post->post_status]['msg'], []);
		$actions[$this::ACTION] = sprintf(
			'<a href="%s" rel="permalink">%s</a>',
			wp_nonce_url(admin_url('admin.php?action='.$this::ACTION.'&post='.$post->ID), $this::ACTION, $this::ACTION),
			$message
		);

		return $actions;

	}

	public function switch_to() {

		// Nonce error, bail.
		if (!wp_verify_nonce(sanitize_key(wp_unslash($_REQUEST[$this::ACTION])), $this::ACTION)) {
			wp_die(esc_html__('Nonce error detected.', 'switch-post-status'));
		}

		// If no item id is present, bail.
		if (!isset($_GET['post'])) {
			wp_die(esc_html__('Post ID not specified.', 'switch-post-status'));
		}

		// If user can't edit items, bail.
		$post = (isset($_GET['post']) ? (int)$_GET['post'] : (int)$_POST['post']);
		if (!current_user_can('publish_posts', $post)) {
			wp_die(esc_html__('A higher level of permission is required to perform this action.', 'switch-post-status'));
		}

		// Get post status.
		$status = get_post_status($post);
		if ($status === false) {
			wp_die(esc_html__('Can\'t get post status.', 'switch-post-status'));
		}

		// Filter values of messages and destinations.
		$lookup = apply_filters($this::ACTION.'_lookup', $this->lookup);

		// Post status not switchable, bail.
		if (!in_array($status, array_keys($lookup)) || !isset($lookup[$status]['dst'])) {
			wp_die(esc_html__('Can\'t switch post status.', 'switch-post-status'));
		}

		// Update post status.
		$new_status = $lookup[$status]['dst'];
		wp_update_post(['ID' => $post, 'post_status' => $new_status]);

		// Go back to edit.
		wp_safe_redirect(wp_unslash($_SERVER['HTTP_REFERER']));
		exit;

	}

}

new SwitchPostStatus;
