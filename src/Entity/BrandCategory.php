<?php

namespace App\Entity;

use App\Repository\BrandCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandCategoryRepository::class)]
class BrandCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $trendy_brands = null;

    #[ORM\Column(length: 55)]
    private ?string $luxury_brands = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'brandCategories')]
    private Collection $product;

    #[ORM\ManyToMany(targetEntity: ProductCategory::class, mappedBy: 'brandCategory')]
    private Collection $productCategories;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'brandCategories')]
    private Collection $category;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrendyBrands(): ?string
    {
        return $this->trendy_brands;
    }

    public function setTrendyBrands(string $trendy_brands): self
    {
        $this->trendy_brands = $trendy_brands;

        return $this;
    }

    public function getLuxuryBrands(): ?string
    {
        return $this->luxury_brands;
    }

    public function setLuxuryBrands(string $luxury_brands): self
    {
        $this->luxury_brands = $luxury_brands;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->product->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories->add($productCategory);
            $productCategory->addBrandCategory($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            $productCategory->removeBrandCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
