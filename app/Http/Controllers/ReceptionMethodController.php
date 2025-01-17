<?php

namespace App\Http\Controllers;

use App\Models\ReceptionMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionMethodController extends Controller
{
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->is_seller) {
            abort(403, 'Only sellers can create reception methods');
        }

        $request->validate([
            'type' => 'required|string|in:iban',
            'iban_number' => 'required_if:type,iban|string|max:34',
            'iban_swift' => 'required_if:type,iban|string',
            'iban_holder_name' => 'required_if:type,iban|string',
        ]);

        $receptionMethod = new ReceptionMethod($request->all());
        $receptionMethod->user_id = $user->id;
        $receptionMethod->save();

        return to_route('user.settings')->with('message', 'Reception method created');
    }

    public function edit(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:iban',
            'iban_number' => 'required_if:type,iban|string|max:34',
            'iban_swift' => 'required_if:type,iban|string',
            'iban_holder_name' => 'required_if:type,iban|string',
        ]);
        $receptionMethod = $user->receptionMethods()->find($request->get('id'));

        if (!$receptionMethod) {
            return back()->withErrors(['id' => 'Reception method not found']);
        }

        $receptionMethod->update($request->all());
        return to_route('settings.reception-methods')->with('message', 'Reception method updated');
    }

    public function delete(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
        ]);
        $receptionMethod = $user->receptionMethods()->find($request->get('id'));

        if (!$receptionMethod) {
            return back()->withErrors(['id' => 'Reception method not found']);
        }

        $receptionMethod->delete();

        return to_route('settings.reception-methods')->with('message', 'Reception method deleted');
    }
}
