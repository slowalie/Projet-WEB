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
		// Always pass authentication status to templates
		$context['is_authenticated'] = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
		$context['user_role'] = $_SESSION['user_role'] ?? null;
		$context['user_email'] = $_SESSION['user_email'] ?? null;
		
		return $this->twig->render($template, $context);
	}
}
