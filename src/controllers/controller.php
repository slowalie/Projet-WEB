<?php

declare(strict_types=1);

use Twig\Environment;

abstract class Controller
{
	protected Environment $twig;

	public function __construct(Environment $twig)
	{
		$this->twig = $twig;
	}

	protected function render(string $template, array $context = [])
	{
		$context['is_authenticated'] = !empty($_SESSION['is_authenticated']);
		$context['user_role'] = $_SESSION['user_role'] ?? null;
		return $this->twig->render($template, $context);
	}
}
