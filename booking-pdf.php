<?php
declare(strict_types=1);
require_once __DIR__.'/lib/booking.php';
$ref=strtoupper(trim((string)($_GET['ref']??'')));$email=strtolower(trim((string)($_GET['email']??'')));
$booking=mt_booking_find_for_customer($ref,$email);
if(!$booking){http_response_code(404);exit('Booking not found.');}
$order=mt_booking_order($booking);$confirmed=($booking['status']??'')==='confirmed';$pdf=mt_pdf_bytes($booking,$confirmed?'confirmed':'receipt',$order);
header('Content-Type: application/pdf');header('Content-Disposition: inline; filename="'.$ref.'-'.($confirmed?'booking-confirmed':'payment-received').'.pdf"');header('Content-Length: '.strlen($pdf));echo $pdf;
