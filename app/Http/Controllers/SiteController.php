<?php

namespace App\Http\Controllers;

use App\Actions\SendContactMessage;
use App\Actions\StoreQuoteAction;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\QuoteFormRequest;
use App\Models\AboutUsContent;
use App\Models\CompanySetting;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GlassSubCategory;
use App\Models\GlassType;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceType;
use App\Services\ContactNumberService;
use App\Services\SiteService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct(
        protected StoreQuoteAction $storeQuoteAction,
        protected ContactNumberService $contactNumberService,
        protected SiteService $siteService,
        protected SendContactMessage $sendContactMessage
    ) {}

    public function home()
    {
        return view('welcome', $this->siteService->getHomeData());
    }

    public function aboutUs()
    {
        $content = AboutUsContent::active()->with([])->first();

        if (! $content) {
            abort(404);
        }

        $staff = \App\Models\Staff::where('is_active', true)->orderBy('order')->get();

        return view('site.about-us', compact('content', 'staff'));
    }

    public function services(Request $request)
    {
        $perPage = 6;
        $page = $request->input('page', 1);

        $services = Service::active()
            ->ordered()
            ->with([])
            ->paginate($perPage, ['*'], 'page', $page);

        return view('site.services', compact('services'));
    }

    public function gallery(Request $request)
    {
        $category = $request->input('category');
        $perPage = 9;
        $page = $request->input('page', 1);

        $query = GalleryImage::active()->with('category')->ordered();

        if ($category) {
            $query->byCategory($category);
        }

        $images = $query->paginate($perPage, ['*'], 'page', $page);
        $categories = GalleryCategory::active()->ordered()->with([])->get();
        $galleryMetrics = CompanySetting::get('gallery_metrics', []);

        return view('site.gallery', compact('images', 'categories', 'category', 'galleryMetrics'));
    }

    public function galleryShow(GalleryImage $galleryImage)
    {
        $galleryImage->load('category');

        $relatedImages = GalleryImage::active()
            ->with('category')
            ->where('gallery_category_id', $galleryImage->gallery_category_id)
            ->where('id', '!=', $galleryImage->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('site.gallery-show', compact('galleryImage', 'relatedImages'));
    }

    public function contact()
    {
        $whatsappDefault = CompanySetting::get('whatsapp_number_default', '+267 123 4567');
        $whatsappAdditional = CompanySetting::get('whatsapp_additional_numbers', []);
        $primaryPhone = CompanySetting::get('primary_phone', '+267 123 4567');

        $contactData = $this->siteService->getContactData();
        $contactData['contactNumbers'] = $this->contactNumberService->buildContactNumbers($whatsappDefault, $whatsappAdditional, $primaryPhone);

        return view('site.contact', $contactData);
    }

    public function quote()
    {
        $whatsappDefault = CompanySetting::get('whatsapp_number_default', '+267 123 4567');
        $whatsappAdditional = CompanySetting::get('whatsapp_additional_numbers', []);
        $primaryPhone = CompanySetting::get('primary_phone', '+267 123 4567');

        $glassTypes = GlassType::active()->ordered()->with('subCategories')->get();
        $serviceTypes = ServiceType::active()->ordered()->with([])->get();
        $glassSubCategories = GlassSubCategory::active()->ordered()->with('glassType')->get();

        $contactNumbers = $this->contactNumberService->buildContactNumbers($whatsappDefault, $whatsappAdditional, $primaryPhone);

        return view('site.quote', compact('contactNumbers', 'glassTypes', 'serviceTypes', 'glassSubCategories'));
    }

    public function submitContact(ContactFormRequest $request)
    {
        $result = $this->sendContactMessage->handle($request);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function submitQuote(QuoteFormRequest $request)
    {
        $result = $this->storeQuoteAction->execute($request);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function blog(Request $request)
    {
        // Pass initial URL parameters to the view for Livewire component
        $search = $request->input('search', '');
        $categorySlug = $request->input('category');
        $tagSlug = $request->input('tag');

        return view('blog.index', compact('search', 'categorySlug', 'tagSlug'));
    }

    public function blogShow(string $slug)
    {
        $post = Post::published()->where('slug', $slug)->with('categories', 'tags')->firstOrFail();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->with(['categories', 'tags'])
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
