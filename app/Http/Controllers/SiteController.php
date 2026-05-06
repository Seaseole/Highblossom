<?php

namespace App\Http\Controllers;

use App\Actions\StoreQuoteAction;
use App\Domains\Content\Models\AboutUsContent;
use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\GalleryCategory;
use App\Domains\Content\Models\GlassType;
use App\Domains\Content\Models\Post;
use App\Domains\Content\Models\Service;
use App\Domains\Content\Models\ServiceType;
use App\Domains\Content\Models\CompanySetting;
use App\Http\Requests\QuoteFormRequest;
use App\Services\ContactNumberService;
use App\Services\SiteService;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct(
        protected StoreQuoteAction $storeQuoteAction,
        protected ContactNumberService $contactNumberService,
        protected SiteService $siteService
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

        return view('site.about-us', compact('content'));
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

        return view('site.gallery', compact('images', 'categories', 'category'));
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

        // Defensive eager loading: models have no relationships, with([]) documents intent
        $glassTypes = GlassType::active()->ordered()->with([])->get();
        $serviceTypes = ServiceType::active()->ordered()->with([])->get();

        // Build contact numbers using service
        $contactNumbers = $this->contactNumberService->buildContactNumbers($whatsappDefault, $whatsappAdditional, $primaryPhone);

        return view('site.quote', compact('contactNumbers', 'glassTypes', 'serviceTypes'));
    }

    public function submitContact(\App\Http\Requests\ContactFormRequest $request)
    {
        $result = (new \App\Actions\SendContactMessage())->handle($request);

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

    public function blogShow($slug)
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
