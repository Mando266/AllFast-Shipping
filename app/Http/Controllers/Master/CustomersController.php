<?php

namespace App\Http\Controllers\Master;

use App\ContactPerson;
use App\Filters\Customers\UserIndexFilter;
use App\Models\Master\Country;
use App\Models\Master\CustomerRoles;
use App\Models\Master\Customers;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\Master\Currency;
use App\Models\Master\RoleCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Customers::class);
            $customers = Customers::filter(new UserIndexFilter(request()))->where('company_id',Auth::user()->company_id)->with('CustomerRoles.role')->orderBy('id')->paginate(30);
            $exportCustomers = Customers::select('id','name','contact_person','phone','tax_card_no','landline','country_id','city','email','address','cust_address','sales_person_id','customer_role_id','customer_kind')->filter(new UserIndexFilter(request()))->where('company_id',Auth::user()->company_id)->with('CustomerRoles.role')->orderBy('id')->get();
            $customer = Customers::where('company_id',Auth::user()->company_id)->get();
            $countries = Country::orderBy('name')->get();
            session()->flash('customers',$exportCustomers);
        return view('master.customers.index',[
            'items'=>$customers,
            'customer'=>$customer,
            'countries'=>$countries
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $countries = Country::orderBy('name')->get();
        $customer_roles = RoleCustomer::all();
       // dd($customer_roles);
        $currency = Currency::get();
        $users = User::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        return view('master.customers.create',[
            'countries'=>$countries,
            'customer_roles'=>$customer_roles,
            'currency'=>$currency,
            'users'=>$users,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        if ($request->input('customer_kind') == 1){
        $request->validate([
            'name' => 'required',
            'country_id' => ['required'],
            'city' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
            'address' => ['required'],
            'currency' => ['required'],
            'othercurrency' => ['different:currency'],
            'customer_kind' => ['required'],
        ],[
            'othercurrency.different'=>'Other Currency The Same Currency',

        ]);
        }else{
            $request->validate([
                'name' => 'required',
                'country_id' => ['required'],
                'customer_kind' => ['required'],
            ]);
        }
        $user = Auth::user();

        $NameDublicate  = Customers::where('company_id',$user->company_id)->where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','Customer Name Already Exists');
        }

        $country = Country::where('id',$request->country_id)->pluck('prefix')->first();
        $code = $country.$request->input('name').'-';
        $customers = Customers::create([
            'code'=> "",
            'name'=> $request->input('name'),
            'tax_card_no'=> $request->input('tax_card_no'),
            'phone'=> $request->input('phone'),
            'landline'=> $request->input('landline'),
            'currency'=> $request->input('currency'),
            'othercurrency'=> $request->input('othercurrency'),
            'city'=> $request->input('city'),
            'email'=> $request->input('email'),
            'country_id'=> $request->input('country_id'),
            'sales_person_id'=> $request->input('sales_person_id'),
            'company_id'=> $request->input('company_id'),
            'contact_person'=>$request->input('contact_person'),
            'address'=>$request->input('address'),
            'cust_address'=>$request->input('cust_address'),
            'customers_website'=>$request->input('customers_website'),
            'fax'=>$request->input('fax'),
            'notes'=>$request->input('notes'),
            'company_id'=>$user->company_id,
            'customer_kind'=>$request->input('customer_kind'),
        ]);
        $customers->storeContactPeople(request()->contactPeople);
        foreach($request->input('customerRole',[]) as $customerRole){
            CustomerRoles::create([
                'customer_id'=>$customers->id,
                'role_id'=>$customerRole['role_id'],
            ]);
        }
        $code .=$customers->id;
        $customers->code = $code;
        $customers->save();
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $customers->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('customers.index')->with('success',trans('Customer.created'));
    }

    public function show(Customers $customer)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $customer = $customer->load('country');
        $customer_roles = CustomerRoles::where('customer_id',$customer->id)->with('role')->get();
        $roles = RoleCustomer::all();
        return view('master.customers.show',[
            'customer'=>$customer,
            'customer_roles'=>$customer_roles,
            'roles'=>$roles,
        ]);
    }

    public function edit(Customers $customer)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $countries = Country::orderBy('name')->get();
        $customer_roles = CustomerRoles::where('customer_id',$customer->id)->with('role')->get();
        $currency = Currency::get();
        $users = User::orderBy('id')->get();
        $customer_role_id = explode(", ", $customer->customer_role_id);
        $roles = RoleCustomer::all();
        return view('master.customers.edit',[
            'customer'=>$customer,
            'roles'=>$roles,
            'countries'=>$countries,
            'customer_roles'=>$customer_roles,
            'users'=>$users,
            'currency'=>$currency,
            'customer_role_id'=>$customer_role_id,
            'contactPeople' => $customer->contactPeople
        ]);
    }

    public function update(Request $request, Customers $customer)
    {
        $customer->storeContactPeople(request()->contactPeople);
        if ($request->input('customer_kind') == 1){
        $request->validate([
            'name' => 'required',
            'country_id' => ['required'],
            'city' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
            'address' => ['required'],
            'currency' => ['required'],
            'othercurrency' => ['different:currency'],
            'customer_kind' => ['required'],
        ],[
            'othercurrency.different'=>'Other Currency The Same Currency',

        ]);
        }else{
            $request->validate([
                'name' => 'required',
                'country_id' => ['required'],
                'customer_kind' => ['required'],
            ]);
        }
        $user = Auth::user();

        $NameDublicate  = Customers::where('id','!=',$customer->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','Customer Name Already Exists');
        }
        $this->authorize(__FUNCTION__,Customers::class);
            $inputs = request()->except('customerRole', 'contactPeople', '_token', 'removed');
            $customer->update($inputs);
            CustomerRoles::destroy(explode(',',$request->removed));
            $customer->createOrUpdateRoles($request->customerRole);
            if($request->hasFile('certificat')){
                $path = $request->file('certificat')->getClientOriginalName();
                $request->certificat->move(public_path('certificat'), $path);
                $customer->update(['certificat'=>"certificat/".$path]);
            }
        return redirect()->route('customers.index')->with('success',trans('customer.updated.success'));
    }

    public function destroy($id)
    {
        $customer =Customers::Find($id);
        CustomerRoles::where('customer_id',$id)->delete();
        $customer->delete();
        return redirect()->route('customers.index')->with('success',trans('customer.deleted.success'));
    }

}
