<?php

namespace Tests\Unit;

use Tests\TestCase;

class StatusBadgeComponentTest extends TestCase
{
    public function test_status_badge_uses_configured_order_palette_and_label(): void
    {
        $view = $this->blade('<x-status-badge type="order" status="processing" />');

        $view->assertSee('Processing');
        $view->assertSee('status-badge--primary', false);
    }

    public function test_status_badge_supports_label_override(): void
    {
        $view = $this->blade('<x-status-badge type="queue" status="ready" label="Ready to Release" />');

        $view->assertSee('Ready to Release');
        $view->assertSee('status-badge--info', false);
    }

    public function test_status_badge_falls_back_to_humanized_status_for_unknown_keys(): void
    {
        $view = $this->blade('<x-status-badge type="queue" status="custom_pending_state" />');

        $view->assertSee('Custom Pending State');
        $view->assertSee('status-badge--neutral', false);
    }

    public function test_status_badge_uses_neutral_tone_for_unknown_type(): void
    {
        $view = $this->blade('<x-status-badge type="nonexistent_type" status="some_status" />');

        $view->assertSee('Some Status');
        $view->assertSee('status-badge--neutral', false);
    }

    public function test_status_badge_label_override_takes_precedence_over_config(): void
    {
        $view = $this->blade('<x-status-badge type="order" status="completed" label="Done" />');

        $view->assertSee('Done');
        $view->assertDontSee('Completed');
    }

    public function test_status_badge_escapes_label_output(): void
    {
        $view = $this->blade('<x-status-badge type="queue" status="custom_status" label="<script>alert(1)</script>" />');

        $view->assertDontSee('<script>', false);
        $view->assertSee('&lt;script&gt;', false);
    }

    public function test_status_badge_uses_success_tone_for_completed_order(): void
    {
        $view = $this->blade('<x-status-badge type="order" status="completed" />');

        $view->assertSee('Completed');
        $view->assertSee('status-badge--success', false);
    }

    public function test_status_badge_uses_danger_tone_for_unpaid_payment(): void
    {
        $view = $this->blade('<x-status-badge type="payment" status="unpaid" />');

        $view->assertSee('Unpaid');
        $view->assertSee('status-badge--danger', false);
    }
}
