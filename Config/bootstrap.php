<?php

App::uses('CakeEventManager', 'Event');
App::uses('TwigViewConstructListener', 'TwigView.Event');

CakeEventManager::instance()->attach(new TwigViewConstructListener());