<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Media $media;

    #[ORM\Column(type: Types::DECIMAL, precision: 2, scale: 1)]
    private float $value; // Note de 0 à 10

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $review = null; // Critique textuelle

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;
}