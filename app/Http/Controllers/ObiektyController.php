<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Obiekty;
use App\Models\Historia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ObiektyController extends Controller
{
    /**
     * Funkcja wyświetla wszystkie rekordy
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Obiekty::getAllObiekty();

        return $this->responseJson(
            $records,
            Response::HTTP_OK,
            'Rokordy pobrane pomyślnie'
        );
    }

    /**
     * Funkcja wyświetla pojedyńczy rekord
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $record = Obiekty::find($id);

        return $this->responseJson(
            $record,
            Response::HTTP_OK,
            'Rekord pomyślnie pobrany'
        );
    }

    /**
     * Funkcja dodaje rekord
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

                $obiekt = new Obiekty();
                $obiekt->numer = $request->numer;
                $obiekt->created_at = Carbon::now();
                $obiekt->status = Historia::STATUS_NOWY;
                $obiekt->save();

                $historia = new Historia();
                $historia->status_name = Historia::STATUS_NOWY;
                $historia->created_at = Carbon::now();
                $historia->obiekt_id = $obiekt->id;
                $historia->save();

            DB::commit();

        } catch(\Exception $ex) {
            DB::rollback();

            return $this->responseJson(
                [], Response::HTTP_CONFLICT,
                $ex->getMessage()
            );
        }

        return $this->responseJson(
            $obiekt,
            Response::HTTP_CREATED,
            'Obiekt dodany pomyślnie'
        );
    }

    /**
     * Funkcja edytuje rekord
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        $obiekt = Obiekty::query()
                    ->whereId($id)
                    ->with('historia')
                    ->first();

        if ( empty($obiekt) ) {
            return $this->responseJson(
                [],
                Response::HTTP_NOT_FOUND,
                'Brak rekordu o podanym id: ' . $id
            );
        }

        try {
            DB::beginTransaction();

            $historia = new Historia();
            $historia->status_name = Historia::STATUS_EDYCJA;
            $historia->created_at = Carbon::now();
            $historia->obiekt_id = $id;
            $historia->save();

            $obiekt->status = Historia::STATUS_EDYCJA;
            $obiekt->numer = $request->numer;
            $obiekt->save();

            DB::commit();
        } catch(\Exception $ex) {
            DB::rollback();

            return $this->responseJson(
                [], Response::HTTP_CONFLICT,
                $ex->getMessage()
            );
        }

        return $this->responseJson(
            $obiekt,
            Response::HTTP_OK,
            'Edycja rekordu przebiegła pomyślnie'
        );
    }

    /**
     * Funkcja usuwa rekord
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
       $obiekt = Obiekty::query()
                    ->whereId($id)
                    ->with('historia')
                    ->first();

        if ( empty($obiekt) ) {
            return $this->responseJson(
                [],
                Response::HTTP_NOT_FOUND,
                'Brak rekordu o podanym id: ' . $id
            );
        }

        try {
            DB::beginTransaction();

                $obiekt->historia()->delete();
                $obiekt->delete();

            DB::commit();
        } catch(\Exception $ex) {
            DB::rollback();

            return $this->responseJson(
                [], Response::HTTP_CONFLICT,
                $ex->getMessage()
            );
        }

        return $this->responseJson(
            [],
            Response::HTTP_OK,
            'Usunięto rekord oraz jego historię'
        );
    }

    /**
     * Funkcja pobiera obiekty według podanego numeru
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findByNumer(Request $request)
    {
        $numer = $request->numer;

        $records = Obiekty::findByNumer($numer);

        $httpCode = Response::HTTP_OK;
        $message = 'Rekordy pobrane według numeru: ' . $numer;

        if ( count($records) == 0 ) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $message = 'Brak rekordów o podanym numerze: ' . $numer;
        }

        return $this->responseJson(
            $records,
            $httpCode,
            $message
        );
    }

    /**
     * Funkcja pobiera obiekty według podanego numeru
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findByDate(Request $request)
    {
        $date = $request->date;

        $records = Obiekty::findByDate($date);

        $httpCode = Response::HTTP_OK;
        $message = 'Rekordy pobrane według daty: ' . $date;

        if ( count($records) == 0 ) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $message = 'Brak rekordów według daty: ' . $date;
        }

        return $this->responseJson(
            $records,
            $httpCode,
            $message
        );
    }

    /**
     * Funkcja pobiera obiekty według podanego statusu
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findByStatus(Request $request)
    {
        $status = strval($request->status);

        $records = Obiekty::findByStatus($status);

        $httpCode = Response::HTTP_OK;
        $message = 'Rekordy pobrane według statusu: ' . $status;

        if ( count($records) == 0 ) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $message = 'Brak rekordów według statusu: ' . $status;
        }

        return $this->responseJson(
            $records,
            $httpCode,
            $message
        );
    }

    /**
     * Funkcja pobiera obiekty według podanego statusu
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function findByHistoryStatus(Request $request)
    {
        $status = strval($request->status);

        $records = Obiekty::findByHistoryStatus($status);

        $httpCode = Response::HTTP_OK;
        $message = 'Rekordy pobrane według statusu: ' . $status;

        if ( count($records) == 0 ) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $message = 'Brak rekordów według statusu: ' . $status;
        }

        return $this->responseJson(
            $records,
            $httpCode,
            $message
        );
    }

    /**
     * Funckja wysyła odpowiedź w formacie json
     *
     * @param array $data
     * @param int $code
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    public function responseJson($data, $code, $message)
    {
        $json = [
            'status' => $code,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($json, $code);
    }
}
