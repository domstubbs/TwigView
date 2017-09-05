<?php
App::uses('PaginatorHelper', 'View/Helper');
App::uses('Inflector', 'Utility');

class Twig_Extension_Paginator extends Twig_Extension {

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
		return 'cake_paginator';
	}

/**
 * {@inheritDoc}
 */
	public function getFunctions() {
		$paginator = new PaginatorHelper($this->_view);

		return array(
			new Twig_SimpleFunction('paginator_*', function ($name) use ($paginator) {
				$arguments = array_slice(func_get_args(), 1);
				$name = Inflector::camelize($name);
				return call_user_func_array(array($paginator, $name), $arguments);
			}, array('is_safe' => array('html')))
		);
	}
}
