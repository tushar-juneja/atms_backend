@extends('layouts.admin')

@section('styles')
    <style>
        .row_no {
            padding-inline: 6px;
            border-radius: 4px;
            background-color: darkcyan;
            color: #fff;
            font-weight: 500;
            font-size: 15px;
            width: 22px;
            border-color: rgb(2, 82, 82);
            height: fit-content;
        }

        .column_no .row_no {
            padding-block: 2px;
            padding-inline: 5px;
            /* font-size: 15px; */
        }

        .seat-icon:hover {
            cursor: pointer;
        }

        .row-vip {
            align-self: center;
            margin-top: 0px;
        }

        .vip-row {
            color: grey;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div class="card container pt-2 pb-5">
        <div class="card-body">
            <div class="container mt-4">
                <h4>Configure details for <strong><em>{{ $show->name }}</em></strong></h4>

                <div class="mt-5">
                    <h5 class="text-center">Tickets Configuration</h5>

                    <form action="{{ route('show_manager.shows.update_seating_config', $show) }}" method="POST">
                        @csrf

                        <div class="d-flex w-100 justify-content-center mt-4">
                            <div class="layout d-flex flex-column col-12" style="max-width: 80%;">
                                {{-- <div class="column_no d-flex mb-2" style="margin-left: 37px;">
                                        @for ($i = 0; $i < $config['rows'] / $config['break_at']; $i++)
                                            <div class="column_no_row" style="margin-right: 55px;">
                                                @for ($j = 1; $j <= $config['break_at']; $j++)
                                                    <span class="row_no" style="{{ $j == 1 ? '' : 'margin-left: 13.5px;'}}">{{ $j }}</span>
                                                @endfor
                                            </div>
                                        @endfor
                                    </div> --}}

                                <div>

                                    @php $row = 'A'; @endphp
                                    @for ($i = 0; $i < $config['balcony_rows']; $i++)
                                        <div class="d-flex justify-content-between"
                                            style="width: 100%; margin-bottom: -15px;">
                                            @php $local_count = 0; @endphp
                                            <span class="row_no">{{ $row }}</span>
                                            @for ($j = 0; $j < $config['seats_per_row']; $j++)
                                                <div style="margin-bottom: -15px;">
                                                    <span class="seat-icon material-symbols-outlined"
                                                        data-row="{{ $row }}">
                                                        chair
                                                    </span>
                                                </div>
                                                @php
                                                    if ($local_count == $config['break_at'] - 1) {
                                                        echo "<div style='margin-left: 40px;'></div>";
                                                        $local_count = 0;
                                                    } else {
                                                        $local_count++;
                                                    }
                                                @endphp
                                            @endfor

                                            <div class="d-flex">
                                                <input type="hidden" name="rows[{{ $row }}][balcony]"
                                                    value="1">
                                                <input type="hidden" name="rows[{{ $row }}][vip]" value="off">
                                                <input type="number" style="max-width: 150px;"
                                                    name="rows[{{ $row }}][price]" class="row-price form-control"
                                                    data-row="{{ $row }}"
                                                    placeholder="Row {{ $row }} Price">
                                                <input type="checkbox" class="row-vip form-check-input"
                                                    name="rows[{{ $row }}][vip]" data-row="{{ $row }}"
                                                    data-bs-toggle="tooltip" title="Mark Row for VIP">
                                            </div>
                                            @php $row++; @endphp

                                        </div>
                                        <br>
                                    @endfor
                                </div>
                                <div class="mt-5">
                                    @for ($i = 0; $i < $config['rows'] - $config['balcony_rows']; $i++)
                                        <div class="d-flex justify-content-between"
                                            style="width: 100%; margin-bottom: -15px;">
                                            @php $local_count = 0; @endphp
                                            <span class="row_no">{{ $row }}</span>
                                            @for ($j = 0; $j < $config['seats_per_row']; $j++)
                                                <div style="margin-bottom: -15px;">
                                                    <span class="seat-icon material-symbols-outlined"
                                                        data-row="{{ $row }}">
                                                        chair
                                                    </span>
                                                </div>
                                                @php
                                                    if ($local_count == $config['break_at'] - 1) {
                                                        echo "<div style='margin-left: 40px;'></div>";
                                                        $local_count = 0;
                                                    } else {
                                                        $local_count++;
                                                    }
                                                @endphp
                                            @endfor
                                            <div class="d-flex">
                                                <input type="hidden" name="rows[{{ $row }}][balcony]"
                                                    value="0">
                                                <input type="hidden" name="rows[{{ $row }}][vip]" value="off">
                                                <input type="number" style="max-width: 150px;"
                                                    class="row-price form-control" data-row="{{ $row }}"
                                                    name="rows[{{ $row }}][price]"
                                                    placeholder="Row {{ $row }} Price">
                                                <input type="checkbox" class="row-vip form-check-input"
                                                    name="rows[{{ $row }}][vip]" data-row="{{ $row }}"
                                                    data-toggle="tooltip" title="Mark Row for VIP">
                                            </div>
                                            @php $row++; @endphp
                                        </div>
                                        <br>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mr-4">
                            <input type="Submit" value="Save" class="btn btn-primary">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.row-vip').on('change', function() {
                const row = $(this).data('row');
                const isVip = $(this).prop('checked');

                if (confirm(`Are you sure you want to reserve all seats of row ${row}?`)) {
                    const rowPriceInput = $(`.row-price[data-row="${row}"]`);
                    const seatIcons = $(`.seat-icon[data-row="${row}"]`);

                    if (isVip) {
                        rowPriceInput.prop('disabled', true);
                        seatIcons.addClass('vip-row').off('mouseenter mouseleave click');
                    } else {
                        rowPriceInput.prop('disabled', false);
                        seatIcons.removeClass('vip-row').on('mouseenter mouseleave click', seatHover);
                    }
                } else {
                    $(this).prop('checked', !isVip); // Revert checkbox state
                }
            });

            $('.seat-icon').on('mouseenter', function() {
                // $(this).css('color', 'lightblue'); // Change color on hover
                $(this).addClass('material-symbols-outlined-hover'); // Change color on hover
            }).on('mouseleave', function() {
                // if (!seatData[$(this).data('seat-id')].vip) {
                //     $(this).css('color', ''); // Reset color if not VIP
                // } else {
                //     $(this).css('color', 'red'); // Keep red if VIP
                // }

                // $(this).css('color', ''); // Reset color on mouse leave

                $(this).removeClass('material-symbols-outlined-hover'); // Change color on hover
            });

            // ... (Your existing code for VIP button click) ...
        });
    </script>
@endsection
