<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bags = null;

    #[ORM\Column(length: 255)]
    private ?string $shoes = null;

    #[ORM\Column(length: 255)]
    private ?string $clothe = null;

    #[ORM\Column(length: 255)]
    private ?string $watch = null;

    #[ORM\Column(length: 255)]
    private ?string $accessory = null;

    #[ORM\OneToMany(mappedBy: 'productCategory', targetEntity: Product::class)]
    private Collection $product;

    #[ORM\ManyToMany(targetEntity: BrandCategory::class, inversedBy: 'productCategories')]
    private Collection $brandCategory;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'productCategories')]
    private Collection $category;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->brandCategory = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBags(): ?string
    {
        return $this->bags;
    }

    public function setBags(string $bags): self
    {
        $this->bags = $bags;

        return $this;
    }

    public function getShoes(): ?string
    {
        return $this->shoes;
    }

    public function setShoes(string $shoes): self
    {
        $this->shoes = $shoes;

        return $this;
    }

    public function getClothe(): ?string
    {
        return $this->clothe;
    }

    public function setClothe(string $clothe): self
    {
        $this->clothe = $clothe;

        return $this;
    }

    public function getWatch(): ?string
    {
        return $this->watch;
    }

    public function setWatch(string $watch): self
    {
        $this->watch = $watch;

        return $this;
    }

    public function getAccessory(): ?string
    {
        return $this->accessory;
    }

    public function setAccessory(string $accessory): self
    {
        $this->accessory = $accessory;

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
            $product->setProductCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductCategory() === $this) {
                $product->setProductCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BrandCategory>
     */
    public function getBrandCategory(): Collection
    {
        return $this->brandCategory;
    }

    public function addBrandCategory(BrandCategory $brandCategory): self
    {
        if (!$this->brandCategory->contains($brandCategory)) {
            $this->brandCategory->add($brandCategory);
        }

        return $this;
    }

    public function removeBrandCategory(BrandCategory $brandCategory): self
    {
        $this->brandCategory->removeElement($brandCategory);

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
