<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancellationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase; // Inject the purchase data

    public $refundAmount; // Inject the refund amount

    /**
     * Create a new message instance.
     *
     * @param  $purchase  // Inject the purchase object
     * @return void
     */
    public function __construct($purchase, $refundAmount)
    {
        $this->purchase = $purchase;
        $this->refundAmount = $refundAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Cancellation Mail',
        );
    }

    private function seatingInfo($ticket) {
        if($ticket->seat_type == 'ordinary') {
            $actualSeat = $ticket->seat_id - 100;
            $row = chr(ord('A') + (intdiv($actualSeat, 20)));
        } else {
            $actualSeat = $ticket->seat_id;
            $row = chr(ord('K') + (intdiv($actualSeat, 20)));
        }
        $seat = $actualSeat % 20;
        return [
            'row' => $row,
            'seat' => $seat,
        ];
    }

    private function getRowAndSeat() {
        $data = [];
        foreach($this->purchase->tickets as $ticket) {
            $data[$ticket->id] = $this->seatingInfo($ticket->showSeat);
        }
        return $data;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_cancellation', // Create this view
            with: [
                'purchase' => $this->purchase,
                'refundAmount' => $this->refundAmount,
                'seatsInfo' => $this->getRowAndSeat()
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
