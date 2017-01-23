<?php

namespace Chursina\Titlor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Chursina\Titlor\Models\Title;
use Illuminate\Support\Facades\Validator;

class TitlorController extends Controller
{
    const TITLOR_ROUTE = 'titlor';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View - titlor view
     */
    public function getTitlor()
    {
        $availableUris = Title::getAvailableUris();
        $data = Title::all();
        return view(config('titlor.views.manage'),['availableUris' => $availableUris, 'data' => $data]);
    }

    /**
     * @param Request $request - form data
     * @return \Illuminate\Http\RedirectResponse
     * updates titles and/or adds new ones
     */
    public function postTitlorManage(Request $request)
    {
        $validationRules = array();
        $availableUris = Title::getAvailableUris();
        $errorMessage = "Ошибка: неверное значение URI!";

        $validator = Validator::make($request->all(), [
            'uri.*' => 'unique:titles|max:100',
            'title.*' => 'max:100',
        ]);
         if($validator->fails()) {
             return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                 'errors' => $validator->errors()->all()]);
         }
        $input = $request->toArray();

        // delete element with csrf token
        array_pop($input);

        // array with numeric keys for convenient looping and updating
        $numericKeysInput = array();

        // filling new array
        foreach($input as $key => $value) {
            array_push($numericKeysInput, $value);
        }

        // pop elements with the new uri and title
        array_pop($numericKeysInput);
        array_pop($numericKeysInput);

        for($i = 0; $i < count($numericKeysInput); $i+=2) {
            if($i === count($input) - 2) {
                break;
            }

            // check if such a uri matches an existing pattern
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
                    'errorMsg' => "Такой URI уже существует."]);
            }

        }

        //if nothing is being added
        if($input['new_uri'] == "" && $input['new_title'] == "") {
            return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                'successMessage' => 'Успешно обновлено, ничего не добавлено.']);
        }
        // uri exists and both fields are not empty
        if(Title::uriExists($input['new_uri']) && $input['new_uri'] !== "" && $input['new_title'] !== "") {
            // create a new title
            $title = new Title;
            $title->uri = $input['new_uri'];
            $title->title = $input['new_title'];
            try {
                $title->save();
            }
            catch(QueryException $e) {
                return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                    'errorMsg' => "Такой URI уже существует."]);
            }
            // ok
            return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
                'successMessage' => 'Успешно добавлен новый заголовок!']);
        }
        // something went wrong
        return redirect()->route('titlor.index')->with(['availableUris' => $availableUris,
            'errorMsg' => 'Неверные  добавляемые параметры.']);
    }

}