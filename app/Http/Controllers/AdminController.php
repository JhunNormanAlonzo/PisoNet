<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\ProviderWalletLedger;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function decline_request(Request $request){
        $to_update = WalletTransaction::find($request->wallet_id);
        $data = [
            'approval_user_id' => auth()->user()->id,
            'trans_status' => 'DECLINE',
            'approval_tag' => 2,
            'approval_comment' => $request->approval_comment
        ];

        if ($to_update->update($data)){
            $details = [
                'body' => 'Admin has decline your wallet request.',
                'subject' => 'Request Decline'
            ];
            $status = "Sent.";
            try {
                \Mail::to($request->email)->send(new SendMail($details));
            }catch (\Exception $e){
                $status = "Not Sent.";
            }

            return redirect()->back()->with('message', 'Request declined successfully! '.$status);


        }
    }

    public function approve_request(Request $request){
        $to_update = WalletTransaction::find($request->wallet_id);
        $data = [
            'approval_user_id' => auth()->user()->id,
            'approved_at' => now()->toDateTimeString(),
            'trans_status' => 'approved',
            'approval_tag' => 1,
        ];
        if ($to_update->update($data)){
            $details = [
                'body' => 'Admin has approve your wallet request.',
                'subject' => 'Request Approve'
            ];
            $status = "Sent.";
            try {
                \Mail::to($request->email)->send(new SendMail($details));
            }catch (\Exception $e){
                $status = "Not Sent.";
            }

            return redirect()->back()->with('message', 'Request approve successfully! '.$status);


        }



    }


    public function fetchNotif(){
        $wallet_transaction_notifications = \App\Models\WalletTransaction::where('trans_status', 'pending')
            ->where('load_type_id', null)
            ->latest()
            ->get();

        return response()->json($wallet_transaction_notifications);
    }



    public function ledger(){
        $provider_wallet_ledgers = ProviderWalletLedger::all();
        return view('admin.wallet_transaction.ledger', compact('provider_wallet_ledgers'));
    }

    public function send(){
        $details = [
            'body' => 'Admin has approve your wallet request.',
            'subject' => 'Request Approve'
        ];
        $status = "Sent.";
        try {
            \Mail::to('kronia100@gmail.com')->send(new SendMail($details));
        }catch (\Exception $e){
            $status = "Not Sent.";
        }
        dd($status);

    }

}
