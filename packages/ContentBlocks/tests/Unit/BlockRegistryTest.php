<?php

namespace Highblossom\ContentBlocks\Tests\Unit;

use Highblossom\ContentBlocks\Services\BlockRegistry;
use Highblossom\ContentBlocks\Blocks\ParagraphBlock;
use Orchestra\Testbench\TestCase;

class BlockRegistryTest extends TestCase
{
    protected BlockRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new BlockRegistry();
    }

    public function test_can_register_block()
    {
        $block = new ParagraphBlock();
        $this->registry->register('paragraph', $block);

        $this->assertTrue($this->registry->has('paragraph'));
        $this->assertSame($block, $this->registry->get('paragraph'));
    }

    public function test_can_get_all_blocks()
    {
        $block1 = new ParagraphBlock();
        $this->registry->register('paragraph', $block1);

        $blocks = $this->registry->all();

        $this->assertCount(1, $blocks);
        $this->assertSame($block1, $blocks->get('paragraph'));
    }

    public function test_can_get_block_types()
    {
        $this->registry->register('paragraph', new ParagraphBlock());

        $types = $this->registry->types();

        $this->assertIsArray($types);
        $this->assertContains('paragraph', $types);
    }

    public function test_returns_null_for_unknown_block()
    {
        $result = $this->registry->get('unknown');

        $this->assertNull($result);
    }

    public function test_render_unknown_block_returns_fallback()
    {
        $result = $this->registry->render('unknown', ['content' => 'test']);

        $this->assertStringContainsString('Unknown block type: unknown', $result);
    }
}
