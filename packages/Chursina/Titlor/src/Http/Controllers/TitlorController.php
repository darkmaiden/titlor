<?php

namespace Chursina\Titlor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Chursina\Titlor\Models\Title;

class TitlorController extends Controller
{
    const TITLOR_ROUTE = 'titlor';

    public function getTitlor()
    {
        $availableUris = Title::getAvailableUris();
        $data = Title::all();
        return view(config('titlor.views.manage'),['availableUris' => $availableUris, 'data' => $data]);
    }

    public function postTitlorManage(Request $request)
    {
        $validationRules = array();

        $input = $request->toArray();
        $availableUris = Title::getAvailableUris();
        $errorMessage = "Ошибка: неверное значение URI!";

        array_pop($input);
        $numericKeysInput = array();

        foreach($input as $key => $value) {
            array_push($numericKeysInput, $value);
            array_push($validationRules, [$key => 'max:50']);
        }
        array_pop($numericKeysInput);
        array_pop($numericKeysInput);

        $this->validate($request, $validationRules);

        for($i = 0; $i < count($numericKeysInput); $i+=2) {
            if($i === count($input) - 2) {
                break;
            }
            if(!Title::uriExists($numericKeysInput[$i])) {
                return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                    'errorMsg' => $errorMessage]);
            }
            $title = Title::where('uri', '=', $numericKeysInput[$i])->first();
            if(!$title) {
                break;
            }

            $title->title = $numericKeysInput[$i + 1];
            try {
                $title->save();
            }
            catch(QueryException $e) {
                return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                    'errorMsg' => $e->getMessage()]);
            }

        }

        if($input['new_uri'] == "" && $input['new_title'] == "") {
            return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                'successMessage' => 'Успешно обновлено, ничего не добавлено.']);
        }
        
        if(Title::uriExists($input['new_uri'])) {
            $title = new Title;
            $title->uri = $input['new_uri'];
            $title->title = $input['new_title'];
            try {
                $title->save();
            }
            catch(QueryException $e) {
                return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                    'errorMsg' => $e->getMessage()]);
            }

            return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                'successMessage' => 'Успешно добавлен новый заголовок!']);
        }

        return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
            'errorMsg' => $errorMessage]);
    }

}