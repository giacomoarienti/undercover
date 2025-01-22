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
           return to_route('settings')->withErrors(['id' => 'You are not a seller']);
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

        // if it's the first method
        if ($user->receptionMethods()->count() == 1) {
            $user->reception_method_id = $receptionMethod->id;
            $user->save();
        }

        return to_route('settings')->with('message', 'Reception method created');
    }

    public function edit(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
            'default' => 'boolean'
        ]);
        $receptionMethod = $user->receptionMethods()->find($request->get('id'));

        if (!$receptionMethod) {
            return back()->withErrors(['id' => 'Reception method not found']);
        }

        if($request->has('default')) {
            $user->reception_method_id = $receptionMethod->id;
            $user->save();
        }

        $receptionMethod->update($request->all());
        return to_route('settings')->with('message', 'Reception method updated');
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

        if($receptionMethod->default) {
            return to_route('settings')->withErrors(['id' => 'Cannot delete default reception method']);
        }


        $receptionMethod->delete();

        return to_route('settings')->with('message', 'Reception method deleted');
    }
}
