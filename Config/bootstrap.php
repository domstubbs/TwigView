<?php

Configure::write('TwigView.cache',  CakePlugin::path('TwigView') . 'tmp' . DS . 'views');

App::uses('CakeEventManager', 'Event');
App::uses('TwigViewConstructListener', 'TwigView.Event');

CakeEventManager::instance()->attach(new TwigViewConstructListener());