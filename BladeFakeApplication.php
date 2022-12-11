<?php

namespace BuglerV\Blade;

class BladeFakeApplication
{
	public function getNamespace()
	{
		return app('config')->get('view.app');
	}
}
