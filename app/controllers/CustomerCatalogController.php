<?php

class CustomerCatalogController extends Controller
{
    use CustomerGuestAccess;   // browsing works without logging in

    protected string $viewBase = 'customer';

    private const SORTS = ['relevance', 'price_asc', 'price_desc', 'name'];

    public function index(): void
    {
        $this->allowGuest();

        $medicineModel = new CustomerMedicine();
        $filters = $this->filters();

        $items = $filters['category'] ? $medicineModel->byCategory($filters['category']) : $medicineModel->all();
        $items = $this->applyFilters($medicineModel, $items, $filters);

        $this->render('catalog.index', [
            'items'          => $items,
            'filters'        => $filters,
            'categories'     => CustomerMedicine::categories(),
            'categoryCounts' => CustomerMedicine::categoryCounts(),
            'brands'         => CustomerMedicine::brands(),
            'priceRange'     => CustomerMedicine::priceRange(),
            'pageTitle'      => 'Pharmacy Catalog',
        ]);
    }

    public function search(): void
    {
        $this->allowGuest();

        $q = $this->scalar('q');
        $medicineModel = new CustomerMedicine();
        $filters = $this->filters();

        $matches = $medicineModel->search($q);

        // Category counts show how many of THESE results sit in each
        // category, so the numbers in the sidebar match what you get.
        $categoryCounts = CustomerMedicine::categoryCounts($matches);

        $items = $filters['category'] ? $medicineModel->inCategory($matches, $filters['category']) : $matches;
        $items = $this->applyFilters($medicineModel, $items, $filters);

        $this->render('catalog.search', [
            'items'          => $items,
            'filters'        => $filters,
            'categories'     => CustomerMedicine::categories(),
            'categoryCounts' => $categoryCounts,
            'brands'         => CustomerMedicine::brands(),
            'priceRange'     => CustomerMedicine::priceRange(),
            'query'          => $q,
            'suggested'      => array_slice($medicineModel->all(), 0, 4),
        ]);
    }

    public function alternate($id): void
    {
        $this->allowGuest();

        $medicineModel = new CustomerMedicine();
        $original = $medicineModel->find((int) $id);

        if (!$original) {
            $this->redirect('/customer/catalog');
            return;
        }

        $this->render('catalog.alternate', [
            'original'   => $original,
            'alternates' => $medicineModel->alternatesFor((int) $id),
        ]);
    }

    /**
     * Every filter on the page, read once and cleaned. The views build all
     * their links and hidden fields from this, so choosing a category, a
     * brand, a price or a sort order never throws the others away.
     */
    private function filters(): array
    {
        $categoryId = (int) $this->scalar('category');
        if (!isset(CustomerMedicine::categories()[$categoryId])) {
            $categoryId = null;
        }

        // Only brands the shop actually has. Anything else is ignored.
        $posted = $this->input('brand', []);
        $posted = is_array($posted) ? $posted : [$posted];
        $posted = array_filter($posted, 'is_string');
        $brands = array_values(array_intersect(CustomerMedicine::brands(), $posted));

        $min = $this->price('min_price');
        $max = $this->price('max_price');
        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];   // typed the wrong way round
        }

        $sort = $this->scalar('sort', 'relevance');
        if (!in_array($sort, self::SORTS, true)) {
            $sort = 'relevance';
        }

        return [
            'category'  => $categoryId,
            'brand'     => $brands,
            'min_price' => $min,
            'max_price' => $max,
            'sort'      => $sort,
        ];
    }

    /** One GET field as trimmed text. Arrays (?q[]=x) are ignored, not warned about. */
    private function scalar(string $key, string $default = ''): string
    {
        $value = $this->input($key, $default);
        return is_scalar($value) ? trim((string) $value) : $default;
    }

    /** A price box: a number of zero or more, or null when left empty. */
    private function price(string $key): ?float
    {
        $value = $this->input($key);
        if (!is_scalar($value) || !is_numeric(trim((string) $value))) {
            return null;
        }
        return max(0.0, (float) $value);
    }

    private function applyFilters(CustomerMedicine $medicineModel, array $items, array $filters): array
    {
        return $medicineModel->filterAndSort(
            $items,
            $filters['brand'],
            $filters['min_price'],
            $filters['max_price'],
            $filters['sort']
        );
    }
}
