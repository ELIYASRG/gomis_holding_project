<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Vich\Uploadable]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(
        message: "Le nom est obligatoire."
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;



    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;
    



    #[Assert\NotBlank(
        message: "La catégorie est obligatoire."
    )]
    #[Assert\Type(
        type: Category::class,
        message: 'veuillez entrer une catégorie valide.',
    )]
    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;
    



    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;
    



    #[Assert\NotBlank(
        message: "La description est obligatoire."
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;
    




    #[ORM\Column(options: ['default' => false] )]
    private ?bool $isPublished = null;
    
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;
    
    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;
    

    #[Assert\File(
        maxSize: '2048k',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/bmp'],
        mimeTypesMessage: "Seuls ces formats d'images sont autorisés : jpeg, jpg, png, webp, bmp",
    )]
    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'image_post', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;


    
    // #[ORM\OneToMany(mappedBy: 'article', targetEntity: Image::class, orphanRemoval: true)]
    // private Collection $images;

    public function __construct()
    {
        $this->isPublished = false;
        // $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

            /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }



    // /**
    //  * @return Collection<int, Image>
    //  */
    // public function getImages(): Collection
    // {
    //     return $this->images;
    // }

    // public function addImage(Image $image): self
    // {
    //     if (!$this->images->contains($image)) {
    //         $this->images->add($image);
    //         $image->setArticle($this);
    //     }

    //     return $this;
    // }

    // public function removeImage(Image $image): self
    // {
    //     if ($this->images->removeElement($image)) {
    //         // set the owning side to null (unless already changed)
    //         if ($image->getArticle() === $this) {
    //             $image->setArticle(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
