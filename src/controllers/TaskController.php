<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

class TaskController extends Controller
{
	public function index(): string
	{
		return $this->render('home.twig.html', [
			'page' => 'tasks'
		]);
	}
}
