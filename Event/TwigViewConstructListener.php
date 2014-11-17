<?php
App::uses('CakeEventListener', 'Event');
App::uses('Twig_Extension_Ago', 'TwigView.Lib');
App::uses('Twig_Extension_Basic', 'TwigView.Lib');
App::uses('Twig_Extension_Form', 'TwigView.Lib');
App::uses('Twig_Extension_Html', 'TwigView.Lib');
App::uses('Twig_Extension_I18n', 'TwigView.Lib');
App::uses('Twig_Extension_Number', 'TwigView.Lib');
App::uses('Twig_Extension_Session', 'TwigView.Lib');
App::uses('Twig_Extension_View', 'TwigView.Lib');

class TwigViewConstructListener implements CakeEventListener {

/**
 * {@inheritDoc}
 */
	public function implementedEvents() {
		return array(
			'TwigView.TwigView.construct' => 'addExtensions'
		);
	}

/**
 * Load Twig extensions
 *
 * @param CakeEvent $event
 * @return void
 */
	public function addExtensions($event) {
		$view = $event->subject;

		$twig = $event->data['Twig'];
		$twig->addExtension(new Twig_Extension_Ago);
		$twig->addExtension(new Twig_Extension_Basic);
		$twig->addExtension(new Twig_Extension_Form($view));
		$twig->addExtension(new Twig_Extension_Html($view));
		$twig->addExtension(new Twig_Extension_I18n);
		$twig->addExtension(new Twig_Extension_Number);
		$twig->addExtension(new Twig_Extension_Session($view));
		$twig->addExtension(new Twig_Extension_View($view));

		if (Configure::read('debug') > 0) {
			$twig->addExtension(new Twig_Extension_Debug);
		}
	}
}