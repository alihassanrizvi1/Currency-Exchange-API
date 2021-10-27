<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Currency;
use App\Http\Resources\Currency as CurrencyResource;
   
class ExchangeController extends BaseController
{
    //exchanges given value from one currency to another
    //POST {api/exchange} params={from (currency id to exchange from), to(currency id to exchange to), value}
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'from' => 'required',
            'to' => 'required',
            'value' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $currency_from = Currency::find($input['from']);
        if (is_null($currency_from)) {
            return $this->sendError('Currency From does not exist.');
        }

        $currency_to = Currency::find($input['to']);
        if (is_null($currency_to)) {
            return $this->sendError('Currency To does not exist.');
        }

        $conversion = number_format($currency_to->value/$currency_from->value,2);
        $value = $input['value'];

        return $result = $conversion*$value;
    }
}