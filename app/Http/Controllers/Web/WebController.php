<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Franchise;
use App\Models\Contact;
use App\Models\Category;
use App\Models\Review;

class WebController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'desc')
        ->where('is_active', 1)
        ->where('category_id', 1)
        ->get();
        $reviews = Review::with('user')->where('is_publish', 1)->orderBy('rating', 'desc')->get();
        return view('web.index', compact('services', 'reviews'));
    }

    public function checkZip(Request $request)
    {
        $request->validate(['zip_code' => 'required']);

        $response = \Illuminate\Support\Facades\Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $request->zip_code,
            'key' => env('GOOGLE_MAPS_API_KEY')
        ]);

        $geo = $response->json();

        if ($geo['status'] !== 'OK' || empty($geo['results'])) {
            return redirect()->back()->with('zip_error', 'Invalid Zip Code or location could not be determined.');
        }

        $lat = $geo['results'][0]['geometry']['location']['lat'];
        $lng = $geo['results'][0]['geometry']['location']['lng'];

        $result = Franchise::getByLocation($lat, $lng);

        if (isset($result['message']) && $result['franchises']->isEmpty()) {
            return redirect()->back()->with('zip_error', 'Service is not available in your area');
        }

        return redirect()->route('web.book.franchise', ['latitude' => $lat, 'longitude' => $lng]);
    }

    public function services()
    {
        $services = Service::orderBy('id', 'desc')->where('is_active', 1)->get();
        $categories = Category::with(['services' => function ($query) {
            $query->where('is_active', 1)->orderBy('id', 'desc');
        }])->get();

        return view('web.services', compact('services', 'categories'));
    }

    public function courses()
    {
        return view('web.courses');
    }

    public function about()
    {
        $services = Service::orderBy('id', 'desc')->where('is_active', 1)->take(6)->get();
        return view('web.about', compact('services'));
    }

    public function franchise()
    {
        $franchises = Franchise::with('zone', 'weeklySchedules')->orderBy('id', 'desc')->where('is_active', 1)->get();
        $services = Service::get();
        return view('web.franchise', compact('franchises', 'services'));
    }

    public function franchiseDetails(Franchise $franchise)
    {
        if (!$franchise->is_active) {
            abort(404);
        }
        $franchise->load('weeklySchedules', 'zone');
        return view('web.franchise_details', compact('franchise'));
    }

    public function pricing()
    {
        $categories = Category::with(['standards.size', 'services.sizes.size'])->where('is_active', 1)->get();
        return view('web.pricing', compact('categories'));
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'nullable',
            'subject' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            Contact::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for your message. We will get back to you soon.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ]);
        }
    }
    public function newsletterSubscribe(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.unique' => 'This email is already subscribed to our newsletter.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            \App\Models\Newsletter::create([
                'email' => $request->email,
                'status' => 1,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ]);
        }
    }
}

