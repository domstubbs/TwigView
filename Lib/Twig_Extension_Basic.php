<?php
/**
 * CakePHP Basic functions
 *
 * Use: {{ user|debug }}
 * Use: {{ user|pr }}
 * Use: {{ 'FOO'|low }}
 * Use: {{ 'foo'|up }}
 * Use: {{ 'HTTP_HOST'|env }}
 *
 * @author Hiroshi Hoaki <rewish.org@gmail.com>
 * @package TwigView
 * @subpackage TwigView.Lib
 */
class Twig_Extension_Basic extends Twig_Extension {

/**
 * Returns a list of filters to add to the existing list.
 *
 * @return array An array of filters
 */
	public function getFilters() {
		return array(
			new Twig_SimpleFilter('debug', 'debug'),
			new Twig_SimpleFilter('pr', 'pr'),
			new Twig_SimpleFilter('env', 'env')
		);
	}

/**
 * Returns the name of the extension.
 *
 * @return string The extension name
 */
	public function getName() {
		return 'basic';
	}
}
