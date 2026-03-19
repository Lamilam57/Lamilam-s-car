<?php

namespace App\Http\Controllers;

use App\Models\AppFeedback;
use App\Models\Car;
use App\Models\CarType;
use App\Models\CarView;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subQuery = Car::where('published_at', '<', now())
            ->orderBy('published_at', 'desc')
            ->limit(30);

        $cars = Car::fromSub($subQuery, 'cars')
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->paginate(10);

        $totalViews = CarView::whereNotNull('user_id')
            ->distinct('car_id')
            ->count('user_id');

        return view('admin.index', [
            'role' => Auth::user()->role,
            'totalCars' => Car::count(),
            'availableCars' => Car::whereNotNull('published_at')->count(),
            'notavailableCars' => Car::whereNull('published_at')->count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalViews' => $totalViews,
            'cars' => $cars,
            'makers' => Maker::all(),
            'carTypes' => CarType::all(),
            'models' => Model::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
        ]);
    }

    public function notavailable(Request $request)
    {
        $query = Car::with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->whereNull('published_at');

        if ($request->maker_id) {
            $query->where('maker_id', $request->maker_id);
        }
        if ($request->model_id) {
            $query->where('model_id', $request->model_id);
        }
        if ($request->car_type_id) {
            $query->where('car_type_id', $request->car_type_id);
        }
        if ($request->year_from) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->year_to) {
            $query->where('year', '<=', $request->year_to);
        }
        if ($request->price_from) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->price_to) {
            $query->where('price', '<=', $request->price_to);
        }
        if ($request->mileage) {
            $query->where('mileage', '<=', $request->mileage);
        }
        if ($request->state_id) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->fuel_type_id) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        // Sorting
        if ($request->sort) {
            if ($request->sort === 'price') {
                $query->orderBy('price', 'asc');
            }
            if ($request->sort === '-price') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $cars = $query->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('car.partials.car-list', compact('cars'))->render();
        }

        return view('car.notavailable', [
            'role' => Auth::user()->role,
            'cars' => $cars,
            'makers' => Maker::all(),
            'models' => Model::all(),
            'carTypes' => CarType::all(),
            'fuelTypes' => FuelType::all(),
            'states' => State::all(),
        ]);
    }

    public function viewedCar()
    {
        // //All users
        // $cars = Car::with(['maker', 'model'])
        // ->whereNotNull('user_id')
        // ->withSum('views', 'views')
        // ->withCount(['views as unique_clicks' => function ($q) {
        //     $q->distinct('user_id');
        // }])
        // ->having('views_sum_views', '>', 0)
        // ->paginate(15);

        $cars = Car::with(['maker', 'model'])
            // Sum only views where user_id is NOT null
            ->withSum(['views as total_clicks' => function ($query) {
                $query->whereNotNull('user_id');
            }], 'views')
            // Count unique users where user_id is NOT null
            ->withCount(['views as unique_clicks' => function ($query) {
                $query->whereNotNull('user_id')->distinct('user_id');
            }])
            // Only include cars where total_clicks > 0
            ->having('total_clicks', '>', 0)
            ->paginate(15);
            
            return view('admin.viewedCar', [
                'role' => auth()->user()->role,
                'cars' => $cars,
            ]);
    }

    public function carViewers(Car $car){
        // dd($car->id);
        $carView = CarView::whereNotNull('user_id');
        $totalClicks = $carView->where('car_id', $car->id)
            ->sum('views');
        $uniqueUsers = $carView->distinct('user_id')
            ->count();

        $viewedUsers = User::select(
            'users.*',
            'cities.name as city_name',
            'states.name as state_name'
        )
        ->addSelect([
            'viewed_cars_count' => DB::table('car_views')
                ->selectRaw('COUNT(DISTINCT car_id)')
                ->whereColumn('car_views.owner_id', 'users.id')
                ->whereNotNull('car_views.user_id')
        ])
        ->leftJoin('cities', 'cities.id', '=', 'users.city_id')
        ->leftJoin('states', 'states.id', '=', 'users.state_id')
        ->whereIn('users.id', function ($query) use ($car) {
            $query->select('user_id')
                ->from('car_views')
                ->where('car_id', $car->id)
                ->whereNotNull('user_id')
                ->distinct();
        })
        ->get();
        return view('admin.carViewers', [
            'car' => $car, 
            'user'=> Auth::user(),
            'role' => Auth::user()->role, 
            'totalClicks'=> $totalClicks,
            'uniqueUsers' => $uniqueUsers,
            'viewedUsers' => $viewedUsers,
        ]);
    }

    public function show(User $user)
    {
        // dd($user);
        // Cars owned by this user
        $cars = $user->cars()
            ->withSum('views', 'views') // total clicks
            ->withCount(['views as unique_views' => function ($q) {
                $q->whereNotNull('user_id')->distinct();
            }])
            ->paginate(5);

        // Cars viewed by this user
        $viewedCars = $user->carViews()
            ->whereNotNull('car_id')
            ->select('car_id', DB::raw('SUM(views) as total_clicks'))
            ->groupBy('car_id')
            ->with(['car.maker', 'car.model', 'car.primaryImage'])
            ->paginate(5);

        // Optional: Count total cars viewed
        $viewedCarsCount = $viewedCars->count();

        $city = City::where('id', $user->city_id)->first();
        $state = State::where('id', $user->state_id)->first();

        return view('admin.show', [
            'role' => auth()->user()->role,
            'user' => $user,
            'cars' => $cars,
            'city' => $city,
            'state' => $state,
            'viewedCars' => $viewedCars,
            'viewedCarsCount' => $viewedCarsCount,
        ]);
    }

    public function users(){
        $users = User::withCount([
            'cars', // total cars owned
            'carViews as viewed_cars_count' => function ($query) {
                $query->whereNotNull('car_id');
            }
        ])
        ->paginate(5);

        return view('admin.users', [
            'role' => auth()->user()->role,
            'users' => $users
        ]);
    }

    public function feedback()
    {
        $feedback = AppFeedback::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.feedback', [
            'role' => auth()->user()->role,
            'feedback' => $feedback
        ]);
    }

    
    public function updateFeedbackStatus(Request $request, AppFeedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,rejected'
        ]);

        $feedback->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Feedback status updated successfully.');
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with('user')->latest()->paginate(5);
        $today = Carbon::now();

        // Current active subscribers (currently valid subscription)
        $currentActiveSubscribers = Subscription::where('status', 'active')
            ->where('starts_at', '<=', $today)
            ->where('expires_at', '>=', $today)
            ->count();

        // Total active subscribed users (users that have at least one active subscription, may overlap with above)
        $totalActiveSubs = Subscription::where('status', 'active')
            ->count('user_id');

        // Overall subscribed users (any subscription ever, any status)
        $overallSubscribedUsers = Subscription::distinct('user_id')->count('user_id');

        // Pending subscriptions
        $pendingSubscriptions = Subscription::where('status', 'pending')->count();

        // Cancelled subscriptions
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();

        // Overall subscription count (all subscriptions in DB)
        $overallSubscriptionCount = Subscription::count();

        
        return view('admin.subscriptions',  [
            'currentActiveSubscribers' => $currentActiveSubscribers,
            'totalActiveSubs' => $totalActiveSubs,
            'overallSubscribedUsers' => $overallSubscribedUsers,
            'pendingSubscriptions' => $pendingSubscriptions,
            'cancelledSubscriptions' => $cancelledSubscriptions,
            'overallSubscriptionCount' => $overallSubscriptionCount,
            'subscriptions' => $subscriptions,
            'role' => auth()->user()->role,
        ]);
    }

    public function updateSubscription(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,cancelled'
        ]);

        $subscription->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Subscription updated successfully.');
    }

    public function pendingCars()
    {
        $pendingCars = session()->get('pending_cars', []);

        return view('admin.pending-cars', [
            'pendingCars' => $pendingCars,
            'role' => auth()->user()->role,
        ]);
    }
}
