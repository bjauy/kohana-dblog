<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Database log writer.
 *
 * @package    dblog
 * @author     Bastian Bräu
 */
class DBlog_Writer extends Log_Writer
{


	/**
	 * @param   array messages
	 * @return  void
	 */
	public function write(array $messages)
	{
		foreach ($messages as & $message)
		{
			DBlog::add_kohana_message( 
				(!empty($message['type']) ? $message['type'] : (!empty($this->_log_levels[$message['level']]) ? $this->_log_levels[$message['level']] : '')), 
				$message['body']
			);
		}
	}

}
