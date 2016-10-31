<?php
/**
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Modified to use CakePHP functions
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @package TwigView
 * @subpackage TwigView.Lib
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_Extension_I18n extends Twig_Extension {

/**
 * Returns a list of filters to add to the existing list.
 *
 * @return array An array of filters
 */
	public function getFilters() {
		return array(
			new Twig_SimpleFilter('trans', '__'),
			new Twig_SimpleFilter('c', '__c'),
			new Twig_SimpleFilter('d', '__d'),
			new Twig_SimpleFilter('dc', '__dc'),
			new Twig_SimpleFilter('n', '__n')
		);
	}

/**
 * Returns the name of the extension.
 *
 * @return string The extension name
 */
	public function getName() {
		return 'i18n';
	}
}
