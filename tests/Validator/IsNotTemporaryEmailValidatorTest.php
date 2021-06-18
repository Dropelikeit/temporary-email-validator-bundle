<?php

declare(strict_types=1);

namespace MarcelStrahl\TemporaryEmailValidatorBundle\Tests\Validator;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use MarcelStrahl\TemporaryEmailValidatorBundle\Validator\IsNotTemporaryEmail;
use MarcelStrahl\TemporaryEmailValidatorBundle\Validator\IsNotTemporaryEmailValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use TemporaryEmailDetection\Client;
use Webmozart\Assert\Assert;

/**
 * @author Marcel Strahl <info@marcel-strahl.de>
 */
final class IsNotTemporaryEmailValidatorTest extends ConstraintValidatorTestCase
{
    private const REQUEST_METHOD = 'GET';

    private const API_URL = 'https://api.temporary-email-detection.de/detect/%s';

    /**
     * @var ClientInterface|MockObject
     */
    private ClientInterface $clientMock;

    private Client $client;

    /**
     * @var LoggerInterface|MockObject
     */
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->clientMock = $this->createMock(ClientInterface::class);
        $this->client = new Client($this->clientMock);
        $this->logger = $this->createMock(LoggerInterface::class);
        parent::setUp();
        $this->constraint = new IsNotTemporaryEmail();
    }

    protected function createValidator(): IsNotTemporaryEmailValidator
    {
        return new IsNotTemporaryEmailValidator($this->client, $this->logger);
    }

    /**
     * @test
     * @dataProvider dataProviderWithNoEmailValue
     */
    public function canNotValidateCauseInvalidEmail(string | int $value, bool $throwException): void
    {
        if ($throwException) {
            $this->expectException(UnexpectedTypeException::class);
        }

        $this->validator->validate($value, $this->constraint);
    }

    /**
     * @return array<string, array>
     */
    public function dataProviderWithNoEmailValue(): array
    {
        return [
            'with_value_int' => [
                1234, true,
            ],
            'with_value_no_valid_email' => [
                'test', true,
            ],
        ];
    }

    /**
     * @test
     */
    public function isATemporaryEmail(): void
    {
        $email = 'temporary@temporary.de';

        $willReturn = [
            'temporary' => true,
        ];

        $json = json_encode($willReturn);
        Assert::stringNotEmpty($json);

        $this->clientMock
            ->expects(self::once())
            ->method('request')
            ->with(self::REQUEST_METHOD, sprintf(self::API_URL, $email))
            ->willReturn($this->createResponse($json));

        $this->validator->validate($email, $this->constraint);
        $this->assertCount(1, $this->context->getViolations());
    }

    /**
     * @test
     */
    public function isNotTemporary(): void
    {
        $email = 'info@marcel-strahl.de';

        $willReturn = [
            'temporary' => false,
        ];

        $json = json_encode($willReturn);
        Assert::stringNotEmpty($json);

        $this->clientMock
            ->expects(self::once())
            ->method('request')
            ->with(self::REQUEST_METHOD, sprintf(self::API_URL, $email))
            ->willReturn($this->createResponse($json));

        $this->validator->validate($email, $this->constraint);
        $this->assertNoViolation();
    }

    /**
     * @test
     */
    public function canHandleEmptyValuesWithNoErrors(): void
    {
        $email = null;

        $this->validator->validate($email, $this->constraint);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function throwExceptionIfConstraintIsNotFromTemporaryEmail(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $constraint = $this->createMock(Constraint::class);

        $this->validator->validate(null, $constraint);
    }

    private function createResponse(string $content): ResponseInterface
    {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Platform' => 'test',
            ],
            $content,
        );
    }
}
