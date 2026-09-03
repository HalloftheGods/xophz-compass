<?php
/**
 * Trait providing standardized WordPress hook registration.
 * Replaces the redundant WPPB Loader classes across child plugins.
 *
 * @package    Xophz_Compass
 * @subpackage Xophz_Compass/includes/core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Xophz_Compass_Hookable_Trait {

	/**
	 * Storage for queued actions.
	 *
	 * @var array<int, array{hook: string, callback: callable|array|string, priority: int, accepted_args: int}>
	 */
	protected array $registered_actions = array();

	/**
	 * Storage for queued filters.
	 *
	 * @var array<int, array{hook: string, callback: callable|array|string, priority: int, accepted_args: int}>
	 */
	protected array $registered_filters = array();

	/**
	 * Add an action hook.
	 * Supports both standard WordPress callable syntax and 5-parameter WPPB syntax.
	 *
	 * @param string $hook      Hook name.
	 * @param mixed  $component Object instance, class name, or callable callback.
	 * @param mixed  $callback  Method name (if $component is object/class) or priority integer.
	 * @param int    $priority  Hook priority or accepted args. Default 10.
	 * @param int    $accepted_args Number of accepted arguments. Default 1.
	 * @return self
	 */
	public function add_action( string $hook, $component, $callback = null, int $priority = 10, int $accepted_args = 1 ): self {
		if ( null === $callback ) {
			$callback_target = $component;
			$p = 10;
			$a = 1;
		} elseif ( is_int( $callback ) ) {
			$callback_target = $component;
			$p = $callback;
			$a = $priority;
		} else {
			$callback_target = array( $component, $callback );
			$p = $priority;
			$a = $accepted_args;
		}

		$this->registered_actions[] = array(
			'hook'          => $hook,
			'callback'      => $callback_target,
			'priority'      => $p,
			'accepted_args' => $a,
		);
		return $this;
	}

	/**
	 * Add a filter hook.
	 * Supports both standard WordPress callable syntax and 5-parameter WPPB syntax.
	 *
	 * @param string $hook      Hook name.
	 * @param mixed  $component Object instance, class name, or callable callback.
	 * @param mixed  $callback  Method name (if $component is object/class) or priority integer.
	 * @param int    $priority  Hook priority or accepted args. Default 10.
	 * @param int    $accepted_args Number of accepted arguments. Default 1.
	 * @return self
	 */
	public function add_filter( string $hook, $component, $callback = null, int $priority = 10, int $accepted_args = 1 ): self {
		if ( null === $callback ) {
			$callback_target = $component;
			$p = 10;
			$a = 1;
		} elseif ( is_int( $callback ) ) {
			$callback_target = $component;
			$p = $callback;
			$a = $priority;
		} else {
			$callback_target = array( $component, $callback );
			$p = $priority;
			$a = $accepted_args;
		}

		$this->registered_filters[] = array(
			'hook'          => $hook,
			'callback'      => $callback_target,
			'priority'      => $p,
			'accepted_args' => $a,
		);
		return $this;
	}

	/**
	 * Register all queued actions and filters with WordPress.
	 *
	 * @return void
	 */
	public function run_hooks(): void {
		foreach ( $this->registered_filters as $hook ) {
			add_filter(
				$hook['hook'],
				$hook['callback'],
				$hook['priority'],
				$hook['accepted_args']
			);
		}

		foreach ( $this->registered_actions as $hook ) {
			add_action(
				$hook['hook'],
				$hook['callback'],
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}
}
