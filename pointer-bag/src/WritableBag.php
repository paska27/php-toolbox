<?php
namespace Zicket\Library\PointerBag;

/**
 * Class WritableBag
 *
 * Handy bag for storing configuration arrays.
 *
 * Values can be overwritten.
 *
 * Example:
 * <code>
 *  $config = new WritableBag(array('find' => array('me' => 'Sergey')));
 *  $config->find->me->get();// gives 'Sergey'
 *
 *  // overwrite
 *  $config->find->me->set('Serg');
 *  $config->find->me->get();// gives 'Serg'
 *
 *  // new values can be set as well
 *  $config->her->set('Jessica');
 * </code>
 *
 * @package Oxynade\Library\PointerBag
 */
class WritableBag extends ReadableBag {
	/**
	 * Keeps track of current multidimensional array's path.
	 *
	 * @var string
	 */
	private $path = '';

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		parent::__get($name);

		// keep track of 'nodes' to create
		$this->path .= '.' . $name;

		return $this;
	}

	/**
	 * Sets the values for current path.
	 *
	 * @param mixed $value
	 */
	public function set($value) {
		$ref =& $this->data;
		$keys = explode('.', ltrim($this->path, '.'));
		// delete first key, so $a can nest references
		//TODO: probably introduce 'full-rewrite' mode in order to prevent full rewriting
		!array_key_exists($f = current($keys), $this->data) ?: $this->data[$f] = null;
		while ($k = array_shift($keys)) {
			$ref =& $ref[$k];
		}
		$ref = $value;

		$this->pointer = $this->data;
		$this->reset();
	}

	/**
	 * Adds value to the stack.
	 *
	 * @param $value
	 */
	public function add($value) {
		$stack = ($stack = $this->pointer) ? $stack : array();
		array_push($stack, $value);

		$this->set($stack);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function reset() {
		$this->path = '';

		return parent::reset();
	}
}