<?php

use \Illuminate\Container\Container;
use \BuglerV\Blade\BladeBootstrap;

if( !function_exists('app') ) {
	/**
	 * Возвращает экземпляр контейнера или конкретный элемент, если задан.
	 *
	 * @param string $elem
	 * @return \Illuminate\Container\Container|mixed
	 */
	function app($elem = null)
	{
		$container = Container::getInstance();

		if(!$elem){
			return $container;
		}

		return $container->get($elem);
	}
}

if( !function_exists('blade') ) {
	/**
	 * Возвращает экземпляр Blade или конкретный View, если задан.
	 *
	 * @param string $view
	 * @param array $data
	 * @param array $mergeData
	 * @return mixed
	 */
	function blade($view = null, $data = [], $mergeData = [])
	{
		if(!app()->bound('blade')) {
			BladeBootstrap::boot();
		}

		$blade = app('blade');

		if(!$view){
			return $blade;
		}
	
		return $blade->render($view, $data, $mergeData);
	}
}