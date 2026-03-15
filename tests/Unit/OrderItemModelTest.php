<?php

namespace Tests\Unit;

use App\Models\OrderItem;
use Carbon\Carbon;
use Tests\TestCase;

class OrderItemModelTest extends TestCase
{
    private function makeItem(array $attributes = []): OrderItem
    {
        $item = new OrderItem();
        foreach ($attributes as $key => $value) {
            $item->$key = $value;
        }
        return $item;
    }

    public function test_is_pending_returns_true_for_pending_status(): void
    {
        $item = $this->makeItem(['status' => OrderItem::STATUS_PENDING]);
        $this->assertTrue($item->isPending());
        $this->assertFalse($item->isProcessing());
        $this->assertFalse($item->isCompleted());
    }

    public function test_is_processing_returns_true_for_processing_status(): void
    {
        $item = $this->makeItem(['status' => OrderItem::STATUS_PROCESSING]);
        $this->assertTrue($item->isProcessing());
        $this->assertFalse($item->isPending());
        $this->assertFalse($item->isCompleted());
    }

    public function test_is_completed_returns_true_for_completed_status(): void
    {
        $item = $this->makeItem(['status' => OrderItem::STATUS_COMPLETED]);
        $this->assertTrue($item->isCompleted());
        $this->assertFalse($item->isPending());
        $this->assertFalse($item->isProcessing());
    }

    public function test_is_overdue_returns_true_when_past_due_and_not_completed(): void
    {
        $item = $this->makeItem([
            'status' => OrderItem::STATUS_PROCESSING,
            'due_at' => Carbon::now()->subHour(),
        ]);
        $this->assertTrue($item->isOverdue());
    }

    public function test_is_overdue_returns_false_when_completed_even_if_past_due(): void
    {
        $item = $this->makeItem([
            'status' => OrderItem::STATUS_COMPLETED,
            'due_at' => Carbon::now()->subHour(),
        ]);
        $this->assertFalse($item->isOverdue());
    }

    public function test_is_overdue_returns_false_when_no_due_date(): void
    {
        $item = $this->makeItem([
            'status' => OrderItem::STATUS_PROCESSING,
            'due_at' => null,
        ]);
        $this->assertFalse($item->isOverdue());
    }

    public function test_is_overdue_returns_false_when_due_in_future(): void
    {
        $item = $this->makeItem([
            'status' => OrderItem::STATUS_PROCESSING,
            'due_at' => Carbon::now()->addHour(),
        ]);
        $this->assertFalse($item->isOverdue());
    }
}
