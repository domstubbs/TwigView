<?php
App::uses('HtmlHelper', 'View/Helper');
App::uses('Inflector', 'Utility');

class Twig_Extension_Html extends Twig_Extension {

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
		return 'cake_html';
	}

/**
 * {@inheritDoc}
 */
	public function getFunctions() {
		$html = new HtmlHelper($this->_view);

		return array(
			new Twig_SimpleFunction('html_*', function ($name) use ($html) {
				$arguments = array_slice(func_get_args(), 1);
				$name = Inflector::camelize($name);
				return call_user_func_array(array($html, $name), $arguments);
			}, array('is_safe' => array('html')))
		);
	}
}
