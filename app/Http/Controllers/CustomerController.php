<?php

namespace App\Http\Controllers;

use App\DataTables\CompanyDataTable;
use App\Models\Company;
use App\Models\Package;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $packages = Package::query()->select(['id','name','limit'])->get();

        return view('common.companies',['packages' => $packages]);
    }

    public function getCompanies(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::with('package');
            $data = $data->where('customer_id',auth()->user()->id);
            try {
                return Datatables::eloquent($data)
                    ->orderColumn('packages.name', function ($query, $order) {
                        $query
                            ->leftJoin('packages', 'companies.package_id', '=', 'packages.id')
                            ->orderBy('packages.name', $order)
                            ->select('companies.*');
                    })
                    ->orderColumn('status', function ($query, $order) {
                        $query->orderBy('status', $order);
                    })
                    ->filter(function ($query) {
                        if (request()->get('id')) {
                            $query->where('id', '=', request('id'));
                        }

                        if (request()->get('name')) {
                            $query->where('name', 'like', "%" . request('name') . "%");
                        }

                        if (request()->get('package_id') && request()->get('package_id') > 0) {
                            $query->whereHas('package', function (Builder $queryHas) {
                                $queryHas->where('id', '=', request('package_id'));
                            });
                        }

                        if (request()->get('status') && in_array(request('status'),array_keys(config('global.status')))) {
                            $query->where('status', '=', request('status'));
                        }

                    })->smart(false)->startsWithSearch()
                    ->addIndexColumn()
                    ->addColumn('packages',function (Company $company){
                        return $company->package->name." - ".$company->package->limit;
                    })
                    ->addColumn('status',function ($row){
                        return $row->status == 1 ? 'Enable' : 'Disable';
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Login" class="btn btn-info btn-sm deleteProduct">Login</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }
}
