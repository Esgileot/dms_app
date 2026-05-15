<?php

declare(strict_types=1);

namespace Presentation\Web\Tests\Feature;

use Presentation\Web\Tests\TestCase;

/**
 * @internal
 */
class HomeControllerTest extends TestCase
{
    public function testHomePageReturnsSuccessfulResponse(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
    }

    public function testHomePageUsesCorrectView(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
        $response->assertViewIs('web::dashboard.index');
    }

    public function testHomePageHasCorrectTitle(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
        $response->assertViewHas('title', 'Dashboard');
    }

    public function testHomePageHasBreadcrumbs(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
        $response->assertViewHas('breadcrumbs');

        $breadcrumbs = $response->viewData('breadcrumbs');
        $this->assertIsArray($breadcrumbs);
        $this->assertCount(2, $breadcrumbs);

        // Check Home breadcrumb
        $this->assertEquals('Home', $breadcrumbs[0]['name']);
        $this->assertArrayHasKey('url', $breadcrumbs[0]);

        // Check Dashboard breadcrumb
        $this->assertEquals('Dashboard', $breadcrumbs[1]['name']);
        $this->assertTrue($breadcrumbs[1]['active']);
    }

    public function testHomePageBreadcrumbHomeUrlIsCorrect(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
        $breadcrumbs = $response->viewData('breadcrumbs');

        // The home URL should be the route to the home page
        $expectedUrl = route('web.home');
        $this->assertEquals($expectedUrl, $breadcrumbs[0]['url']);
    }

    public function testHomePageReturnsHtmlContentType(): void
    {
        $response = $this->get(route('web.home'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/html; charset=UTF-8');
    }
}
