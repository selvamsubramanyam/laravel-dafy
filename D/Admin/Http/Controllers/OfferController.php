<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Offer;
use Modules\Shop\Entities\BusinessShop;
use Modules\Category\Entities\BusinessCategory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ClaimedOffer;
use App\Http\Requests\OfferStoreRequest;
use App\Http\Requests\OfferUpdateRequest;
use Modules\Product\Entities\OfferProduct;
use Auth;


class OfferController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:offer-list', ['only' => ['offer','offerList']]);
        $this->middleware('permission:offer-create', ['only' => ['offerAdd','offerStore']]);
        $this->middleware('permission:offer-edit', ['only' => ['offerEdit','offerUpdate']]);
        $this->middleware('permission:offer-delete', ['only' => ['deleteOffer']]);
    }

    public function offer()
    {
        return view('admin::Offer.offer');
    }


    public function offerList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $offer = Offer::with('shop')->orderBy('id', 'desc');
        
        if ($search != '') 
        {
            $offer->where('title', 'LIKE', '%'.$search.'%')->orWhereHas('shop',function($query) use ($search){
                $query->where('name','LIKE', '%'.$search.'%');
            });
        }

        $total = $offer->count();
        
        $result['data'] = $offer->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function offerAdd()
    {
       
        $shops = BusinessShop::/*where(['is_active' => 1])->*/orderBy('id','desc')->get();
        
        
        if(Auth::guard('seller')->check())
        {
            $shop_id = Auth::guard('seller')->id();
            $shops = BusinessShop::where(['is_active' => 1,'id' => $shop_id])->orderBy('id','desc')->get();
        }

        return view('admin::Offer.addOffer', compact('shops'));
    }


    public function offerStore(OfferStoreRequest $request)
    {


        
        if($request->hasfile('pic'))
        {
            $thumpimage = $request->pic;
            $imageName = str_replace(' ', '', time()).trim($thumpimage->getClientOriginalName());
            $imageName = str_replace(' ', '_',trim($imageName));
            $thumpimage->move(storage_path('app/offers/'), $imageName);  
            $url = "offers/".$imageName; 
        }else{
            $url = NULL;
        }

       $offer =  Offer::create([
            'seller_id' => $request->shops,
            'title' => $request->title,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_discount_value' => $request->max_discount_value,
            'min_amount_price' => $request->min_amount_price,
            'valid_from' => $request->validfrom,
            'valid_to'   => $request->validto,
            'description' => $request->description,
            'about'     => $request->about,
            'status'    => $request->status,
            'image'     => $url
        ]);

        if($offer)
        {
            if($request->has('products'))
            {
                foreach($request->products as $product)
                {
                    OfferProduct::create([
                        'shop_id'  => $offer->seller_id,
                        'product_id'  => $product,
                        'offer_id' => $offer->id,
                        'type'     => 0
                    ]);

                }
            }else{
                OfferProduct::create([
                    'shop_id'  => $offer->seller_id,
                    'offer_id' => $offer->id,
                    'type'     => 1
                ]);
            }
            return redirect()->back()->with('message', 'Offer added Successfully.');
        }else{
            return redirect()->back()->with('danger', 'Offer cannot been added please try again.');
        }
    }

    public function getProduct(Request $request)
    {
        if($request->ajax()){

            $ids = [];
            if($request->shop_id != '')
            {
                $shop_id = $request->shop_id;

                $products['data'] = Product::where('is_active' , 1)->where('seller_id',$shop_id)->whereIn('type',[0,1])->orderBy('name')->groupBy('sku')->get();
              
                return response()->json($products);
            }else{
                return response()->json('');
            }
        }
        
    }


    public function searchShopCode(Request $request)
    {
       
    	$shops = [];

        if($request->has('q')){
            $search = $request->q;
            $shops =BusinessShop::select("id", "name")
            		->where('name', 'LIKE', "%$search%")
            		->get();
        }else{
            $shops = BusinessShop::select("id", "name")->where(['is_active' => 1])->orderBy('name','asc')->get();
        }
        return response()->json($shops);
        
    }


    public function offerEdit($id)
    {
        $productIds = [];
        $offer = Offer::findOrFail($id);

        $shops = BusinessShop::where([/*'is_active' => 1,*/'id'=>$offer->seller_id])->orderBy('id','desc')->get();

        $products = Product::where('is_active' , 1)->where('seller_id',$offer->seller_id)->whereIn('type',[0,1])->orderBy('name')->groupBy('sku')->get();


        foreach($offer->offerProducts as $offer_prod)
        {
            if(!is_null($offer_prod->product_id))
                $productIds[] = $offer_prod->product_id;
        }        


        return view('admin::Offer.editOffer', compact('offer','shops','products','productIds'));
    }

    public function offerUpdate(OfferUpdateRequest $request)
    {
       
        $offer = Offer::where('id',$request->id)->with('offerProducts')->first();

        if($offer)
        {

            if($request->hasfile('pic'))
            {
                $thumpimage = $request->pic;
                $imageName = str_replace(' ', '', time()).trim($thumpimage->getClientOriginalName());
                $imageName = str_replace(' ', '_',trim($imageName));
                $thumpimage->move(storage_path('app/offers/'), $imageName);  
                $url = "offers/".$imageName; 
            }else{
                $url = $offer->image;
            }

            $offer =  Offer::updateOrCreate(['id'   => $request->id,],[
                'seller_id' => $request->shops,
                'title' => $request->title,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'max_discount_value' => $request->max_discount_value,
                'min_amount_price' => $request->min_amount_price,
                'valid_from' => $request->validfrom,
                'valid_to'   => $request->validto,
                'description' => $request->description,
                'about'     => $request->about,
                'status'    => $request->status,
                'image'     => $url
            ]);

            
            OfferProduct::where('offer_id',$offer->id)->delete();
          
            if($request->has('products'))
            {
                foreach($request->products as $product)
                {
                    OfferProduct::create([
                        'shop_id'  => $offer->seller_id,
                        'product_id'  => $product,
                        'offer_id' => $offer->id,
                        'type'     => 0
                    ]);

                }
            }else{
                OfferProduct::create([
                    'shop_id'  => $offer->seller_id,
                    'offer_id' => $offer->id,
                    'type'     => 1
                ]);
            }
       

          
            return redirect()->back()->with('message', 'Offer updated Successfully.');

        }else{
            return redirect()->back()->with('danger', 'Offer cannot been added please try again.');
        }

    }


    public function deleteOffer(Request $request,$offer_id)
    { 
        
        if($request->ajax())
        {
            OfferProduct::where('offer_id',$offer_id)->delete();

            Offer::where('id',$offer_id)->delete();
        }
    }

    //Offline Vouchers
    public function offlineVouchers()
    {   
        $shops = BusinessShop::whereDoesntHave('products')->where(['is_active' => 1])->get();
        
        return view('admin::Offer.offline_voucher_list', compact('shops'));
    }

    //offline Voucher list
    public function offlineVoucherList(Request $request)
    {
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;
        
        $offer = ClaimedOffer::with('shop', 'offer', 'user')->orderBy('id', 'desc');
        
        if ($search != '') 
        {
            $offer->orWhereHas('shop',function($query) use ($search){
                $query->where('name','LIKE', '%'.$search.'%');
            })->orWhereHas('offer',function($query) use ($search){
                $query->where('title','LIKE', '%'.$search.'%');
            });
        }

        $total = $offer->count();
        
        $result['data'] = $offer->take($request->length)->skip($request->start)->get();
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result);
    }

    public function searchVoucher(Request $request)
    {
        // dd($request->all());
        $search   = $request->search['value'];
        $sort     = $request->order;
        $column   = $sort[0]['column'];
        $order    = $sort[0]['dir'] == 'asc' ? "ASC" : "DESC" ;

        $date=date('Y-m-d');
        $fromdate=$request->fromdate;
        
        $todate=$request->todate;

        $shop=$request->shop;
   
        $offer = ClaimedOffer::with('shop', 'offer', 'user')->orderBy('id', 'desc');
        

        if($todate!=null && $fromdate!=null)
        { 
          $offer->whereBetween('created_at', [$fromdate, $todate]);
          
        }

        if($shop)
        {
            $offer->where('shop_id', $shop);
        }
       
        $total  =  $offer->count();
        
        if($request->length==null)
        {
          $request->length=1;
        }

        $result['data'] = $offer->take($request->length)->skip($request->start)->get();
        
        $result['recordsTotal'] = $total;
        $result['recordsFiltered'] =  $total;

        echo json_encode($result); 
    }

    /**
     * Export CSV for the specified list.
     * @param
     * @return Response
     */
    public function exportVoucher()
    {
        $offline_vouchers = ClaimedOffer::orderBy('id', 'ASC')->get();

        if(count($offline_vouchers) > 0) {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $fields = array('', 'Offer', 'Shop', 'User', 'Date & Time');
            $voucher_list = array();

            foreach ($offline_vouchers as $key => $offline_voucher) {

                if($offline_voucher->shop) {
                    $shop = $offline_voucher->shop->name;
                } else {
                   $shop = 'NIL'; 
                }

                $time_info = date('d M Y, h:i A', strtotime($offline_voucher->created_at));

                $voucher_list[] = array(
                        '' => '',
                        'Offer' => $offline_voucher->offer->title,
                        'Shop' => $shop,
                        'User' => $offline_voucher->user->name,
                        'Date & Time' => $time_info
                    );
            }
            
            $file_name = 'offline_voucher_list.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            Header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename = '.$file_name.'');
            
            $output = fopen('php://output', 'w');
            // fputcsv($output, $fields);
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, $fields);
            // dd('ss');
            foreach ($voucher_list as $list) {
                fputcsv($output, $list);
            }
            
            fclose($output);
        }
    }

}
