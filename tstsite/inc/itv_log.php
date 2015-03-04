<?php

class ItvLog {
	public static $ACTION_TASK_CREATE = 'create';
	public static $ACTION_TASK_DELETE = 'delete';
	public static $ACTION_TASK_EDIT = 'edit';
	public static $ACTION_TASK_ADD_CANDIDATE = 'add_candidate';
	public static $ACTION_TASK_REFUSE_CANDIDATE = 'refuse_candidate';
	public static $ACTION_TASK_APPROVE_CANDIDATE = 'approve_candidate';
	public static $ACTION_TASK_REMOVE_CANDIDATE = 'remove_candidate';
	public static $ACTION_TASK_PUBLISH = 'publish';
	public static $ACTION_TASK_UNPUBLISH = 'unpublish';
	public static $ACTION_TASK_INWORK = 'inwork';
	public static $ACTION_TASK_CLOSE = 'close';
	
	private $task_action_table;
	private static $_instance = NULL;
	
	function __construct() {
		global $wpdb;
		$this->task_action_table = $wpdb->prefix.'itv_task_actions_log';
	}
	
	public static function instance() {
		if(ItvLog::$_instance == NULL) {
			ItvLog::$_instance = new ItvLog();
		}
		return ItvLog::$_instance;
	}
	
	public function log_task_action($task_id, $action, $action_assoc_user_id = 0) {
		global $wpdb;
		
		$task = get_post($task_id);
		
		$wpdb->query(
				$wpdb->prepare(
						"
						INSERT INTO $this->task_action_table
						SET task_id = %d, action = %s, assoc_user_id = %d, action_time = NOW(), task_status = %s
						",
						$task_id, $action, $action_assoc_user_id, $task->post_status
				)
		);
	}
	
	public function get_task_log($task_id) {
		global $wpdb;
		
		$actions = $wpdb->get_results(
				$wpdb->prepare(
						"
						SELECT * FROM $this->task_action_table
						WHERE task_id = %d
						ORDER BY action_time DESC
						",
						$task_id
				)
		);
		
		return $actions;		
	}
	
	public function get_all_tasks_log($offset = 0, $limit = 5) {
		global $wpdb;
		
		$offset = (int)$offset;
		$limit = (int)$limit;
		
		$actions = $wpdb->get_results(
			"
			SELECT * FROM $this->task_action_table
			WHERE 1
			ORDER BY action_time DESC
			LIMIT $offset, $limit
			"
		);
		
		return $actions;
	}
	
	public function get_all_tasks_log_records_count() {
		global $wpdb;
		
		$actions = $wpdb->get_var(
				"
				SELECT COUNT(*) FROM $this->task_action_table
				"
		);
		
		return $actions;
	}
	
	public function humanize_action($action, $user_text) {
		return sprintf(__('itv_task_actions_log_'.$action, 'tst'), $user_text);
	}
}

__('itv_task_actions_log_create', 'tst');
__('itv_task_actions_log_delete', 'tst');
__('itv_task_actions_log_edit', 'tst');
__('itv_task_actions_log_add_candidate', 'tst');
__('itv_task_actions_log_refuse_candidate', 'tst');
__('itv_task_actions_log_approve_candidate', 'tst');
__('itv_task_actions_log_remove_candidate', 'tst');
__('itv_task_actions_log_publish', 'tst');
__('itv_task_actions_log_unpublish', 'tst');
__('itv_task_actions_log_inwork', 'tst');
__('itv_task_actions_log_close', 'tst');