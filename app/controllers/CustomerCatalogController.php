<?php

class CustomerCatalogController extends Controller
{
    use CustomerGuestAccess;   // browsing works without logging in

    protected string $viewBase = 'customer';

    public function index(): void
    {
        $this->allowGuest();

        $medicineModel = new CustomerMedicine();
        $categoryId = $this->input('category');
        $categoryId = $categoryId !== null ? (int) $categoryId : null;

        $items = $categoryId ? $medicineModel->byCategory($categoryId) : $medicineModel->all();
        $items = $this->applyFilters($medicineModel, $items);

        $this->render('catalog.index', [
            'items'          => $items,
            'categories'     => CustomerMedicine::categories(),
            'categoryCounts' => CustomerMedicine::categoryCounts(),
            'brands'         => CustomerMedicine::brands(),
            'priceRange'     => CustomerMedicine::priceRange(),
            'activeCategory' => $categoryId,
            'activeBrands'   => $this->selectedBrands(),
            'activeSort'     => $this->input('sort', 'relevance'),
            'query'          => '',
            'pageTitle'      => 'Pharmacy Catalog',
        ]);
    }

    public function search(): void
    {
        $this->allowGuest();

        $q = trim((string) $this->input('q', ''));
        $medicineModel = new CustomerMedicine();
        $items = $medicineModel->search($q);
        $items = $this->applyFilters($medicineModel, $items);

        $this->render('catalog.search', [
            'items'          => $items,
            'categories'     => CustomerMedicine::categories(),
            // The filter sidebar is shared with the catalog page and needs
            // these two. Without them /search fataled on array_sum(null).
            'categoryCounts' => CustomerMedicine::categoryCounts(),
            'activeCategory' => null,
            'brands'         => CustomerMedicine::brands(),
            'priceRange'     => CustomerMedicine::priceRange(),
            'activeBrands'   => $this->selectedBrands(),
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

    private function selectedBrands(): array
    {
        $brands = $this->input('brand', []);
        return is_array($brands) ? $brands : [$brands];
    }

    private function applyFilters(CustomerMedicine $medicineModel, array $items): array
    {
        $minPrice = $this->input('min_price');
        $maxPrice = $this->input('max_price');

        return $medicineModel->filterAndSort(
            $items,
            $this->selectedBrands(),
            $minPrice !== null && $minPrice !== '' ? (float) $minPrice : null,
            $maxPrice !== null && $maxPrice !== '' ? (float) $maxPrice : null,
            (string) $this->input('sort', 'relevance')
        );
    }
}
