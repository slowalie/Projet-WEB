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

	protected function isAuthenticated(): bool
	{
		return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
	}

	protected function getCurrentRole(): ?string
	{
		return isset($_SESSION['user_role']) ? (string) $_SESSION['user_role'] : null;
	}

	protected function requireAuth(): void
	{
		if (!$this->isAuthenticated()) {
			$this->redirectUnauthorized('login_required');
		}
	}

	protected function requireRole(array|string $roles): void
	{
		$this->requireAuth();

		$allowedRoles = is_array($roles) ? $roles : [$roles];
		$currentRole = $this->getCurrentRole();

		if ($currentRole === null || !in_array($currentRole, $allowedRoles, true)) {
			$this->redirectUnauthorized('access_denied');
		}
	}

	protected function redirectUnauthorized(string $status = 'access_denied'): void
	{
		header('Location: /home?auth_status=' . urlencode($status));
		exit;
	}
}
