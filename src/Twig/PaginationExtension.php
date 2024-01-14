<?php
namespace App\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationExtension extends AbstractExtension
{

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly RouterInterface $router
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [$this, 'generatePagination'], ['is_safe' => ['html']])
        ];
    }

    public function generatePagination(int $totalRows, int $limit, array $additionalRouteParams = []): string
    {
        $totalPages = ceil($totalRows / $limit);
        $request = $this->requestStack->getCurrentRequest();
        $currentRoute = $request->attributes->get('_route');
        $currentPage = $request->query->getInt('page', 1);
        $queryParams = array_filter(
            $request->query->all(),
            function ($key) {
                return $key !== 'page';
            },
            ARRAY_FILTER_USE_KEY
        );

        $range = 1;
        $paginationHtml = '<nav class="pagination is-centered" role="navigation" aria-label="pagination">';

        $paginationHtml .= '<ul class="pagination-list">';

        if ($totalPages < 6) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHtml .= $this->generatePageLink($currentRoute, $queryParams, $i, (string)$i, $i == $currentPage, $additionalRouteParams);
            }
        } else {
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
                    $paginationHtml .= $this->generatePageLink($currentRoute, $queryParams, $i, (string)$i, $i == $currentPage, $additionalRouteParams);
                } elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1) {
                    $paginationHtml .= '<li><span class="pagination-ellipsis">&hellip;</span></li>';
                }
            }
        }

        $paginationHtml .= '</ul>';

        $paginationHtml .= '</nav>';

        return $paginationHtml;
    }

    private function generatePageLink(string $route, array $queryParams, int $page, string $label, bool $isActive = false, array $additionalRouteParams = [])
    {
        $queryParams['page'] = $page;
        $parameters = array_merge($queryParams, $additionalRouteParams);
        $url = $this->router->generate($route, $parameters);

        $activeClass = $isActive ? ' is-current' : '';
        return "<li><a href=\"{$url}\" class=\"pagination-link{$activeClass}\" aria-label=\"Goto page {$page}\">{$label}</a></li>";
    }
}
