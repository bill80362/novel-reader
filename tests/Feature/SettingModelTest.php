<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_setting_can_be_retrieved(): void
    {
        Setting::create(['key' => 'site_name', 'value' => 'My Novel Site']);

        $value = Setting::get('site_name');
        $this->assertEquals('My Novel Site', $value);
    }

    public function test_setting_returns_default_when_not_found(): void
    {
        $value = Setting::get('non_existent', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    public function test_setting_is_cached(): void
    {
        Setting::create(['key' => 'ga4_id', 'value' => 'GA-123456']);

        $firstCall = Setting::get('ga4_id');
        $this->assertEquals('GA-123456', $firstCall);

        // Delete from database, should still be cached
        Setting::where('key', 'ga4_id')->delete();
        $secondCall = Setting::get('ga4_id');
        $this->assertEquals('GA-123456', $secondCall);
    }

    public function test_setting_cache_is_cleared_on_set(): void
    {
        Setting::set('api_key', 'old-key');
        $this->assertEquals('old-key', Setting::get('api_key'));

        Setting::set('api_key', 'new-key');
        $this->assertEquals('new-key', Setting::get('api_key'));
    }
}
