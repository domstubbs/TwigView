<?php
App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('View', 'View');
App::uses('TwigView', 'TwigView.View');

/**
 * TwigView shell.
 *
 */
class TwigViewShell extends AppShell {

/**
 * Override startup method to disable the welcome message
 *
 * @return void
 */
	public function startup() {}

/**
 * Compile all Twig templates
 *
 * @return void
 */
	public function compileAll() {
		$paths = App::path('View');
		$twigView = new TwigView();

		$this->out('<info>Compiling Twig templates</info>');
		$this->out('');

		foreach ($paths as $dir) {
			$folder = new Folder($dir);

			try {
				foreach ($folder->findRecursive('.*' . preg_quote($twigView->ext) . '$') as $template) {
					$relativePath = str_replace($paths, '', $template);
					$twigView->getTwig()->loadTemplate($relativePath);
					$this->out(' - ' . $relativePath);
				}
			} catch (Exception $e) {
				$this->out(' - <error>' . $e->getMessage() . '</error>');
			}
		}
	}
}