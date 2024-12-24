<?php

namespace App\Services;

use Illuminate\Support\Str;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;

class MarkdownService
{
    /**
     * Convert markdown to HTML with configured extensions and styling.
     *
     * @param  string  $markdown  The markdown content to convert
     * @return string The HTML output
     */
    public function toHTML(string $markdown): string
    {
        $config = [
            'allow_unsafe_links' => false,
            'heading_permalink' => [
                'html_class' => 'hover_link',
                'symbol' => '#',
                'insert' => 'after',
            ],
            'default_attributes' => [
                Heading::class => [
                    'class' => function (Heading $node) {
                        if ($node->getLevel() === 1) {
                            return 'title-main';
                        } else {
                            return null;
                        }
                    },
                ],
                Table::class => [
                    'class' => 'table table-hover table-success table-striped',
                ],
                Link::class => [
                    'class' => 'link-dark link-offset-2',
                ],
                Image::class => [
                    'class' => 'img-fluid rounded',
                ],
                FencedCode::class => [
                    'class' => function ($node) {
                        //dd($node);
                        // Check if the node already has a class attribute, then don't add the "code" class
                        $attributes = $node->data['attributes'];
                        if (isset($attributes['class'])) {
                            return null; // Return null to keep the existing "mermaid" class
                        }

                        return 'code rounded-bottom-1';
                    },
                ],
                Code::class => [
                    'class' => 'code fw-bold rounded',
                ],
            ],
            'table' => [
                'wrap' => [
                    'enabled' => true,
                    'tag' => 'div',
                    'attributes' => ['class' => 'table-responsive'],
                ],
                'alignment_attributes' => [
                    'left' => ['class' => 'text-start'],
                    'center' => ['class' => 'text-center'],
                    'right' => ['class' => 'text-end'],
                ],
            ],
            'external_link' => [
                'internal_hosts' => ['kbase.test', 'app.asensar.com'],
                'open_in_new_window' => true,
                'html_class' => 'link-offset-2  link-underline-danger',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
            'table_of_contents' => [
                'html_class' => 'toc border-bottom py-5',
                'position' => 'top',
                'style' => 'ordered',
                'normalize' => 'relative',
            ],
        ];

        $extensions = [
            new HeadingPermalinkExtension,
            new TableOfContentsExtension,
            new TableExtension,
            new AttributesExtension,
            new DefaultAttributesExtension,
            new TaskListExtension,
            new AutolinkExtension,
            new ExternalLinkExtension,
        ];

        return Str::markdown($markdown, $config, $extensions);
    }
}
