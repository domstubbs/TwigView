<?php

App::uses('TimeHelper', 'View/Helper');

/**
 * Time Ago in Words
 * Use: {{ user.User.created|ago }} 
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @package TwigView
 * @subpackage TwigView.Lib
 */
class Twig_Extension_Ago extends Twig_Extension {

/**
 * Constructor
 *
 * @param TwigView $view TwigView context
 */
	public function __construct(TwigView $view) {
		$this->_view = $view;
	}

/**
 * Returns a list of filters to add to the existing list.
 *
 * @return array An array of filters
 */
	public function getFilters() {
		return array(
			new Twig_SimpleFilter('ago', array(new TimeHelper($this->_view), 'timeAgoInWords'))
		);
	}

/**
 * Returns the name of the extension.
 *
 * @return string The extension name
 */
	public function getName() {
		return 'ago';
	}
}
