<?php

namespace App\Service;

use App\Repository\FavorisRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class FavorisService
{
    protected $favorisService;
    protected $requestStack;
    protected $productRepository;


    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    public function add(int $id): void
    {
        $favoris = $this->requestStack->getSession()->get('favoris', []);
        if (!empty($favoris[$id])) {
            $favoris[$id]++;
        } else {
            $favoris[$id] = 1;
        }
        $this->requestStack->getSession()->set('favoris', $favoris);
    }

    public function remove(int $id): void
    {
        $favoris = $this->requestStack->getSession()->get('favoris', []);
        if (!empty($favoris[$id])) {
            if ($favoris[$id] > 1) {
                $favoris[$id]--;
            } else {
                unset($favoris[$id]);
            }
        }
        $this->requestStack->getSession()->set('favoris', $favoris);
    }


    public function delete(int $id): void
    {
        $favoris = $this->requestStack->getSession()->get('favoris'[]);
        if (!empty($favoris[$id])) {
            unset($favoris[$id]);
        }
        $this->requestStack->getSession()->set('favoris', $favoris);
    }


    public function clear(): void
    {
        $this->requestStack->getSession()->remove('favoris');
    }

    public function getFavoris(): array
    {
        $sessionFavoris = $this->requestStack->getSession()->get('favoris', []);
        $favoris = [];
        foreach ($sessionFavoris as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $element = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $favoris[] = $element;
        }
        return $favoris;
    }

    public function getTotal(): float
    {
        $sessionFavoris = $this->requestStack->getSession()->get('favoris', []);
        $total = 0;
        foreach ($sessionFavoris as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    public function getNbFavoris(): int
    {
        $sessionFavoris = $this->requestStack->getSession()->get('favoris', []);
        $nb = 0;
        foreach ($sessionFavoris as $id => $quantity) {
            $nb += $quantity;
        }

        return $nb;
    }
}
