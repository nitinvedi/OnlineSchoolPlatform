<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Notifications\CourseEnrolled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Request $request, Course $course)
    {
        // Check if user is already enrolled
        if (Auth::user()->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You are already enrolled in this course.');
        }

        // Check if course has a price
        if (!$course->price || $course->price <= 0) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'This course is not available for purchase.');
        }

        try {
            $checkoutSession = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $course->title,
                            'description' => substr($course->description, 0, 500),
                            'images' => $course->thumbnail_url ? [$course->getThumbnailSrcAttribute()] : [],
                        ],
                        'unit_amount' => (int)($course->price * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('courses.show', $course->slug),
                'metadata' => [
                    'course_id' => $course->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            // Create payment record
            Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'stripe_session_id' => $checkoutSession->id,
                'amount' => $course->price,
                'currency' => 'USD',
                'status' => 'pending',
            ]);

            return redirect($checkoutSession->url);
        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Payment setup failed. Please try again.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment session.');
        }

        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);

            $payment = Payment::where('stripe_session_id', $sessionId)->first();

            if (!$payment) {
                return redirect()->route('dashboard')->with('error', 'Payment record not found.');
            }

            if ($session->payment_status === 'paid') {
                $payment->update(['status' => 'completed']);

                // Create enrollment
                $payment->user->enrolledCourses()->attach($payment->course_id, [
                    'enrolled_at' => now(),
                    'progress_percentage' => 0,
                ]);

                // Send enrollment notification
                $payment->user->notify(new CourseEnrolled($payment->course));

                return redirect()->route('dashboard')
                    ->with('success', 'Payment successful! You are now enrolled in the course.');
            } else {
                $payment->update(['status' => 'failed']);
                return redirect()->route('dashboard')
                    ->with('error', 'Payment was not completed. Please try again.');
            }
        } catch (ApiErrorException $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $payment = Payment::where('stripe_session_id', $session->id)->first();

                if ($payment && $payment->status === 'pending') {
                    $payment->update(['status' => 'completed']);

                    // Create enrollment if not exists
                    if (!$payment->user->enrolledCourses()->where('course_id', $payment->course_id)->exists()) {
                        $payment->user->enrolledCourses()->attach($payment->course_id, [
                            'enrolled_at' => now(),
                            'progress_percentage' => 0,
                        ]);
                    }
                }
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                // Handle failed payment
                break;
        }

        return response()->json(['status' => 'success']);
    }
}