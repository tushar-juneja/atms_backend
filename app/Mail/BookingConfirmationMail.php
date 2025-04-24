<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase; // Inject the purchase data

    /**
     * Create a new message instance.
     *
     * @param  $purchase  // Inject the purchase object
     * @return void
     */
    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation Mail',
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
        // }
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
    public function content()
    {
        return new Content(
            view: 'emails.booking_confirmation', // Create this view
            with: [
                'purchase' => $this->purchase,
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
