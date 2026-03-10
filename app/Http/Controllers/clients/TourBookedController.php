<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\Booking;
use App\Models\Clients\Tours;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TourBookedController extends Controller
{
    private $tour;
    private $booking;

    public function __construct()
    {
        $this->tour = new Tours();
        $this->booking = new Booking();
    }
    public function index(Request $req)
    {
        $title = "Tour đã đặt";

        $bookingId = $req->input('bookingId');
        $checkoutId = $req->input('checkoutId');
        $tour_booked = $this->tour->tourBooked($bookingId, $checkoutId);

        // Check if the tour_booked has valid data before accessing properties
        $today = Carbon::now();
        $startDate = Carbon::parse($tour_booked->startDate);

        if ($startDate->greaterThan($today)) {
            $daysBeforeStart = $today->diffInDays($startDate);
            $hide = $daysBeforeStart < 7 ? 'hide' : '';
        } else {
         // Tour đã bắt đầu hoặc đã qua → không cho hủy
        $hide = 'hide';
}

        // dd($tour_booked);
        return view("clients.tour-booked", compact('title', 'tour_booked', 'hide', 'bookingId'));
    }

    public function cancelBooking(Request $req)
    {
        $tourId = $req->tourId;
        $quantityAdults = $req->quantity__adults;
        $quantityChildren = $req->quantity__children;
        $bookingId = $req->bookingId;


        $tour = $this->tour->getTourDetail($tourId);
        $currentQuantity = $tour->quantity;

        // Tính toán số lượng trả lại
        $return_quantity = $quantityAdults + $quantityChildren;

        // Cập nhật lại số lượng mới cho tour
        $newQuantity = $currentQuantity + $return_quantity;
        $updateQuantity = $this->tour->updateTours($tourId, ['quantity' => $newQuantity]);

        // Hủy booking
        $updateBooking = $this->booking->cancelBooking($bookingId);

        if ($updateQuantity && $updateBooking) {
            toastr()->success('Hủy thành công!');
            
        }else{
            toastr()->error('Có lỗi xảy ra !');
        }

        return redirect()->route('home');
    }
}
