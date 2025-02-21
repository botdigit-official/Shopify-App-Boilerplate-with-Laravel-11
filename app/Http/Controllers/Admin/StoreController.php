<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $stores = Store::with('subscription')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.stores.index', compact('stores'));
    }

    public function show(Store $store): View
    {
        return view('admin.stores.show', compact('store'));
    }
}