<?php

namespace Tests\Unit;

use App\Services\MarkdownService;
use Tests\TestCase;

class MarkdownServiceTest extends TestCase
{
    private MarkdownService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MarkdownService;
    }

    public function test_markdown_conversion(): void
    {
        $markdown = <<<'MARKDOWN'
# Main Title

## Section 1
This is a paragraph with an [external link](https://example.com) and an [internal link](https://kbase.test/page).
You can also visit https://autolink.com directly.

### Task List
- [ ] Unchecked task
- [x] Checked task

### Table Example
| Left | Center | Right |
|:-----|:------:|------:|
| L1   |   C1   |    R1 |

### Images
![Test Image](image.jpg)

[Unsafe Link](javascript:alert('test'))
MARKDOWN;

        $html = $this->service->toHTML($markdown);

        // Test Table of Contents
        $this->assertStringContainsString('class="toc border-bottom py-5"', $html);
        $this->assertStringContainsString('Section 1', $html);

        // Test Main Title
        $this->assertStringContainsString('<h1 class="title-main">', $html);
        $this->assertStringContainsString('Main Title', $html);

        // Test Heading Permalinks
        $this->assertStringContainsString('hover_link', $html);
        $this->assertStringContainsString('section-1', $html);

        // Test External Links
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('link-underline-danger', $html);
        $this->assertStringContainsString('rel="noopener noreferrer"', $html);

        // Test Internal Links
        $this->assertStringContainsString('link-dark link-offset-2', $html);
        $this->assertStringContainsString('href="https://kbase.test/page"', $html);

        // Test Autolinks
        $this->assertStringContainsString('href="https://autolink.com"', $html);

        // Test Task Lists
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('checked', $html);

        // Test Table Formatting
        $this->assertStringContainsString('table-responsive', $html);
        $this->assertStringContainsString('table table-hover table-success table-striped', $html);
        $this->assertStringContainsString('text-start', $html);
        $this->assertStringContainsString('text-center', $html);
        $this->assertStringContainsString('text-end', $html);

        // Test Images
        $this->assertStringContainsString('img-fluid rounded', $html);
        $this->assertStringContainsString('alt="Test Image"', $html);

        // Test Unsafe Links
        $this->assertStringNotContainsString('javascript:', $html);
    }
}
