<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SendContactMessage;
use App\Actions\StoreQuoteAction;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\QuoteFormRequest;
use App\Models\GalleryImage;
use App\Services\SiteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handle public-facing page requests for the main site.
 */
class SiteController extends Controller
{
    public function __construct(
        protected SiteService $siteService,
        protected StoreQuoteAction $storeQuoteAction,
        protected SendContactMessage $sendContactMessage
    ) {}

    /**
     * Display the homepage.
     *
     * @return View
     */
    public function home()
    {
        return view('welcome', $this->siteService->getHomeData());
    }

    /**
     * Display the About Us page.
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function aboutUs()
    {
        $data = $this->siteService->getAboutUsData();

        if ($data === null) {
            abort(404);
        }

        return view('site.about-us', $data);
    }

    /**
     * Display the Services listing page.
     *
     * @return View
     */
    public function services(Request $request)
    {
        return view('site.services', $this->siteService->getServicesData(
            (int) $request->input('page', 1)
        ));
    }

    /**
     * Display the Gallery index page.
     *
     * @return View
     */
    public function gallery(Request $request)
    {
        return view('site.gallery', $this->siteService->getGalleryData(
            $request->input('category'),
            (int) $request->input('page', 1)
        ));
    }

    /**
     * Display a single gallery image.
     *
     * @return View
     */
    public function galleryShow(GalleryImage $galleryImage)
    {
        return view('site.gallery-show', $this->siteService->getGalleryShowData($galleryImage));
    }

    /**
     * Display the Contact page.
     *
     * @return View
     */
    public function contact()
    {
        return view('site.contact', $this->siteService->getContactData());
    }

    /**
     * Display the Quote request page.
     *
     * @return View
     */
    public function quote()
    {
        return view('site.quote', $this->siteService->getQuotePageData());
    }

    /**
     * Handle contact form submission.
     *
     * @return RedirectResponse
     */
    public function submitContact(ContactFormRequest $request)
    {
        $result = $this->sendContactMessage->handle($request);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Handle quote form submission.
     *
     * @return RedirectResponse
     */
    public function submitQuote(QuoteFormRequest $request)
    {
        $result = $this->storeQuoteAction->execute($request);

        return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Display the Blog index page.
     *
     * @return View
     */
    public function blog(Request $request)
    {
        return view('blog.index', [
            'search' => $request->input('search', ''),
            'categorySlug' => $request->input('category'),
            'tagSlug' => $request->input('tag'),
        ]);
    }

    /**
     * Display a single blog post by slug.
     *
     * @return View
     *
     * @throws ModelNotFoundException
     */
    public function blogShow(string $slug)
    {
        return view('blog.show', $this->siteService->getBlogShowData($slug));
    }
}
