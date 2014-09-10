<?php
App::uses('HtmlHelper', 'View/Helper');
App::uses('Inflector', 'Utility');

class Twig_Extension_Session extends Twig_Extension {

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
		return 'cake_session';
	}

/**
 * {@inheritDoc}
 */
	public function getFunctions() {
		$session = new SessionHelper($this->_view);

		return array(
			new Twig_SimpleFunction('session_read', array($session, 'read')),
			new Twig_SimpleFunction('session_check', array($session, 'check')),
			new Twig_SimpleFunction('session_error', array($session, 'error')),
			new Twig_SimpleFunction('session_valid', array($session, 'valid')),
			new Twig_SimpleFunction('session_flash', array($session, 'flash'), array('is_safe' => array('html'))),
		);
	}
}
