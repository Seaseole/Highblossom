<?php

namespace App\Http\Controllers;

use App\Actions\StoreQuoteAction;
use App\Domains\Content\Models\AboutUsContent;
use App\Domains\Content\Models\CompanySetting;
use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\GalleryCategory;
use App\Domains\Content\Models\GlassType;
use App\Domains\Content\Models\Service;
use App\Domains\Content\Models\ServiceType;
use App\Domains\Content\Models\Testimonial;
use App\Http\Requests\QuoteFormRequest;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct(
        protected StoreQuoteAction $storeQuoteAction
    ) {}

    public function home()
    {
        $featuredTestimonial = Testimonial::where('is_featured', true)->active()->first();
        $otherTestimonials = Testimonial::active()->where('is_featured', false)->ordered()->get();
        $featuredServices = Service::active()->ordered()->take(3)->get();
        $featuredGalleryImages = GalleryImage::featured()->active()->with('category')->ordered()->take(3)->get();

        $workingHours = CompanySetting::get('working_hours', [
            'monday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'tuesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'wednesday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'thursday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'friday' => ['open' => '08:00', 'close' => '17:30', 'is_closed' => false],
            'saturday' => ['open' => null, 'close' => null, 'is_closed' => true],
            'sunday' => ['open' => null, 'close' => null, 'is_closed' => true],
        ]);
        $timeFormatDisplay = CompanySetting::get('time_format_display', '12');

        return view('welcome', compact('otherTestimonials', 'featuredServices', 'featuredTestimonial', 'workingHours', 'timeFormatDisplay', 'featuredGalleryImages'));
    }

    public function aboutUs()
    {
        $content = AboutUsContent::active();

        if (! $content) {
            abort(404);
        }

        return view('site.about-us', compact('content'));
    }

    public function services(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);

        $services = Service::active()
            ->ordered()
            ->paginate($perPage, ['*'], 'page', $page);

        return view('site.services', compact('services'));
    }

    public function gallery(Request $request)
    {
        $category = $request->get('category');
        $perPage = 9;
        $page = $request->get('page', 1);

        $query = GalleryImage::active()->with('category')->ordered();

        if ($category) {
            $query->byCategory($category);
        }

        $images = $query->paginate($perPage, ['*'], 'page', $page);
        $categories = GalleryCategory::active()->ordered()->get();

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
        $primaryEmail = CompanySetting::get('primary_email', 'info@highblossom.co.bw');
        $workingHours = CompanySetting::get('working_hours', []);
        $timeFormatDisplay = CompanySetting::get('time_format_display', '12');
        $companyName = CompanySetting::get('company_name', 'Highblossom PTY LTD');
        $companyAddress = CompanySetting::get('address', 'Plot 123, Broadhurst, Gaborone, Botswana');
        $googleMapsApiKey = CompanySetting::get('google_maps_api_key', '');
        $mapDirectionsLink = CompanySetting::get('map_directions_link', 'https://maps.google.com');

        // Build contact numbers collection from company settings
        $contactNumbers = collect();
        
        // Add WhatsApp default as primary
        $contactNumbers->push((object)[
            'label' => 'WhatsApp',
            'phone_number' => $whatsappDefault,
            'formatted_number' => $whatsappDefault,
            'is_whatsapp' => true,
            'is_primary' => true,
        ]);

        // Add additional WhatsApp numbers
        if (is_array($whatsappAdditional) && !empty($whatsappAdditional)) {
            foreach ($whatsappAdditional as $index => $item) {
                $phoneNumber = is_array($item) ? ($item['number'] ?? $item[0] ?? '') : $item;
                $label = is_array($item) ? ($item['label'] ?? 'WhatsApp ' . ($index + 2)) : 'WhatsApp ' . ($index + 2);
                
                if ($phoneNumber && $phoneNumber !== $whatsappDefault) {
                    $contactNumbers->push((object)[
                        'label' => $label,
                        'phone_number' => $phoneNumber,
                        'formatted_number' => $phoneNumber,
                        'is_whatsapp' => true,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        // Add primary phone if different from WhatsApp default
        if ($primaryPhone !== $whatsappDefault) {
            $contactNumbers->push((object)[
                'label' => 'Phone',
                'phone_number' => $primaryPhone,
                'formatted_number' => $primaryPhone,
                'is_whatsapp' => false,
                'is_primary' => false,
            ]);
        }

        return view('site.contact', compact('contactNumbers', 'primaryPhone', 'primaryEmail', 'workingHours', 'timeFormatDisplay', 'companyName', 'companyAddress', 'googleMapsApiKey', 'mapDirectionsLink'));
    }

    public function quote()
    {
        $whatsappDefault = CompanySetting::get('whatsapp_number_default', '+267 123 4567');
        $whatsappAdditional = CompanySetting::get('whatsapp_additional_numbers', []);
        $primaryPhone = CompanySetting::get('primary_phone', '+267 123 4567');
        $glassTypes = GlassType::active()->ordered()->get();
        $serviceTypes = ServiceType::active()->ordered()->get();

        // Build contact numbers collection from company settings
        $contactNumbers = collect();
        
        // Add WhatsApp default as primary
        $contactNumbers->push((object)[
            'label' => 'WhatsApp',
            'phone_number' => $whatsappDefault,
            'formatted_number' => $whatsappDefault,
            'is_whatsapp' => true,
            'is_primary' => true,
        ]);

        // Add additional WhatsApp numbers
        if (is_array($whatsappAdditional) && !empty($whatsappAdditional)) {
            foreach ($whatsappAdditional as $index => $item) {
                $phoneNumber = is_array($item) ? ($item['number'] ?? $item[0] ?? '') : $item;
                $label = is_array($item) ? ($item['label'] ?? 'WhatsApp ' . ($index + 2)) : 'WhatsApp ' . ($index + 2);
                
                if ($phoneNumber && $phoneNumber !== $whatsappDefault) {
                    $contactNumbers->push((object)[
                        'label' => $label,
                        'phone_number' => $phoneNumber,
                        'formatted_number' => $phoneNumber,
                        'is_whatsapp' => true,
                        'is_primary' => false,
                    ]);
                }
            }
        }

        // Add primary phone if different from WhatsApp default
        if ($primaryPhone !== $whatsappDefault) {
            $contactNumbers->push((object)[
                'label' => 'Phone',
                'phone_number' => $primaryPhone,
                'formatted_number' => $primaryPhone,
                'is_whatsapp' => false,
                'is_primary' => false,
            ]);
        }

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
}
