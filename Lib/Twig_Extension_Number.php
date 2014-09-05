<?php

App::uses('CakeNumber', 'Utility');

/**
 * Twig_Extension_Number
 *
 * Use: {{ '3535839525'|toReadableSize }} //=> 3.29 GB
 * Use: {{ '0.555'|precision(2) }} //=> 0.56
 * Use: {{ '5999'|currency }} //=> $5,999.00
 * Use: {{ '2.3'|toPercentage }} //=> 2.30%
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @package TwigView
 * @subpackage TwigView.Lib
 */
class Twig_Extension_Number extends Twig_Extension {

	/**
	 * Get declared filters
	 *
	 * @return array Twig_SimpleFilters
	 */
	public function getFilters() {
		return array(
			new Twig_SimpleFilter('precision', 'CakeNumber::precision'),
			new Twig_SimpleFilter('toReadableSize', 'CakeNumber::toReadableSize'),
			new Twig_SimpleFilter('fromReadableSize', 'CakeNumber::fromReadableSize'),
			new Twig_SimpleFilter('toPercentage', 'CakeNumber::toPercentage'),
			new Twig_SimpleFilter('format', 'CakeNumber::format'),
			new Twig_SimpleFilter('formatDelta', 'CakeNumber::formatDelta'),
			new Twig_SimpleFilter('currency', 'CakeNumber::currency'),
		);
	}

	/**
	 * Get declared functions
	 *
	 * @return array Twig_SimpleFunctions
	 */
	public function getFunctions() {
		return array(
			new Twig_SimpleFunction('defaultCurrency', 'CakeNumber::defaultCurrency')
		);
	}

/**
 * Returns the name of the extension.
 *
 * @return string The extension name
 */
	public function getName() {
		return 'number';
	}
}
