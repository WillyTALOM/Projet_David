<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $name = null;

    #[ORM\Column(length: 45, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    #[ORM\ManyToMany(targetEntity: ProductCategory::class, mappedBy: 'category')]
    private Collection $productCategories;

    #[ORM\ManyToMany(targetEntity: BrandCategory::class, mappedBy: 'category')]
    private Collection $brandCategories;

 

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
        $this->brandCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

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
            $productCategory->addCategory($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            $productCategory->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, BrandCategory>
     */
    public function getBrandCategories(): Collection
    {
        return $this->brandCategories;
    }

    public function addBrandCategory(BrandCategory $brandCategory): self
    {
        if (!$this->brandCategories->contains($brandCategory)) {
            $this->brandCategories->add($brandCategory);
            $brandCategory->addCategory($this);
        }

        return $this;
    }

    public function removeBrandCategory(BrandCategory $brandCategory): self
    {
        if ($this->brandCategories->removeElement($brandCategory)) {
            $brandCategory->removeCategory($this);
        }

        return $this;
    }

   
}
