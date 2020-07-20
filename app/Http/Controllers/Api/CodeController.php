<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\EmailAddress;
use App\Models\SenderHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CodeController
 * @package App\Http\Controllers\Api
 */
class CodeController extends Controller
{

    public function send(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = filter_var($request->get('email'), FILTER_VALIDATE_EMAIL);
            $code = new Code();

            $model = EmailAddress::firstOrCreate(['email' => $email]);
            $model->email = $email;
            $model->code = $code->getCode();
            $model->save();

            $history = new SenderHistory();
            $history->email_address_id = $model->id;
            $history->sender_type = $code->getSenderType();
            $history->sent_at = date('U');
            $history->save();

            $isSent = $code->send($email);
            if (!$isSent)
                throw new \Exception('Cant send an email');

            DB::commit();
            $data = ['result' => 'ok', 'message' => 'Code is sent'];
            return response()->json($data);
        } catch (\Throwable $exception){
            DB::rollBack();
            Log::critical('CodeController->sendCode failed: ' . $exception->getMessage());
            $data = ['result' => 'error', 'message' => 'application error'];
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = filter_var($request->get('email'), FILTER_VALIDATE_EMAIL);
            $code  = filter_var($request->get('code'), FILTER_VALIDATE_INT);

            /**
             * @var EmailAddress $model
             */
            $model = EmailAddress::where('email', $email)->first();

            if (!$model){
                throw new \InvalidArgumentException('email not found');
            } elseif (!$model->checkCode($code)){
                throw new \InvalidArgumentException('wrong code');
            }

            DB::commit();
            $data = ['result' => 'ok'];
            return response()->json($data);
        } catch (\InvalidArgumentException $exception){

            DB::commit();
            Log::error('CodeController->checkCode failed: ' . $exception->getMessage());
            $data = ['result' => 'error', 'message' => $exception->getMessage()];
            return response()->json($data);
        } catch (\Throwable $exception){

            DB::rollBack();
            Log::critical('CodeController->checkCode failed: ' . $exception->getMessage());
            $data = ['result' => 'error', 'message' => 'application error'];
            return response()->json($data);
        }
    }
}
