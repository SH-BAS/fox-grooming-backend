@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card chat-card overflow-hidden" style="height: calc(100vh - 180px);">
                <div class="row g-0 h-100">
                    <!-- Chat Sidebar (Info) -->
                    <div class="col-lg-4 col-xl-3 border-end d-none d-lg-block bg-light">
                        <div class="p-4 text-center border-bottom bg-white">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="{{ $user->image }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle"
                                    style="width: 18px; height: 18px;"></span>
                            </div>
                            <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">Customer Since
                                {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}
                            </p>
                        </div>

                        <div class="p-4">
                            <h6 class="text-uppercase small fw-bold text-muted mb-3">Booking Information</h6>
                            <div class="bg-white rounded p-3 shadow-sm mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <span class="small">{{ $booking->booking_date }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <span
                                        class="small">{{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') : 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paw text-warning me-2"></i>
                                    <span class="small">{{ $booking->pet->name ?? 'N/A' }}
                                        ({{ $booking->pet->category->name ?? 'Pet' }})</span>
                                </div>
                            </div>

                            <h6 class="text-uppercase small fw-bold text-muted mb-3">Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>View Booking
                                </a>
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-user me-2"></i>User Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Main Area -->
                    <div class="col-lg-8 col-xl-9 d-flex flex-column h-100 bg-white">
                        <!-- Chat Header -->
                        <div
                            class="p-3 border-bottom d-flex align-items-center justify-content-between px-4 sticky-top bg-white z-index-1">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light me-3 d-lg-none">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->image }}" alt="{{ $user->name }}"
                                        class="rounded-circle me-2 d-lg-none"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                                        <small class="text-success"><i class="fas fa-circle font-size-10 me-1"></i>
                                            Online</small>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a href="{{ route('admin.bookings.index') }}"
                                    class="ms-auto btn btn-primary btn-xs sharp shadow"><i
                                        class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div id="chatMessages" class="chat-messages p-4 flex-grow-1 overflow-auto bg-light"
                            style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Loading your conversation...</p>
                            </div>
                        </div>

                        <!-- Chat Input Area -->
                        <div class="p-4 border-top bg-white">
                            <form id="chatForm">
                                <div
                                    class="input-group input-group-lg shadow-sm border rounded-pill overflow-hidden bg-light px-2">
                                    <input type="text" id="chatInput" class="form-control border-0 bg-transparent"
                                        placeholder="Write your message here..." required autocomplete="off">
                                    <button
                                        class="btn btn-primary rounded-circle m-1 d-flex align-items-center justify-content-center"
                                        type="submit" id="sendBtn" style="width: 46px; height: 46px;">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chat-card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 15px;
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            gap: 12px;
            scroll-behavior: smooth;
        }

        .message {
            max-width: 75%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            animation: messageAppear 0.3s ease-out;
            position: relative;
        }

        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(15px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .message.admin {
            align-self: flex-end;
            background: linear-gradient(135deg, var(--primary), #4a90e2);
            color: black;
            border-bottom-right-radius: 4px;
        }

        .message.user {
            align-self: flex-start;
            background-color: white;
            color: #212529;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.7rem;
            margin-top: 5px;
            display: block;
            opacity: 0.7;
        }

        .message.admin .message-time {
            text-align: right;
            color: rgb(25 22 22 / 80%);
        }

        .message.user .message-time {
            color: #888;
        }

        .font-size-10 {
            font-size: 8px;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .sticky-top {
            z-index: 10;
        }

        #chatInput:focus {
            box-shadow: none;
        }

        .z-index-1 {
            z-index: 1;
        }
    </style>

    @push('scripts')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            $(document).ready(function () {
                const bookingId = "{{ $booking->id }}";
                const userId = "{{ $user->id }}";
                let lastMessageId = 0;

                // Enable Pusher logging for debugging
                Pusher.logToConsole = true;

                var pusher = new Pusher("{{ env('PUSHER_APP_KEY', '4f918ad3ae17f81ec65b') }}", {
                    cluster: "{{ env('PUSHER_APP_CLUSTER', 'ap2') }}",
                    authEndpoint: "{{ url('broadcasting/auth') }}",
                    auth: {
                        headers: {
                            'X-CSRF-Token': "{{ csrf_token() }}"
                        }
                    }
                });

                var channel = pusher.subscribe(`private-bookings.${bookingId}`);

                channel.bind('message.sent', function (data) {
                    console.log('Real-time event received:', data);
                    loadMessages(false); // Trigger fetch when message arrives
                });

                function loadMessages(shouldScroll = true) {
                    $.ajax({
                        url: "{{ route('admin.chat.getMessages') }}",
                        type: "GET",
                        data: { booking_id: bookingId, user_id: userId },
                        success: function (messages) {
                            let html = '';
                            if (messages.length === 0) {
                                html = '<div class="text-center text-muted py-5"><i class="fas fa-comments fa-3x mb-3 opacity-25"></i><p>No messages yet. Start the conversation!</p></div>';
                            } else {
                                messages.forEach(msg => {
                                    const typeClass = msg.is_admin ? 'admin' : 'user';
                                    html += `<div class="message ${typeClass}" data-id="${msg.id}">
                                        <div class="message-content">${msg.message}</div>
                                        <span class="message-time">${msg.time} • ${msg.date}</span>
                                        </div>`;
                                    lastMessageId = msg.id;
                                });
                            }

                            const chatBox = $('#chatMessages');
                            const isAtBottom = chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight - 100;

                            chatBox.html(html);

                            if (shouldScroll || isAtBottom) {
                                chatBox.scrollTop(chatBox[0].scrollHeight);
                            }
                        }
                    });
                }

                // Initial load
                loadMessages();

                $('#chatForm').submit(function (e) {
                    e.preventDefault();
                    const btn = $('#sendBtn');
                    const input = $('#chatInput');
                    const message = input.val().trim();

                    if (!message) return;

                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                    $.ajax({
                        url: "{{ route('admin.chat.sendMessage') }}",
                        type: "POST",
                        headers: {
                            'X-Socket-ID': pusher.connection.socket_id
                        },
                        data: {
                            _token: "{{ csrf_token() }}",
                            booking_id: bookingId,
                            user_id: userId,
                            message: message
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                input.val('');
                                loadMessages(true);
                            }
                            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                        },
                        error: function () {
                            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                            alert('Failed to send message.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection