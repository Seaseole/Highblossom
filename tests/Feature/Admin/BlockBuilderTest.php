<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\BlockBuilder;
use App\Models\GalleryImage;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Cover the admin block builder: block state round-trips, registry-driven editor
 * metadata, and the block attribute validation performed when a post is saved.
 */
class BlockBuilderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed permissions and act as a user that can manage blog posts.
     */
    private function actingAsAdmin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findOrCreate('Super Admin', 'web'));

        $this->actingAs($user);
    }

    public function test_it_restores_blocks_when_the_round_trip_value_is_a_json_string(): void
    {
        $content = json_encode([
            ['type' => 'heading', 'attributes' => ['content' => 'Round trip', 'level' => 'h2']],
        ]);

        Livewire::test(BlockBuilder::class, ['name' => 'content', 'value' => $content])
            ->assertSet('blocks.0.type', 'heading')
            ->assertSet('blocks.0.attributes.content', 'Round trip');
    }

    public function test_it_serves_editor_metadata_from_the_block_registry(): void
    {
        $meta = Livewire::test(BlockBuilder::class)->instance()->blockMeta;

        $this->assertSame('line', $meta['divider']['defaults']['style']);
        $this->assertSame(['Option 1', 'Option 2'], $meta['poll']['defaults']['options']);
        $this->assertContains('content', $meta['alert']['required']);
        $this->assertSame(['type', 'content', 'dismissible'], $meta['alert']['required']);
        $this->assertTrue($meta['paragraph']['editable']);
        $this->assertTrue($meta['gallery']['editable']);
    }

    /**
     * The client used to ship its own defaults; several of them broke the
     * enum and array shaped rules of the block classes.
     */
    public function test_registry_defaults_have_the_shape_their_rules_require(): void
    {
        $meta = Livewire::test(BlockBuilder::class)->instance()->blockMeta;

        $shapeRules = [
            'divider' => ['style' => 'required|in:line,dots,space', 'size' => 'nullable|in:sm,md,lg'],
            'alert' => ['type' => 'required|in:info,success,warning,danger', 'dismissible' => 'required|boolean'],
            'list' => ['type' => 'required|string|in:ordered,unordered,ul,ol'],
            'heading' => ['level' => 'required|string'],
            'countdown' => ['target_date' => 'required|date'],
            'poll' => [
                'options' => 'required|array|min:2',
                'options.*' => 'required|string',
                'allow_multiple' => 'required|boolean',
                'show_results' => 'required|boolean',
            ],
        ];

        foreach ($shapeRules as $type => $rules) {
            $validator = Validator::make($meta[$type]['defaults'], $rules);

            $this->assertFalse(
                $validator->fails(),
                "{$type} builder defaults rejected: ".json_encode($validator->errors()->all())
            );
        }
    }

    public function test_a_post_with_builder_divider_styles_can_be_saved(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.posts.store'), [
            'title' => 'Divider styles',
            'status' => 'draft',
            'content' => json_encode([
                ['type' => 'divider', 'attributes' => ['style' => 'dots', 'size' => 'md']],
                ['type' => 'divider', 'attributes' => ['style' => 'space', 'size' => 'lg']],
            ]),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('posts', ['title' => 'Divider styles']);
    }

    public function test_an_unsupported_divider_style_is_rejected_with_a_block_scoped_error(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Bad divider',
            'status' => 'draft',
            'content' => json_encode([
                ['type' => 'divider', 'attributes' => ['style' => 'dashed', 'size' => 'md']],
            ]),
        ])->assertSessionHasErrors('content.0.attributes');
    }

    public function test_flashed_block_errors_are_mapped_onto_their_block_ids(): void
    {
        $bag = new ViewErrorBag;
        $bag->put('default', new MessageBag([
            'content.0.attributes' => ['Block divider at position 0: The selected style is invalid.'],
        ]));
        session()->put('errors', $bag);

        $content = json_encode([
            ['id' => 'block_fixed_id', 'type' => 'divider', 'attributes' => ['style' => 'dashed', 'size' => 'md']],
        ]);

        Livewire::test(BlockBuilder::class, ['name' => 'content', 'value' => $content])
            ->assertSet('blockErrors', [
                'block_fixed_id' => ['Block divider at position 0: The selected style is invalid.'],
            ]);
    }

    public function test_the_builder_page_renders_the_block_toolbar(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.posts.create'))
            ->assertSee('Drag to reorder')
            ->assertSee('blockMeta')
            ->assertSee('x-collapse');
    }

    public function test_the_admin_layout_surfaces_validation_errors_after_a_failed_save(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.posts.store'), ['title' => '', 'status' => 'draft'])->assertRedirect();

        $this->get(route('admin.posts.create'))->assertSee('The form could not be saved', false);
    }

    public function test_the_layout_and_collection_blocks_expose_editors(): void
    {
        $meta = Livewire::test(BlockBuilder::class)->instance()->blockMeta;

        foreach (['gallery', 'table', 'form', 'carousel', 'columns', 'tabs', 'accordion'] as $type) {
            $this->assertTrue($meta[$type]['editable'], "{$type} should expose an inline editor");
        }
    }

    /**
     * Newly editable types ship defaults that must already satisfy the structural
     * rules of their block classes; entry-level required fields stay empty and
     * are surfaced by the builder instead.
     */
    public function test_registry_defaults_for_the_new_editors_satisfy_their_shape_rules(): void
    {
        $meta = Livewire::test(BlockBuilder::class)->instance()->blockMeta;

        $shapeRules = [
            'gallery' => [
                'images' => 'required|array|min:1',
                'images.*' => 'required|array',
                'columns' => 'required|integer|min:1|max:6',
            ],
            'table' => [
                'headers' => 'required|array|min:1',
                'rows' => 'required|array',
                'rows.*' => 'required|array',
            ],
            'accordion' => [
                'items' => 'required|array|min:1',
                'items.*' => 'required|array',
                'multiple_open' => 'required|boolean',
            ],
            'form' => [
                'fields' => 'required|array|min:1',
                'fields.*.type' => 'required|in:text,email,textarea,select,checkbox,radio',
                'fields.*.required' => 'required|boolean',
                'submit_text' => 'required|string',
            ],
            'carousel' => [
                'slides' => 'required|array|min:1',
                'slides.*' => 'required|array',
                'autoplay' => 'required|boolean',
                'interval' => 'required|integer|min:1|max:60',
            ],
            'tabs' => [
                'tabs' => 'required|array|min:1',
                'tabs.*.label' => 'required|string',
                'tabs.*.content' => 'required|array|min:1',
            ],
            'columns' => [
                'columns' => 'required|array|min:1',
                'column_widths' => 'required|array',
                'column_widths.*' => 'required|integer|min:1|max:12',
            ],
        ];

        foreach ($shapeRules as $type => $rules) {
            $validator = Validator::make($meta[$type]['defaults'], $rules);

            $this->assertFalse(
                $validator->fails(),
                "{$type} builder defaults rejected: ".json_encode($validator->errors()->all())
            );
        }
    }

    public function test_layout_and_collection_blocks_round_trip_through_the_save_validation(): void
    {
        $this->actingAsAdmin();

        $blocks = [
            ['type' => 'gallery', 'attributes' => ['images' => [['src' => 'uploads/a.jpg', 'alt' => 'A', 'caption' => null]], 'columns' => 3]],
            ['type' => 'table', 'attributes' => ['headers' => ['Name', 'Price'], 'rows' => [['Window', '120']], 'caption' => 'Pricing']],
            ['type' => 'accordion', 'attributes' => ['items' => [['title' => 'Question?', 'content' => 'Answer.']], 'multiple_open' => false]],
            ['type' => 'form', 'attributes' => ['fields' => [['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true]], 'submit_text' => 'Send', 'action_url' => null]],
            ['type' => 'columns', 'attributes' => ['columns' => [[['type' => 'paragraph', 'attributes' => ['content' => 'Left']]], []], 'column_widths' => [6, 6]]],
            ['type' => 'tabs', 'attributes' => ['tabs' => [['label' => 'One', 'content' => [['type' => 'heading', 'attributes' => ['content' => 'Hi', 'level' => 'h3']]]]]]],
            ['type' => 'carousel', 'attributes' => ['slides' => [[['type' => 'paragraph', 'attributes' => ['content' => 'Slide']]]], 'autoplay' => false, 'interval' => 5]],
        ];

        $response = $this->post(route('admin.posts.store'), [
            'title' => 'Layout blocks',
            'status' => 'draft',
            'content' => json_encode($blocks),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('posts', ['title' => 'Layout blocks']);
    }

    public function test_a_gallery_image_missing_its_alt_text_is_rejected(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.posts.store'), [
            'title' => 'Bad gallery',
            'status' => 'draft',
            'content' => json_encode([
                ['type' => 'gallery', 'attributes' => ['images' => [['src' => 'uploads/a.jpg', 'alt' => '', 'caption' => null]], 'columns' => 3]],
            ]),
        ])->assertSessionHasErrors('content.0.attributes');
    }

    public function test_the_preview_renders_blocks_through_the_production_renderer(): void
    {
        $html = Livewire::test(BlockBuilder::class)
            ->instance()
            ->renderPreview(json_encode([
                ['type' => 'heading', 'attributes' => ['content' => 'Preview me', 'level' => 'h2']],
                ['type' => 'columns', 'attributes' => ['columns' => [[['type' => 'paragraph', 'attributes' => ['content' => 'Nested']]], []], 'column_widths' => [6, 6]]],
            ]));

        $this->assertStringContainsString('Preview me', $html);
        $this->assertStringContainsString('Nested', $html);
    }

    public function test_the_preview_degrades_gracefully_on_broken_input(): void
    {
        $builder = Livewire::test(BlockBuilder::class)->instance();

        $this->assertStringContainsString('not a valid block list', $builder->renderPreview('not json'));
        $this->assertStringContainsString('Unknown block type', $builder->renderPreview(json_encode([['type' => 'mystery']])));
        $this->assertStringContainsString('not a valid block list', $builder->renderPreview(json_encode('just a string')));
    }

    public function test_the_media_library_answers_json_for_the_picker(): void
    {
        $this->actingAsAdmin();

        GalleryImage::create([
            'title' => 'Picker image',
            'image_path' => 'gallery/picker.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->getJson(route('admin.media-library.index'))
            ->assertOk()
            ->assertJsonStructure(['images' => [['id', 'name', 'url', 'alt']]])
            ->assertJsonPath('images.0.name', 'Picker image');
    }
}
