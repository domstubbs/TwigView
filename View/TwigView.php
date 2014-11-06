<?php
/**
 * TwigView for CakePHP
 *
 * About Twig
 *  http://www.twig-project.org/
 *
 * @version 0.5
 * @package TwigView
 * @subpackage TwigView.View
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @link http://github.com/m3nt0r My GitHub
 * @link http://twitter.com/m3nt0r My Twitter
 * @author Graham Weldon (http://grahamweldon.com)
 * @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('CakeEvent', 'Event');

$twigPath = CakePlugin::path('TwigView');

/**
 * TwigView for CakePHP
 *
 * @version 0.5
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @link http://github.com/m3nt0r/cakephp-twig-view GitHub
 * @package app.views
 * @subpackage app.views.twig
 */
class TwigView extends View {

	const EXT = '.tpl';

/**
 * File extension
 *
 * @var string
 */
	public $ext = self::EXT;

/**
 * Twig Environment Instance
 *
 * @var Twig_Environment
 */
	protected $_twig;

/**
 * Collection of paths.
 * These are stripped from $___viewFn.
 *
 * @todo overwrite getFilename()
 * @var array
 */
	protected $_templatePaths = array();

/**
 * Twig settings
 *
 * @var array
 */
	protected $_settings = array(
		'cache'               => null,
		'charset'             => null,
		'auto_reload'         => null,
		'debug'               => null,
		'autoescape'          => 'html',
		'optimizations'       => -1,
		'base_template_class' => 'Twig_Template',
		'strict_variables'    => false
	);

/**
 * Constructor
 * Overridden to provide Twig loading
 *
 * @param Controller $Controller Controller
 */
	public function __construct(Controller $Controller = null) {
		parent::__construct($Controller);

		$this->ext = self::EXT;
		$this->_templatePaths = App::path('View');

		$this->_settings = Hash::merge($this->_settings, array(
			'cache'       => CakePlugin::path('TwigView') . 'tmp' . DS . 'views',
			'charset'     => strtolower(Configure::read('App.encoding')),
			'auto_reload' => Configure::read('debug') > 0,
			'debug'       => Configure::read('debug') > 0,
		), Configure::read('TwigView.options'));

		$this->_twig = new Twig_Environment($this->_getLoader(), $this->_settings);

		CakeEventManager::instance()->dispatch(new CakeEvent('TwigView.TwigView.construct', $this, array(
			'Twig' => $this->_twig
		)));

		if (isset($Controller->theme)) {
			$this->theme = $Controller->theme;
		}
	}

/**
 * Get loader
 *
 * @return Twig_Loader_Filesystem
 */
	protected function _getLoader() {
		$event = new CakeEvent('TwigView.TwigView.loader', $this, array(
			'loader' => new Twig_Loader_Filesystem($this->_templatePaths[0])
		));
		CakeEventManager::instance()->dispatch($event);

		if (isset($event->result['loader'])) {
			return $event->result['loader'];
		}

		return $event->data['loader'];
	}

/**
 * Render the view
 *
 * @param string $_viewFn Filename of the view
 * @param string $_dataForView Data to include in the rendered view
 * @return void
 */
	protected function _render($_viewFn, $_dataForView = array()) {
		$isCtpFile = (substr($_viewFn, -3) === 'ctp');

		if (empty($_dataForView)) {
			$_dataForView = $this->viewVars;
		}

		if ($isCtpFile) {
			return parent::_render($_viewFn, $_dataForView);
		}

		ob_start();

		// Setup the helpers from the new Helper Collection
		$helpers = array();

		// Disable automatic helper loading for now - we'll flesh out extensions as necessary
		// $loaded_helpers = $this->Helpers->loaded();

		// foreach ($loaded_helpers as $helper) {
		// 	$name = Inflector::variable($helper);
		// 	$helpers[$name] = $this->loadHelper($helper);
		// }

		if (!isset($_dataForView['cakeDebug'])) {
			$_dataForView['cakeDebug'] = null;
		}

		$data = array_merge($_dataForView, $helpers);
		$data['_view'] = $this;
		$relativeFn = str_replace($this->_templatePaths, '', $_viewFn);
		echo $this->_twig->loadTemplate($relativeFn)->render($data);
		return ob_get_clean();
	}

/**
 * Get Twig instance
 *
 * @return Twig_Environment
 */
	public function getTwig() {
		return $this->_twig;
	}
}
