<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\SlotProductType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SlotConfirmRequestTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        // GIVEN.
        $date = new \DateTimeImmutable('2024-06-15 14:00:00');

        // WHEN.
        $request = new SlotConfirmRequest(
            SlotProductType::RDV,
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '2',
            $date,
        );

        // THEN.
        $this->assertSame(SlotProductType::RDV, $request->getProductType());
        $this->assertSame('SLOT001', $request->getCodeSlot());
        $this->assertSame('MESH001', $request->getMeshCode());
        $this->assertSame('TXN001', $request->getTransactionId());
        $this->assertSame('1', $request->getRank());
        $this->assertSame('2', $request->getPosition());
        $this->assertSame($date, $request->getDateSelected());
    }
}
