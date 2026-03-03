<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SellerProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->sellerProfile;

        return view('dashboard.profile', [
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:120'],
            'photo_url'    => ['nullable', 'string', 'max:2048'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'whatsapp'     => ['nullable', 'string', 'max:30'],
        ]);

        SellerProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return redirect()
            ->route('dashboard.profile.edit')
            ->with('status', 'Perfil atualizado com sucesso.');
    }
}
