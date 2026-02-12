<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\UserRequest;
use App\Mail\ForgotPasswordMail;
use App\Services\Log;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials))
                return $this->unauthorized(
                    'E-mail ou senha inválidos.',
                    'Não foi possível realizar o login'
                );

            /** @param User $user */
            $user = Auth::user();
            $token = $user->createToken('login')->plainTextToken;

            Log::info('Logando ' . $user->email);
            return $this->success(['token' => $token], 'Logado com sucesso');
        } catch (\Exception $e) {

            Log::Error($e->getMessage(), $request->email);
            return $this->serverError(
                $e->getMessage(),
                'Erro ao logar'
            );
        }
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();

            if (!$user->remember_token) {
                $user->remember_token = Str::random(60);
                $user->save();
            }

            $return = Mail::to($user->email)->send(new ForgotPasswordMail($user));
            Log::Email('Esqueci Senha', $user->email);

            return $this->success(
                $return,
                'Enviamos as instruções de redefinição de senha para o seu e-mail.',
                'Tudo certo!'
            );
        } catch (\Exception $e) {

            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao enviar instrução de redefinição'
            );
        }
    }

    public function reset(ResetPassword $request)
    {
        try {
            $user = User::where([
                'remember_token' => $request->token,
            ])->first();

            if (!$user)
                $this->unauthorized('Usuário não localizado!');

            $user->password = bcrypt($request->password);
            $user->remember_token = null;

            $user->save();
            Log::Info('Logout User ' . $user->email, 'Logout');

            return $this->success(
                $user,
                'Senha alterada com sucesso',
                'Tudo certo!'
            );
        } catch (\Exception $e) {

            Log::Error($e, $request->all(), $user->id);
            return $this->serverError(
                $e->getMessage(),
                'Erro ao resetar sua senha.'
            );
        }
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        Log::Info('Logout User ' . Auth::user()->email, 'Logout');
        Auth::user()->tokens()->delete();
        return $this->success([], 'Usuário deslogado');
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();

            return $this->success($user, 'Senha alterada.');
        } catch (\Exception $e) {

            Log::Error($e->getMessage(), $request->all());
            return $this->serverError(
                $e->getMessage(),
                'Erro ao atualizar user'
            );
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $return = $user->toArray();
        $return['addresses'] = $user->myAddresses();
        return $return;
    }
}
