<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\Payment;
use App\Models\Course;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createPayment(Request $request)
    {
        try {
            $course = Course::findOrFail($request->course_id);
            $user = Auth::user();
            
            // Generate unique order ID
            $order_id = 'ORDER-' . $request->course_id . '-' . time() . '-' . Str::random(5);
            
            // Customer details
            $customer_details = [
                'first_name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08123456789'
            ];
            
            // Item details
            $item_details = [
                [
                    'id' => $course->id,
                    'price' => (int) $course->price,
                    'quantity' => 1,
                    'name' => $course->title
                ]
            ];

            $exists = CourseEnrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->exists();

            if (!$exists) {
                CourseEnrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now(),
                ]);
            }
            
            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'order_id' => $order_id,
                'amount' => $course->price,
                'payment_status' => 'pending'
            ]);
            
            // Get snap token
            $snapToken = $this->midtransService->createTransaction(
                $order_id,
                (int) $course->price,
                $customer_details,
                $item_details
            );
            
            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order_id
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notification = $this->midtransService->notification();
            
            $order_id = $notification->order_id;
            $transaction_status = $notification->transaction_status;
            $payment_type = $notification->payment_type;
            $transaction_id = $notification->transaction_id;
            $fraud_status = $notification->fraud_status ?? null;
            
            // Find payment record
            $payment = Payment::where('order_id', $order_id)->firstOrFail();
            
            // Update payment based on transaction status
            if ($transaction_status == 'capture') {
                if ($payment_type == 'credit_card') {
                    if ($fraud_status == 'challenge') {
                        $payment->payment_status = 'pending';
                    } else if ($fraud_status == 'accept') {
                        $payment->payment_status = 'settlement';
                        $payment->settlement_time = now();
                    }
                }
            } else if ($transaction_status == 'settlement') {
                $payment->payment_status = 'settlement';
                $payment->settlement_time = now();
            } else if ($transaction_status == 'pending') {
                $payment->payment_status = 'pending';
            } else if ($transaction_status == 'deny') {
                $payment->payment_status = 'failure';
            } else if ($transaction_status == 'expire') {
                $payment->payment_status = 'expire';
            } else if ($transaction_status == 'cancel') {
                $payment->payment_status = 'cancel';
            }
            
            $payment->transaction_id = $transaction_id;
            $payment->transaction_status = $transaction_status;
            $payment->payment_type = $payment_type;
            $payment->transaction_time = now();
            $payment->midtrans_response = $notification->getResponse();
            $payment->save();
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $payment = Payment::where('order_id', $request->order_id)->firstOrFail();

        $payment->update([
            'payment_status' => 'settlement',
            'transaction_id' => $request->transaction_id,
            'transaction_status' => $request->transaction_status,
            'payment_type' => $request->payment_type,
            'transaction_time' => now(),
            'settlement_time' => now(),
            'midtrans_response' => json_encode($request->all()),
        ]);

        return response()->json(['message' => 'Payment updated']);
    }

    public function confirm(Request $request)
    {
        try {
            $orderId = $request->input('order_id');

            // Ambil status transaksi dari Midtrans
            $status = \Midtrans\Transaction::status($orderId);

            // Ubah jadi array jika bukan object (jaga-jaga)
            if (is_array($status)) {
                $status = json_decode(json_encode($status)); // ubah ke object
            }

            $payment = Payment::where('order_id', $orderId)->firstOrFail();

            $payment->update([
                'transaction_id'     => $status->transaction_id ?? null,
                'transaction_status' => $status->transaction_status ?? null,
                'payment_type'       => $status->payment_type ?? null,
                'payment_status'     => ($status->transaction_status ?? '') === 'settlement'
                    ? 'settlement'
                    : ($status->transaction_status ?? 'pending'),
                'transaction_time'   => now(),
                'settlement_time'    => $status->settlement_time ?? null,
                'midtrans_response'  => json_encode($status)
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function finish(Request $request)
    // {

    //     // Ambil data payment
    //     $payment = Payment::where('order_id', $orderId)->first();


    //     // Enroll user jika belum
    //     $userId = Auth::id() ?? $payment->user_id;
    //     $courseId = $payment->course_id;

    //     $alreadyEnrolled = \App\Models\CourseEnrollment::where('user_id', $userId)
    //         ->where('course_id', $courseId)
    //         ->exists();

    //     if (!$alreadyEnrolled) {
    //         \App\Models\CourseEnrollment::create([
    //             'user_id' => $userId,
    //             'course_id' => $courseId,
    //             'enrolled_at' => now()
    //         ]);
    //     }

    //     return redirect()->route('courses.show', $courseId)
    //         ->with('success', 'You have successfully enrolled after payment.');
    // }

    public function success()
    {
        return view('payment.success');
    }
}