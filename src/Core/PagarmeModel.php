<?php namespace Pagarme\Core;

class PagarmeModel extends PagarmeObject {
	protected static $root_url;

	public function __construct($response = array()) {
		parent::__construct($response);
	}

	public static function getUrl() {
		$class = get_called_class();
		// $search = preg_match("/PagarMe_(.*)/",$class, $matches);
		$search = explode('\\', $class);
		return '/'. strtolower(end($search)) . 's';
	}

	public function create() {
		try {
			$request = new PagarmeRequest(self::getUrl(), 'POST');
			$parameters = $this->__toArray(true);
			$request->setParameters($parameters);
			$response = $request->run();
			return $this->refresh($response);
		} catch(Exception $e) {
			throw new PagarmeException($e->getMessage());
		}
	}

	public function save()
	{
		try {
			if(method_exists(get_called_class(), 'validate')) {
				if(!$this->validate()) return false;
			}
			$request = new PagarmeRequest(self::getUrl(). '/' . $this->id, 'PUT');
			$parameters = $this->unsavedArray();
			$request->setParameters($parameters);
			$response = $request->run();
			return $this->refresh($response);
		} catch(Exception $e) {
			throw new PagarmeException($e->getMessage());
		}
	}

	public static function findById($id)
	{
		$request = new PagarmeRequest(self::getUrl() . '/' . $id, 'GET');
		$response = $request->run();
		$class = get_called_class();
		return new $class($response);
	}

	public static function all($page = 1, $count = 10)
	{
		$request = new PagarmeRequest(self::getUrl(), 'GET');
		$request->setParameters(array("page" => $page, "count" => $count));
		$response = $request->run();
		$return_array = Array();
		$class = get_called_class();
		foreach($response as $r) {
			$return_array[] = new $class($r);
		}

		return $return_array;
	}
}
