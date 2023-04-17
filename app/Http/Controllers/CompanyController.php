<?php

namespace App\Http\Controllers;

use App\DataTables\CompanyDataTable;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Instance;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
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
     * @return Application|Factory|View
     */
    public function index(Request $request): Application|Factory|View|RedirectResponse
    {
        $companyId = intval($request->company_id);

        $findAndCheck = Company::whereHas('customer',function ($query) {
            $query->where('id',auth()->user()->id);
        })->where('id',$companyId)->first();

        if(!$findAndCheck) {
            return redirect()->route('404');
        }

        return view('common.instances',['companyId' => $companyId]);
    }

    public function getInstances(Request $request, $company_id): bool|JsonResponse
    {
        if ($request->ajax()) {

            $data = Instance::with('company');
            $data = $data->where('company_id',$company_id);

            try {
                return Datatables::eloquent($data)
                    ->orderColumn('status', function ($query, $order) {
                        $query->orderBy('status', $order);
                    })
                    ->filter(function ($query) {
                        if (request()->get('id')) {
                            $query->where('id', '=', request('id'));
                        }

                        if (request()->get('instance_id')) {
                            $query->where('instance_id', 'like', "%" . request('instance_id') . "%");
                        }

                        if (request()->get('token')) {
                            $query->where('token', 'like', "%" . request('token') . "%");
                        }

                        if (request()->get('status') && in_array(request('status'),array_keys(config('global.status')))) {
                            $query->where('status', '=', request('status'));
                        }

                        if(request()->get('last_login')) {
                            $lastLoginDateRange = explode('/',request()->get('last_login'));

                            $beginDate = $lastLoginDateRange[0];
                            $endDate = $lastLoginDateRange[1];

                            $query->whereDate('last_login', '>=',$beginDate)->whereDate('last_login','<=',$endDate);
                        }

                    })->smart(false)->startsWithSearch()
                    ->addIndexColumn()
                    ->addColumn('token',function ($row){
                        return $row->token;
                    })
                    ->addColumn('last_login',function ($row){
                        return $row->last_login ? Carbon::createFromFormat('Y-m-d H:i:s', $row->last_login)->format('Y-m-d H:i:s') : '-';
                    })
                    ->addColumn('status',function ($row){
                        return $row->status == 1 ? 'Enable' : 'Disable';
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Login" class="btn btn-info btn-sm login-instances">Login</a>';

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
