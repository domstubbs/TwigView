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
 * Render the view, plus any parent/extended views
 *
 * @param string $viewFile Filename of the view
 * @param array $data Data to include in rendered view. If empty the current View::$viewVars will be used.
 * @return string Rendered output
 * @throws CakeException when a block is left open.
 */
	protected function _render($viewFile, $data = array()) {
		if (empty($data)) {
			$data = $this->viewVars;
		}

		$isCtpFile = (substr($viewFile, -3) === 'ctp');

		if ($isCtpFile) {
			return parent::_render($viewFile, $data);
		}

		$this->_current = $viewFile;
		$initialBlocks = count($this->Blocks->unclosed());

		$eventManager = $this->getEventManager();
		$beforeEvent = new CakeEvent('View.beforeRenderFile', $this, array($viewFile));

		$eventManager->dispatch($beforeEvent);

		$data['_view'] = $this;
		$content = $this->_twig->loadTemplate(str_replace($this->_templatePaths, '', $viewFile))->render($data);

		$afterEvent = new CakeEvent('View.afterRenderFile', $this, array($viewFile, $content));

		$afterEvent->modParams = 1;
		$eventManager->dispatch($afterEvent);
		$content = $afterEvent->data[1];

		if (isset($this->_parents[$viewFile])) {
			$this->_stack[] = $this->fetch('content');
			$this->assign('content', $content);

			$content = $this->_render($this->_parents[$viewFile]);
			$this->assign('content', array_pop($this->_stack));
		}

		$remainingBlocks = count($this->Blocks->unclosed());

		if ($initialBlocks !== $remainingBlocks) {
			throw new CakeException(__d('cake_dev', 'The "%s" block was left open. Blocks are not allowed to cross files.', $this->Blocks->active()));
		}

		return $content;
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
