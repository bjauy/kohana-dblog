<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * @package    dblog
 * @author     Bastian Bräu
 */
class Controller_DBlog extends Controller
{

	public function before()
	{
		$this->deny_direct_access();
		parent::before();
		if (isset($_GET['log_id']))
		{
			$this->request->action('show');
		}
	}

	public function action_index()
	{
		$logs = ORM::factory('log')->apply_filters($_GET);
		$counter = clone $logs;
		$config = Kohana::$config->load('dblog');

		$pagination = Pagination::factory(array(
			'current_page'   => array('source' => 'query_string', 'key' => 'page'),
			'total_items'    => $counter->count_all(),
			'items_per_page' => $config['pagination']['items_per_page'],
			'view'           => $config['pagination']['view'],
			'auto_hide'      => TRUE,
		));
		$logs = $logs
			->order_by('tstamp', 'DESC')
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->find_all()
			->as_array();
		$filters = $this->get_filters();
		$view = View::factory('dblog/index')
			->bind('logs', $logs)
			->bind('filter_values', $filters)
			->set('filters', Arr::get($_GET, 'log-filter', array()))
			->bind('pagination', $pagination);
		$this->response->body( $view);
	}

	protected function get_filters() {
		return array(
			'type' => $this->get_filter_type(),
		);
	}

	protected function get_filter_type() {
		$config = Kohana::$config->load('dblog');

		$types = DB::select_array(array('type'))
			->distinct(TRUE)
			->from($config['table'])
			->execute()
			->as_array('type', 'type');
		return Arr::merge($types, array('' => __('any')));
	}

	public function action_show()
	{
		$this->response->body( View::factory('dblog/show')
			->set('log', ORM::factory('log', (int) $_GET['log_id']))
		);
	}

	protected function deny_direct_access()
	{
		if ($this->request === Request::initial())
		{
			throw new DBlog_Exception('No direct access allowed');
		}
	}

}
