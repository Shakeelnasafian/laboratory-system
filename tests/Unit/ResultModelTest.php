<?php

namespace Tests\Unit;

use App\Models\Result;
use PHPUnit\Framework\TestCase;

class ResultModelTest extends TestCase
{
    private function makeResult(array $attributes = []): Result
    {
        $result = new Result();
        foreach ($attributes as $key => $value) {
            $result->$key = $value;
        }
        return $result;
    }

    public function test_is_draft_returns_true_for_draft_status(): void
    {
        $result = $this->makeResult(['status' => Result::STATUS_DRAFT]);
        $this->assertTrue($result->isDraft());
        $this->assertFalse($result->isVerified());
        $this->assertFalse($result->isReleased());
    }

    public function test_is_verified_returns_true_for_verified_status(): void
    {
        $result = $this->makeResult(['status' => Result::STATUS_VERIFIED]);
        $this->assertTrue($result->isVerified());
        $this->assertFalse($result->isDraft());
        $this->assertFalse($result->isReleased());
    }

    public function test_is_released_returns_true_for_released_status(): void
    {
        $result = $this->makeResult(['status' => Result::STATUS_RELEASED]);
        $this->assertTrue($result->isReleased());
        $this->assertFalse($result->isDraft());
        $this->assertFalse($result->isVerified());
    }

    public function test_is_critical_returns_true_for_critical_flag(): void
    {
        $result = $this->makeResult(['flag' => Result::FLAG_CRITICAL]);
        $this->assertTrue($result->isCritical());
    }

    public function test_is_critical_returns_false_for_non_critical_flag(): void
    {
        foreach ([Result::FLAG_NORMAL, Result::FLAG_HIGH, Result::FLAG_LOW] as $flag) {
            $result = $this->makeResult(['flag' => $flag]);
            $this->assertFalse($result->isCritical(), "Expected isCritical() to be false for flag: {$flag}");
        }
    }

    public function test_is_abnormal_reflects_is_abnormal_attribute(): void
    {
        $abnormal = $this->makeResult(['is_abnormal' => true]);
        $normal = $this->makeResult(['is_abnormal' => false]);

        $this->assertTrue($abnormal->isAbnormal());
        $this->assertFalse($normal->isAbnormal());
    }
}
