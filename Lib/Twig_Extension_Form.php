<?php
App::uses('FormHelper', 'View/Helper');
App::uses('Inflector', 'Utility');

class Twig_Extension_Form extends Twig_Extension {

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
		return 'cake_form';
	}

/**
 * {@inheritDoc}
 */
	public function getFunctions() {
		$form = new FormHelper($this->_view);

		return array(
			new Twig_SimpleFunction('form_*', function ($name) use ($form) {
				$arguments = array_slice(func_get_args(), 1);
				$name = Inflector::camelize($name);
				return call_user_func_array(array($form, $name), $arguments);
			}, array('is_safe' => array('html')))
		);
	}
}
