<?php

class CustomerProductController extends Controller
{
    public function show($id): void
    {
        

        $medicineModel = new Medicine();
        $medicine = $medicineModel->find((int) $id);

        if (!$medicine) {
            http_response_code(404);
            $this->render('errors.404-inline', ['message' => 'Medicine not found.']);
            return;
        }

        $related = array_values(array_filter(
            $medicineModel->byCategory($medicine['category_id']),
            fn($m) => $m['id'] !== $medicine['id']
        ));

        Medicine::trackViewed($medicine['id']);

        $this->render('product.show', [
            'medicine' => $medicine,
            'related'  => array_slice($related, 0, 4),
        ]);
    }
}
