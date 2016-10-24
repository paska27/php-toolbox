<?php
namespace Paska\Toolbox\PointerBag;

/**
 * Class ReadableBag
 *
 * Handy bag for storing configuration multidimensional array values.
 * Only for reading.
 * Access for values is done by 'chained-keys'.
 * get() method must be called at the end, otherwise ReadableBag object will be returned.
 *
 * Examples:
 *  <code>
 *      // get scalar value
 *      $config = new ReadableBag(array('find' => array('me' => 'hey!')));
 *      $value = $config->find->me->get(); // returns 'hey!'
 *
 *      // get array
 *      // use get() method to get current pointer's value
 *      $value = $config->find; // returns array('me' => 'hey!')
 *  </code>
 */
class ReadableBag implements \IteratorAggregate
{
	/**
	 * Initial data.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Current value at some level.
	 *
	 * @var array
	 */
	protected $pointer;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
		$this->setData($data);
	}

	/**
	 * @param array $data
	 */
	public function setData(array $data) {
		$this->data = $data;
		$this->reset();
	}

	/**
	 * @param array $data
	 */
	public function mergeData(array $data) {
		$this->data = array_replace_recursive($this->data, $data);
		$this->reset();
	}

	/**
	 * @param ReadableBag $bag
	 */
	public function merge(ReadableBag $bag) {
		$this->mergeData($bag->get());
	}

	/**
	 * Returns the value.
	 *
	 * @param mixed $default
	 *
	 * @return ReadableBag|mixed
	 */
	public function get($default = null) {
		return !is_null($value = $this->reset()) ? $value : $default;
	}

	/**
	 * @return ReadableBag
	 */
	public function copy() {
		$copy = clone $this;
		$copy->data = !empty($this->pointer) ? $this->pointer : array();
		$this->reset();
		return $copy;
	}

	/**
	 * @param string $name
	 *
	 * @return ReadableBag|mixed
	 */
	public function __get($name) {
		if (isset($this->pointer[$name])) {
			$this->pointer = $this->pointer[$name];
		} else {
			$this->pointer = null;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string) $this->get();
	}

	/**
	 * @return \ArrayIterator|\Traversable
	 */
	public function getIterator() {
		return new \ArrayIterator($this->data);
	}

	/**
	 * Resets the pointer to initial data.
	 */
	protected function reset() {
		$value = $this->pointer;
		$this->pointer = $this->data;

		return $value;
	}
}
