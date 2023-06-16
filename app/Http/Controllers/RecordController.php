<?php

namespace App\Http\Controllers;

use App\Events\RecordAdded;
use App\Models\Record;
use App\Models\RecordStatus;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        $data = $request->validate([
            'tz' => 'timezone'
        ]);

        if(!isset($data['tz'])) {
            $data['tz'] = config('app.timezone');
        }

        $records = Record::with(['recordStatus', 'recordStatus.translations', 'user', 'subscription']);
        $recordsCount = null;

        if($user->isCoworker()) {
            $records = $records->where(
                'subscription_id', $user->subscription->id
            );

            $recordsCount =  Record::where(
                'subscription_id',
                Subscription::find($user->subscription_id)->id
            )->count();
        } else {
            $records = $records->where('user_id', $user->id);
        }

        $records = $records->orderByRaw("
            CASE WHEN " . \DB::getPdo()->quote("timestamp") . " > CURRENT_TIMESTAMP THEN 0 ELSE 1 END,
            ABS(EXTRACT(EPOCH FROM CURRENT_TIMESTAMP - " . \DB::getPdo()->quote("timestamp") . "))
        ")->paginate(10);

        return view('records', [
            'records' => $records,
            'recordsCount' => $recordsCount,
            'tz' => $data['tz'],
            'statuses' => RecordStatus::withTranslations()->get(),
        ]);
    }

    public function count($lang, $sub) {
        /** @var User $user */
        $user = auth()->user();
        abort_unless($user->isCoworker() || $user->isAdmin(), Response::HTTP_UNAUTHORIZED);
        return response()->json(['count' => Record::where('subscription_id', $sub)->count()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /** @var User */
        $user = auth()->user();
        if($user->isCoworker()) {
            return redirect()->route('record.index');
        }

        return view('add-record', [
            'subscription' => Subscription::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sub = Subscription::class;

        $data = $request->validate([
            'title' => 'required|string',
            'datetime' => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'timezone' => 'required|timezone',
            'duration' => 'required|numeric',
            'subscription' => "required|exists:{$sub},id",
            'lits' => 'nullable|string'
        ]);

        $date = Carbon::createFromFormat('Y-m-d\TH:i', $data['datetime'], $data['timezone']);
        $date->setTimezone(config('app.timezone'));

        $status = RecordStatus::where('isDefault', true)->first() ?? RecordStatus::first();

        Subscription::find($data['subscription'])->records()->create([
            'title' => $data['title'],
            'timestamp' => $date,
            'duration' => $data['duration'],
            'lits' => $data['lits'],
            'user_id' => auth()->user()->id,
            'record_status_id' => $status->id
        ]);

        if(config('app.ws')){
            RecordAdded::dispatch($data['subscription']);
        }

        return back()->with('success', trans('Record was created!'));
    }

    public function search(Request $request) {
        $baseUrl = 'http://www.lib.ukgu.kz/cgi-bin/irbis64r_01/cgiirbis_64.exe';
        if(!$request->has('q')) {
            return response()->json([]);
        }

        try {
            $q = urlencode($request->query('q'));

            $response = Http::get("$baseUrl?q=$q&C21COM=T&T21PRF=K%3D&I21DBN=KNIGI");

            if(!$response->ok()) {
                return response()->json(['error' => $response->reason()], 500);
            }

            $text = $response->body();

            $text = array_map(
                function($t) {
                    $t = explode('|', trim($t));
                    return $t[0] ?: null;
                },
                mb_split('\r\n', strip_tags($text))
            );

            $text = array_filter(
                $text,
                fn($t) => !!$t
            );

            return response()->json($text);
        } catch (Exception $e) {
            \Log::warning($e);
            return response()->json([]);
        }
    }
}
