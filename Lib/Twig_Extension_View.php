<?php
App::uses('View', 'View');
App::uses('Inflector', 'Utility');

class Twig_Extension_View extends Twig_Extension {

	protected $_view;

/**
 * Constructor
 *
 * @param TwigView $view TwigView context
 */
	public function __construct(TwigView $view) {
		$this->_view = $view;
	}

/**
 * {@inheritDoc}
 */
	public function getName() {
		return 'cake_view';
	}

/**
 * {@inheritDoc}
 */
	public function getFunctions() {
		$rootFunctions = array('element', 'elementExists', 'getVars', 'extend', 'set', 'get', 'blocks');
		$blockFunctions = array('start', 'startIfEmpty', 'end', 'assign', 'fetch', 'append', 'prepend');

		$fns = array();
		foreach ($rootFunctions as $fn) {
			$fns[] = $this->_buildFunction(Inflector::underscore($fn), $fn);
		}
		foreach ($blockFunctions as $fn) {
			$fns[] = $this->_buildFunction('block_' . Inflector::underscore($fn), $fn);
		}

		return $fns;
	}

/**
 * Generate a Twig function
 *
 * @param string $name Function name
 * @param string $callable View method to call
 * @return Twig_SimpleFunction
 */
	protected function _buildFunction($name, $callable) {
		return new Twig_SimpleFunction($name, array($this->_view, $callable), array('is_safe' => array('html')));
	}
}
