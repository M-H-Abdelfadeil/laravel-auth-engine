<?php

namespace App\Http\Services;


use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GeneralService
{

    public static function safeTransaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            DB::commit();

            return $result;
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (BadRequestHttpException $e) {
            DB::rollBack();



            return ResponseService::sendBadRequest($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();

 
            return ResponseService::sendBadRequest($e->getMessage());
        }
    }
}
