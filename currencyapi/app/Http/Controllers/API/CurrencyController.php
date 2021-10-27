<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Currency;
use App\Http\Resources\Currency as CurrencyResource;
   
class CurrencyController extends BaseController
{
    //lists all currencies {api/currencies}
    public function index()
    {
        $currency = Currency::all();
        return $this->sendResponse(CurrencyResource::collection($currency), 'Currencies data fetched.');
    }
    
    //creates dummy data {api/currencies/create}
    public function create()
    {
        $currency = Currency::all();
        if($currency->count()<=0){
            $data = [
                ['name'=>'Dollar', 'code'=> '$', 'value'=>1.00],
                ['name'=>'Euro', 'code'=> 'E', 'value'=>0.86],
                ['name'=>'Krone', 'code'=> 'Kr', 'value'=>8.62],
                ['name'=>'Rupees', 'code'=> 'Rp', 'value'=>175.6],
            ];
            Currency::insert($data);
            return $this->sendResponse(CurrencyResource::collection($currency), 'Dummy Currencies created.');
        } else{
            return $this->sendResponse(CurrencyResource::collection($currency), 'Currencies data already exist.');
        }   
    }

    //shows all exchange rates for given currency {api/currencies/id}
    public function show($id) 
    {
        $currency = Currency::find($id);
        if (is_null($currency)) {
            return $this->sendError('Currency does not exist.');
        }

        $data = [
            'currency'=>$currency->name, 
            'exchange_rates'=>[],
        ];

        $currencies = Currency::where('id', '!=' , $id)->pluck('value','name');
        foreach($currencies as $name=>$value){
            $data['exchange_rates'][] = ['name'=>$name, 'value'=>number_format($value/$currency->value,2)];
        }

        return json_encode($data);
    }
}