<?php
App::uses('CakeEventListener', 'Event');
App::uses('Twig_Extension_Ago', 'TwigView.Lib');
App::uses('Twig_Extension_Basic', 'TwigView.Lib');
App::uses('Twig_Extension_Number', 'TwigView.Lib');

class TwigViewConstructListener implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'TwigView.TwigView.construct' => 'addExtensions'
		);
	}

	/**
	 * Load Twig extensions
	 * @param  CakeEvent $event
	 */
	public function addExtensions($event) {
		$twig = $event->data['Twig'];
		$twig->addExtension(new Twig_Extension_Ago);
		$twig->addExtension(new Twig_Extension_Basic);
		$twig->addExtension(new Twig_Extension_Number);

		if (Configure::read('debug') > 0) {
			$twig->addExtension(new Twig_Extension_Debug);
		}
	}
}