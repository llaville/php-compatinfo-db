<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping\Column;

use Deprecated;

/**
 * @since Release 6.12.0
 * @author Laurent Laville
 */
trait DeprecatedElementTrait
{
    #[Column(name: "deprecated_since", type: "string", nullable: true)]
    private ?string $deprecatedSince = null;

    #[Column(name: "deprecated_message", type: "string", nullable: true)]
    private ?string $deprecatedMessage = null;

    /**
     * @return array<string, string|null>|null
     */
    public function getDeprecated(): ?array
    {
        if ($this->deprecatedSince === null && $this->deprecatedMessage === null) {
            return null;
        }

        return [
            'since' => $this->deprecatedSince,
            'message' => $this->deprecatedMessage,
        ];
    }

    public function setDeprecated(Deprecated $deprecated): void
    {
        $this->deprecatedSince = $deprecated->since;
        $this->deprecatedMessage = $deprecated->message;
    }
}
