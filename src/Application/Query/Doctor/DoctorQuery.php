<?php declare(strict_types=1);
/**
 * This file is part of the PHP_CompatInfoDB package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Bartlett\CompatInfoDb\Application\Query\Doctor;

use Bartlett\CompatInfoDb\Application\Query\QueryInterface;
use Bartlett\CompatInfoDb\Domain\ValueObject\Platform;

/**
 * Value Object of console doctor command.
 *
 * @since Release 3.6.0
 * @author Laurent Laville
 */
final class DoctorQuery implements QueryInterface
{
    /** @var string[] */
    private array $extensions;
    private bool $tests;
    private string $version;

    public function __construct(Platform $platform, bool $withTests, string $version)
    {
        $this->extensions = [];
        foreach ($platform->getExtensions() as $extension) {
            $this->extensions[] = $extension->getName();
        }
        $this->tests = $withTests;
        $this->version = $version;
    }

    /**
     * @return string[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @return bool
     */
    public function withTests(): bool
    {
        return $this->tests;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
