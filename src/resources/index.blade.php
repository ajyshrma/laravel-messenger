@extends($layout)

@push('scripts')
@messengerCss
@endpush

@section('content')

<div class="page-wrapper">
    <!-- /Page Header -->
    <div class="chat-page">
        <div class="container">
            <div class="chat-window">
                <div class="chat-cont-left">
                    @include('laravel-messenger::partials.templates.chat-left')
                </div>
                <div class="chat-cont-right">
                    <div class="chat-content">
                    </div>

                    <div class="chat-footer">
                        <form method="POST" id="chat-message-form">
                            @csrf
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="btn-file btn">
                                        <i class="fa fa-paperclip" onclick="$('#attachment').click()"></i>
                                        <input type="file" id="attachment" name="attachment" style="display: none;" accept="image/*">
                                    </div>
                                </div>
                                <input type="hidden" name="chat_id" value="{{$active_chat_id}}" />
                                <input type="text" id="mytextarea" name="message" class="input-msg-send form-control" placeholder="Type your message here ..." autocomplete="off">
                                <div class="input-group-append">
                                    <button type="button" class="btn msg-send-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('laravel-messenger::partials.modals.attachment')
@include('laravel-messenger::partials.modals.create-new-chat')
@endsection

@push('scripts')
@messengerRoutes
@messengerJs
@endpush
