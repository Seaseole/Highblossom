<?php

namespace App\Http\Controllers;

use App\Domains\Content\Models\ContactNumber;
use App\Domains\Content\Models\GalleryImage;
use App\Domains\Content\Models\Service;
use App\Domains\Content\Models\Testimonial;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $testimonials = Testimonial::active()->ordered()->get();
        $featuredServices = Service::active()->ordered()->take(3)->get();

        return view('welcome', compact('testimonials', 'featuredServices'));
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

        $query = GalleryImage::active()->ordered();

        if ($category) {
            $query->byCategory($category);
        }

        $images = $query->paginate($perPage, ['*'], 'page', $page);
        $categories = ['automotive', 'heavy_machinery', 'fleet', 'other'];

        return view('site.gallery', compact('images', 'categories', 'category'));
    }

    public function contact()
    {
        $contactNumbers = ContactNumber::active()->ordered()->get();
        $primaryPhone = ContactNumber::active()->primary()->first();
        $primaryEmail = \App\Domains\Content\Models\CompanySetting::get('primary_email', 'info@highblossom.co.bw');

        return view('site.contact', compact('contactNumbers', 'primaryPhone', 'primaryEmail'));
    }

    public function quote()
    {
        $contactNumbers = ContactNumber::active()->ordered()->get();
        $primaryPhone = ContactNumber::active()->primary()->first();

        return view('site.quote', compact('contactNumbers', 'primaryPhone'));
    }

    public function submitContact(\App\Http\Requests\ContactFormRequest $request)
    {
        $result = (new \App\Actions\SendContactMessage())->handle($request);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
