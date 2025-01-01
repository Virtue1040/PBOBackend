<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Studio;
use App\Models\Ticket;
use Carbon\Carbon;

class OrderController extends Controller
{
    function __construct() {
        \Midtrans\Config::$serverKey = env("Midtrans_ServerKey");
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $request->validate([
            'ticket' => ['required', 'array'],
            'ticket.*.id_showtime' => ['required', 'integer', 'exists:showtimes,id'],
            'ticket.*.seatNumber' => ['required', 'string'],
        ]);

        $user = $request->user();
        $params = array(
            "payment_type" => "qris",
            "qris" => array(
                "acquirer" => "gopay"
            ),
            'item_details' => array(),
            "expiry" => array(
                "unit" => "minutes",
                "duration" => 5
            ),
            "redirect_url" => "https://www.google.com"
        );
        $totalPrice = 0;

        $makeOrderId = "ORDER-TICKET-" . rand();
        $order = Order::create([
            'id_order' => $makeOrderId,
            'id_user' => $user->id,
            'order_type' => 'Ticket',
            'order_nominal' => $totalPrice,
            'status' => 'pending'
        ]);

        foreach ($request->input('ticket') as $ticket) {
            $getShowtime = Showtime::find($ticket['id_showtime']);
            $getMovie = Movie::find($getShowtime['id_movie']);
            $getStudio = Studio::find($getShowtime['id_studio']);
            $getTime = $getShowtime->time;
            $getPrice = $getMovie->price;
            $getSeatnumber = $ticket['seatNumber'];
            $totalPrice += $getPrice;
            array_push($params['item_details'], [
                'id' => $getShowtime,
                'id_user' => $user->id,
                'seatNumber' => $getSeatnumber,
                'price' => $getPrice,
                'name' => $getMovie->title,
                'quantity' => 1,
            ]);

            $getTicketAfter5Minute = Ticket::where("id_showtime", $request->ticket[0]["id_showtime"])
                    ->where("status", "unpaid")
                    ->where("id_order", '!=', $makeOrderId)
                    ->where("seatNumber", $getSeatnumber)
                    ->where("created_at", '<=', Carbon::now()->subMinutes(5))
                    ->first();

                    if ($getTicketAfter5Minute) {
                        $getTicketAfter5Minute->delete();
                    }

            Ticket::create([
                'id_order' => $makeOrderId,
                'id_user' => $user->id,
                'id_showtime' => $ticket['id_showtime'],
                'seatNumber' => $getSeatnumber,
                'price' => $getPrice,
                'status' => 'unpaid'
            ]);
        }

        array_push($params['item_details'], [
            'id' => 1000,
            'price' => 4000,
            'name' => "Biaya Layanan",
            'quantity' => 1,
        ]);
        $totalPrice += 4000;

        $getOrder = Order::where("id_order", $makeOrderId)->first();
        $getOrder->order_nominal = $totalPrice;
        $getOrder->save();

        $transaction = Transaction::create([
            'id_order' => $makeOrderId,
            'id_transaction' => 0,
            'amount' => $totalPrice,
            'status' => 'unpaid',
        ]);

        $params['transaction_details'] = array(
            'order_id' => $makeOrderId,
            'gross_amount' => $totalPrice,
        );

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat snap midtrans',
                'data' => [
                    'token' => $paymentUrl,
                    'id_order' => $makeOrderId
                ]
            ]);
        }
        catch (Exception $e) {
          echo $e->getMessage();
        }
    }

    public function returnStatus($transaction_status) {
        switch ($transaction_status) {
            case "capture":
                    return true;
                break;
            case "settlement":
                    return true;
                break;
        }
        return false;
    }

    public function getStatus($id) {
        $transactions = \Midtrans\Transaction::status($id);

        return $transactions;
    }

    public function callback(Order $order, Request $request)
    {
        $order_id = $request->input('order_id');
        $transaction_id = $request->input("transaction_id");
        $status_code = $request->input('status_code');
        $gross_amount = $request->input('gross_amount');
        $transaction_status = $request->input('transaction_status');
        $signature_key = $request->input('signature_key');
        if (isset($signature_key)) {
            $ServerKey = \Midtrans\Config::$serverKey;

            $getSignature = hash('sha512', ($order_id . $status_code . $gross_amount . $ServerKey));
            if ($signature_key === $getSignature) {
                $order = Order::where("id_order", $order_id)->first();
                if ($this->returnStatus($transaction_status)) {
                    if ($order) {
                        $order->status = "approved";
                        $order->save();

                        foreach ($order->getTickets as $ticket) {
                            $ticket->status = "paid";
                            $ticket->save();
                        }
                    }
                } else {
                    if ($order) {
                        $order->status = $transaction_status;
                        $order->save();

                        if ($transaction_status !== "pending") {
                            foreach ($order->getTickets as $ticket) {
                                $ticket->delete();
                            }
                        } else {

                        }
                    }
                }
            }
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
