<?php
/*
 * Catalog / search filter links. Include this at the top of the page, before
 * catalog-filters.php and catalog-toolbar.php.
 *
 * Expects:  $formAction  full URL of the page, e.g. url('/customer/catalog')
 *           $filters     from CustomerCatalogController::filters()
 * Optional: $baseQuery   fields that always stay, e.g. ['q' => 'para']
 *
 * Gives:    $filterUrl(['brand' => [...]])  the current page with those
 *           filters changed and every other filter kept. Pass null to
 *           remove one: $filterUrl(['category' => null]).
 *           $hasFilters  true when anything besides sort is switched on.
 */
$baseQuery = $baseQuery ?? [];

$filterUrl = function (array $changes = []) use ($formAction, $filters, $baseQuery): string {
    $params = array_merge($baseQuery, $filters, $changes);

    // Leave out empty fields and the default sort, so links stay short.
    $params = array_filter($params, function ($value, $key) {
        if ($value === null || $value === '' || $value === []) {
            return false;
        }
        return !($key === 'sort' && $value === 'relevance');
    }, ARRAY_FILTER_USE_BOTH);

    $query = http_build_query($params);
    return $formAction . ($query !== '' ? '?' . $query : '');
};

$hasFilters = $filters['category'] !== null
    || !empty($filters['brand'])
    || $filters['min_price'] !== null
    || $filters['max_price'] !== null;
