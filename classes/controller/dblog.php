<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @package    Kohana/dblog
 * @author     Bastian Bräu
 */
class Controller_DBlog extends Controller {

	protected $model;
	protected $requestedLogId;

	public function before() {
		$this->redirectIfLogIdPresent();
		$this->denyDirectAccess();
	}

	protected function redirectIfLogIdPresent() {
		if (isset($_GET['log_id'])) {
			$this->requestedLogId = (int) $_GET['log_id'];
			if ($this->requestedLogId > 0) {
				$this->request->action = 'show';
			}
		}
	}

	/**
	* @todo implement sorting and filtering
	*/
	public function action_index() {
		$logEntries = Model_DBlog_Entry::factory();
		$pagination = Pagination::factory(array(
			'total_items' => $logEntries->count_all(),
			'items_per_page' => 20,
			'auto_hide' => TRUE,
		));
// 		strtr($pagination->render(), array(
// 			Request::current()->uri => Request::$instance->uri,
// 		));
		$this->request->response = View::factory('dblog/index', array(
			'pagination' => $pagination, // Passing $pagination->render() to the view will lead to wrong urls!
										 // Why does this even work? Just echoing the Pagination object in the view!
			'logs' => $logEntries->limit($pagination->items_per_page)->offset($pagination->offset)->find_all()->as_array(),
		));
	}

	public function action_show() {
		$this->request->response = View::factory('dblog/show', array(
			'log' => Model_DBlog_Entry::factory($this->requestedLogId),
		));
	}

	protected function denyDirectAccess() {
		if ($this->request === Request::instance())
			$this->request->action = 'block';
	}

	public function action_block() {
		$this->request->status = 403;
		$this->request->response = '403 Forbidden';
	}

}