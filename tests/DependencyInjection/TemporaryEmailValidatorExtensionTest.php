<?php

declare(strict_types=1);

namespace MarcelStrahl\TemporaryEmailValidatorBundle\Tests\DependencyInjection;

use MarcelStrahl\TemporaryEmailValidatorBundle\DependencyInjection\MarcelStrahlTemporaryEmailValidatorExtension;
use MarcelStrahl\TemporaryEmailValidatorBundle\Validator\IsNotTemporaryEmail;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class TemporaryEmailValidatorExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @return MarcelStrahlTemporaryEmailValidatorExtension[]
     * @psalm-return list<MarcelStrahlTemporaryEmailValidatorExtension>
     */
    protected function getContainerExtensions(): array
    {
        return [
            new MarcelStrahlTemporaryEmailValidatorExtension(),
        ];
    }

    /**
     * @test
     */
    public function afterLoadingTheCorrectParameterHasBeenSet(): void
    {
        $this->load();

        /**
         * @var MarcelStrahlTemporaryEmailValidatorExtension $extension
         */
        $extension = $this->container->getExtension('marcel_strahl_temporary_email_validator');

        $this->assertSame(
            [
                IsNotTemporaryEmail::class,
            ],
            $extension->getAnnotatedClassesToCompile(),
        );
    }
}
