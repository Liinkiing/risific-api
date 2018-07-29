<?php

namespace App\Entity;

use App\Enum\ThemeValueEnum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPreferenceRepository")
 */
class UserPreference
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $theme = ThemeValueEnum::LIGHT_THEME;

    public function getId()
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $theme = strtolower($theme);

        if (!ThemeValueEnum::isThemeValueValid($theme)) {
            throw new \RuntimeException("Unknown theme type $theme");
        }

        $this->theme = $theme;

        return $this;
    }
}
