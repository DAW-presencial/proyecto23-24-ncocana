<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function languageSwitch(Request $request)
    {
        // Get the language from the form
        $language = $request->input('language');

        // Save the language in a cookie
        return redirect()
            ->back()
            ->withCookie(cookie('preferred_language', $language));
    }
}