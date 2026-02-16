<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;


class AdminAuthController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.email');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $status = Password::broker('admins')->sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Erreur lors de l\'envoi de l\'email.']);
        }
    }

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::broker('admins')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => bcrypt($request->password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('admin.login')->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
}


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate using the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $adminUser = Auth::guard('admin')->user();

            // Check if the user is inactive
            if ($adminUser->status === 'inactif') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Votre compte est inactif. Veuillez contacter l\'administrateur.',
                ])->onlyInput('email');
            }

            // Increment total_logins for the user without touching updated_at
            $adminUser->timestamps = false;
            $adminUser->total_logins = ($adminUser->total_logins ?? 0) + 1;
            $adminUser->save();

            $adminUser->timestamps = true;

            $request->session()->regenerate();
            
            // Set default guard to admin so Laravel session handler picks up the user_id
            config(['auth.defaults.guard' => 'admin']);

            // Manually ensure the session record has the user_id
            $sid = $request->session()->getId();
            if ($sid) {
                DB::table('sessions')->where('id', $sid)->update([
                    'user_id' => $adminUser->id,
                    'last_activity' => time()
                ]);
            }

            $request->session()->put('admin_user', [
                'id' => $adminUser->id,
                'name' => $adminUser->name,
                'email' => $adminUser->email,
                'role' => $adminUser->role,
                'avatar' => $adminUser->avatar,
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'L\'adresse e-mail ou le mot de passe est incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->forget('admin_user');

        return redirect()->route('admin.login');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.required' => 'Le champ du mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'email.required' => 'Le champ de l\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être une adresse valide.',
        ];
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
   protected function sendResetResponse(Request $request, string $status)
{
    return redirect()->route('admin.login')
        ->with('status', __($status));
}

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the password reset email subject.
     *
     * @return string
     */

    protected $subject = 'Réinitialisation du mot de passe';
    protected function getResetEmailSubject()
    {
        return property_exists($this, 'subject')
            ? $this->subject
            : 'Réinitialisation du mot de passe';
    }

}