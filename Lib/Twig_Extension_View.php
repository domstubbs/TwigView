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
		$view = $this->_view;

		return array(
			new Twig_SimpleFunction('assign', array($view, 'assign'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('element', array($view, 'element'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('fetch', array($view, 'fetch'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('block_start', array($view, 'start'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('block_end', array($view, 'end'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('append', array($view, 'append'), array('is_safe' => array('html'))),
			new Twig_SimpleFunction('view_*', function ($name) use ($view) {
				$arguments = array_slice(func_get_args(), 1);
				$name = Inflector::camelize($name);
				return call_user_func_array(array($view, $name), $arguments);
			}, array('is_safe' => array('html')))
		);
	}
}
